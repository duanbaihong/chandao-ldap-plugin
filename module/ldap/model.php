<?php
/**
 * The model file of ldap module of ZenTaoPMS.
 *
 * @license     dbh ()
 * @author      dbh888
 * @package     ldap
 * @link        http://www.zentao.net
 */
class ldapModel extends model
{
    protected $ldap_config;
    protected $ldap_usermap;
    protected $ldap_groupsdn;
    protected $ldap_groupmap;
    protected $ldap_groupattrs;
    protected $ldap_usersdn;
    protected $ldap_userattrs;
    protected $ldap_protoaddr;
    protected $ldap_conn;
    protected $ldap_bind;
    protected $syncNum;
    public function __construct()
    {
        parent::__construct();
        if(!function_exists('ldap_connect')) return false;
        
        $this->ldap_config=$this->config->ldap;
        // 用户BASEＤＮ拼接
        $this->ldap_usersdn="{$this->ldap_config->userSearchOU},{$this->ldap_config->baseDN}";
        // 组BASEDN拼接
        $this->ldap_groupsdn="{$this->ldap_config->groupSearchOU},{$this->ldap_config->baseDN}";

        // user 映射
        $this->ldap_usermap=json_decode(stripslashes($this->ldap_config->userFieldMap),true);
        
        $this->ldap_userattrs=array_values($this->ldap_usermap);
        // group 映射
        $this->ldap_groupmap=json_decode(stripslashes($this->ldap_config->groupFieldMap),true);
        
        // 获取ＬＤＡＰ组字段映射
        $this->ldap_groupattrs=array_values($this->ldap_groupmap);
        // 格式连接地址参数
        $this->ldap_protoaddr="{$this->ldap_config->proto}://{$this->ldap_config->host}:{$this->ldap_config->port}";
        // 建立ＬＤＡＰ连接
        $this->ldap_conn=ldap_connect($this->ldap_protoaddr,$this->ldap_config->port);
        ldap_set_option($this->ldap_conn,LDAP_OPT_PROTOCOL_VERSION,$this->ldap_config->version);
        // bind dn
        // $this->ldap_bind=ldap_bind($this->ldap_conn,$this->ldap_config->bindDN,$this->ldap_config->bindPWD);

    }
    /**
     * 效验参数
     * @return [type] [description]
     */
    protected function checkargs()
    {
        if(is_null($this->ldap_usermap) || empty($this->ldap_usermap)){
            return $this->lang->ldap->chkUserFieldErr;
        }
        if(!array_key_exists('account',$this->ldap_usermap)) return sprintf($this->lang->ldap->chkUserFieldErr1,'account');
        if(!array_key_exists('realname',$this->ldap_usermap)) return sprintf($this->lang->ldap->chkUserFieldErr1,'realname');
        if(!array_key_exists('email',$this->ldap_usermap)) return sprintf($this->lang->ldap->chkUserFieldErr1,'email');

        if(is_null($this->ldap_groupmap) || empty($this->ldap_groupmap)){
            return $this->lang->ldap->chkGrpFieldErr;
        }
        if(!array_key_exists('name',$this->ldap_groupmap)) return $this->lang->ldap->chkGrpFieldErr1;
        return true;
    }
    public function userauth($username,$userpass)
    {
        $chk=$this->checkargs();
        if($chk !== true) return array("code"=>"99999","results"=>$chk);
        // user 映射
        $usernamedn="{$this->ldap_usermap['account']}={$username},{$this->ldap_usersdn}";
        $user_conn=ldap_connect($this->ldap_protoaddr,$this->ldap_config->port);
        ldap_set_option($user_conn,LDAP_OPT_PROTOCOL_VERSION,$this->ldap_config->version);
        $user_bind=ldap_bind($user_conn,$usernamedn,$userpass);
        $write_user="";
        if($user_bind){
            // 获取用户字段信息
            $ld_user_filter=sprintf($this->ldap_config->userFilter,$username);
            $result_identifier=ldap_search($user_conn,
                $usernamedn,
                $ld_user_filter,
                $this->ldap_userattrs);
            if(!$result_identifier){
                return array("code"=>"99999","results"=>ldap_error($user_conn));
            }
            $ldap_user=ldap_get_entries($user_conn,$result_identifier);
            $write_user=$this->writeUsers($ldap_user,$username,$userpass);
            ldap_close($user_conn);
            if(!is_object($write_user)) return '{"code": "99999","results": "'.$write_user.'"}';
            // 获取用户组信息
            $group_filter=sprintf('(&(|(member=%s)(uniqueMember=%s)(memberUid=%s))(&%s))',$usernamedn,$usernamedn,$username,$this->ldap_config->groupFilter);
            $ldap_group=$this->getGroups($group_filter);
            if(!is_array($ldap_group)) return '{"code": "99999","results": "'.$ldap_group.'"';
            if($this->ldap_config->syncGroups == 'true' && count($ldap_group)>0){
                $saveGroups=$this->writeGroupsInfo($ldap_group);
                if($saveGroups != true){
                   return array("code"=>"99999","results"=>$saveGroups);
                }
                $saveUserGroups=$this->writeUserGroups($ldap_group,$username);
                if($saveGroups != true){
                   return array("code"=>"99999","results"=>$saveUserGroups);
                }
            }
        }else{
            return array("code"=>"99999","results"=>ldap_error($user_conn));
        }
        return $write_user;
    }
    /**
     * 认证用户密码，通过就更新数据用户，并且同步更新用户的组信息！
     * @param  [type] $username [用户名]
     * @param  [type] $userpass [密码]
     * @return [type]           [description]
     */
    public function identify($username,$userpass)
    {
        $chk=$this->checkargs();
        if($chk !== true) return array("code"=>"99999","results"=>$chk);
        $user= $this->userauth($username,$userpass);
        if(is_object($user)){
            return array("code"=>"00000","results"=>"Authentication Success!");
        }else{
            return $user;
        }
    }
    /**
     * [writeUsers LDAP用户数据写入数据库]
     * @param  array  $users [description]
     * @return [type]        [description]
     */
    protected function writeUsers($users=array(),$username="",$password="")
    {
        if(empty($username) || empty($password) || count($users) == 0){
            return "Param Error";
        }
        $record = $this->dao->select('*')->from(TABLE_USER)
            ->where('account')->eq($username)
            ->fetch();
        $user=new stdClass();
        foreach ($this->ldap_usermap as $k => $v) {
            $user->$k=$users[0][$v][0];
        }
        $user->ip=$this->server->remote_addr;
        $user->deleted='0';
        $user->password=md5($password);
        $user->join = date(DT_DATE1, $this->server->request_time);
        $user->last = time();
        if($record){
            $user_update=$this->dao->update(TABLE_USER)
                ->set('visits = visits + 1')
                ->set('ip')->eq($user->ip)
                ->set('last')->eq($user->last)
                ->set('password')->eq($user->password)
                ->set('deleted')->eq($user->deleted)
                ->where('account')->eq($username)
                ->exec();
        }else{
            $user_insert=$this->dao->insert(TABLE_USER)->data($user)->autoCheck()->exec();
        }
        return $user;
    }
    /**
     * [写入组权限映射表usergroup]
     * @param  array  $groupdata [获取到的group信息]
     * @param  string $username  [用户名]
     * @return [type]            [description]
     */
    protected function writeUserGroups($groupdata=array(),$username="")
    {
        if(empty($groupdata) || empty($username)){
            return false;
        }
        $ldap_groupid=$this->ldap_groupmap['name'];
        $usergroup = new stdClass();
        $remote_ldapgroupmap=array();
        for ($i=0; $i < $groupdata['count']; $i++) {
            $group_id=$this->dao->select('*')
                ->from(TABLE_GROUP)
                ->where('name')->eq($groupdata[$i][$ldap_groupid][0])
                ->fetch('id');
            if(empty($group_id)) return "Falied";
            $remote_ldapgroupmap[]=$group_id;
            $usergroup->account=$username;
            $usergroup->group  = $group_id;
            $groupuser_map = $this->dao->select('*')
                ->from(TABLE_USERGROUP)
                ->where('account')->eq($usergroup->account)
                ->andWhere('`group`')->eq($usergroup->group)
                ->fetch('account');
            if ($groupuser_map) {
                continue;
            } else {
                $this->dao->insert(TABLE_USERGROUP)->data($usergroup)->exec();
            }
        }
        // 删除不存在关联的组映射！
        $local_groupmap=$this->dao->select('*')
                ->from(TABLE_USERGROUP)
                ->where('account')->eq($username)
                ->fetchPairs('group');
        $local_diffmap=array_diff($local_groupmap,$remote_ldapgroupmap);
        if(!is_null($local_diffmap) && count($local_groupmap)>0){
            $this->dao->delete()->from(TABLE_USERGROUP)
                ->where('account')->eq($username)
                ->andWhere('`group`')->in($local_diffmap)->exec();
        }
        if($this->dao::isError()){
            $response['code']='99999';
            $response['results']=dao::getError();
            $this->send($response);
        }
        return true;
    }
    /**
     * [writeGroupsInfo 写入用户组信息到数据库]
     * @param  array  $groupdata [获取到的group信息]
     */
    protected function writeGroupsInfo($groupdata=array())
    {
        if(count($groupdata) == 0) return false;
        $this->syncNum=0;
        $group = new stdclass();
        for ($i=0; $i < $groupdata['count']; $i++) {
            foreach ($this->ldap_groupmap as $key => $val ) 
            {
                $group->$key=$groupdata[$i][$val][0];
            }
            if(!property_exists($group,'name')) return $this->lang->ldap->chkGrpFieldErr1; 
            $name = $this->dao->select('*')->from(TABLE_GROUP)->where('name')->eq($group->name)->fetch('name');
            if ($name == $group->name) {
                continue;
            } else {
                $this->dao->insert(TABLE_GROUP)->data($group)->autoCheck()->exec();
                $this->syncNum+=1;
            }
        }
        return true;
    }
    /**
     * [getGroups 获取全部组信息]
     * @param  string $filter [过滤条件]
     * @param  array  $attrs  [查询属性]
     */
    protected function getGroups($filter="",$attrs=array())
    {
        $this->ldap_bind=ldap_bind($this->ldap_conn,$this->ldap_config->bindDN,$this->ldap_config->bindPWD);
        $rlt = ldap_search($this->ldap_conn,
            $this->ldap_groupsdn, 
            empty($filter)?$this->ldap_config->groupFilter:$filter, 
            count($attrs)===0?$this->ldap_groupattrs:$attrs);
        if(!$rlt){
             return ldap_error($this->ldap_conn);
        }
        $data = ldap_get_entries($this->ldap_conn, $rlt);
        return $data;
    }
    public function syncGroups2db()
    {
        $chk=$this->checkargs();
        if($chk !== true) return array("code"=>"99999","results"=>$chk);
        $ldapGroups = $this->getGroups();
        if(!is_array($ldapGroups)) return array("code"=>"99999","results"=>$ldapGroups);
        $this->writeGroupsInfo($ldapGroups);
        $msg=sprintf($this->lang->ldap->findGroupsMsg,$ldapGroups['count'],$this->syncNum,$ldapGroups['count']-$this->syncNum);
        return array("code"=>"00000","results"=>$msg);
    }
    /**
     * [testconn 测试LDAP连接]
     * @param  string  $addr [地址]
     * @param  integer $port [端口]
     * @param  string  $dn   [binddn]
     * @param  string  $pwd  [密码]
     * @param  integer $ver  [版本号]
     */
    public function testconn($addr="",$port=389,$dn="",$pwd="",$ver=3)
    {
        if(!function_exists('ldap_connect')) return false;
        $ret = '';
        $ds = ldap_connect($addr,(int)$port);
        if ($ds) {
            ldap_set_option($ds,LDAP_OPT_PROTOCOL_VERSION,$ver);
            ldap_bind($ds, $dn, $pwd);

            $ret = ldap_error($ds);
            ldap_close($ds);
        }  else {
            $ret = ldap_error($ds);
        }
        echo "{$ret}.{$addr}";
    }
    
    public function __destruct()
    {
        // parent::__destruct();
        ldap_close($this->ldap_conn);
    }
}

