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
    public function identify($username,$userpass)
    {
        $config=$this->config->ldap;
        $usermap = json_decode(stripslashes($config->userFieldMap),true);
        if(is_null($usermap) || empty($usermap)){
            return $this->lang->ldap->chkUserFieldErr;
        }
        if(!array_key_exists('account',$usermap)) return '{"code":"99999","results": "'.$this->lang->ldap->chkUserFieldErr1.'"}';
        $group_attrs=array('memberof');
        $ldap_user_attrs=array_merge(array_values($usermap),$group_attrs);

        $ldap_user  = '';
        $ldapconfig = $this->config->ldap;
        $ldapconn   = "{$ldapconfig->proto}://{$ldapconfig->host}";
        $userdn     = "{$usermap['account']}={$username},{$ldapconfig->userSearchOU},{$ldapconfig->baseDN}";
        $ds = ldap_connect($ldapconn,$ldapconfig->port);
        if ($ds) {
            ldap_set_option($ds,LDAP_OPT_PROTOCOL_VERSION,$ldapconfig->version);
            $ld_bind=ldap_bind($ds, $userdn, $userpass);
            $ld_user_filter=sprintf($ldapconfig->userFilter,$username);
            $result_identifier=ldap_search($ds,$userdn,$ld_user_filter,$ldap_user_attrs);
            $ldap_user=ldap_get_entries($ds,$result_identifier);
            ldap_close($ds);
        }  else {
            $ldap_user = ldap_error($ds);
        }
        return $ldap_user;
    }

    public function getGroups($config,$attrs=array())
    {
        $ds = ldap_connect($config->proto."://".$config->host,$config->port);
        if ($ds) {
            ldap_set_option($ds,LDAP_OPT_PROTOCOL_VERSION,$config->version);
            ldap_bind($ds, $config->bindDN, $config->bindPWD);
            $groupdn="$config->groupSearchOU,$config->baseDN";
            $rlt = ldap_search($ds,$groupdn, $config->groupFilter, $attrs);
            if(!$rlt){
                 throw new Exception(ldap_error($ds));
            }
            $data = ldap_get_entries($ds, $rlt);
            ldap_close($ds);
            return $data;
        }
        return null;
    }
    public function syncGroups2db($config)
    {
        $groupmap = json_decode(stripslashes($config->groupFieldMap),true);
        if(is_null($groupmap) || empty($groupmap)){
            return $this->lang->ldap->chkGrpFieldErr;
        }
        $attrs=array_values($groupmap);
        try {
            $ldapGroups = $this->getGroups($config,$attrs);
        } catch (Exception $e) {
            return '{"code":"99999","results": "LDAP Say:'.$e->getMessage().'"}';
        }
        if(!is_array($ldapGroups)) return '{"code":"99999","results": "'.$ldapGroups.'"}';
        if(is_null($ldapGroups)) return '{"code":"99999","results": "LDAP返回数据为空。"}';
        $group = new stdclass();
        $name = '';
        $syncNum=0;
        for ($i=0; $i < $ldapGroups['count']; $i++) {
            foreach ($groupmap as $key => $val ) 
            {
                $group->$key=$ldapGroups[$i][$val][0];
            }
            if(!property_exists($group,'name')) return '{"code":"99999","results": "'.$this->lang->ldap->chkGrpFieldErr1.'"}'; 
            $name = $this->dao->select('*')->from(TABLE_GROUP)->where('name')->eq($group->name)->fetch('name');
            if ($name == $group->name) {
                continue;
            } else {
                $syncNum+=1;
                $this->dao->insert(TABLE_GROUP)->data($group)->autoCheck()->exec();
            }
        }
        $msg=sprintf($this->lang->ldap->findGroupsMsg,$ldapGroups['count'],$syncNum,$ldapGroups['count']-$syncNum);
        return '{"code": "00000","results": "'.$msg.'"}';
    }
}
