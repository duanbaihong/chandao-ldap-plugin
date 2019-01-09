<?php
/**
 * The model file of ldap module of ZenTaoPMS.
 *
 * @license     ZPL (http://zpl.pub/page/zplv11.html)
 * @author      TigerLau
 * @package     ldap
 * @link        http://www.zentao.net
 */
?>
<?php
class ldapModel extends model
{
    public function identify($host, $dn, $pwd)
    {
        $ret = '';
        $ds = ldap_connect($host);
        if ($ds) {
            ldap_set_option($ds,LDAP_OPT_PROTOCOL_VERSION,3);
            ldap_bind($ds, $dn, $pwd);

            $ret = ldap_error($ds);
            ldap_close($ds);
        }  else {
            $ret = ldap_error($ds);
        }

        return $ret;
    }

    public function getGroups($config)
    {
        $ds = ldap_connect($config->proto."://".$config->host,$config->port);
        if ($ds) {
            ldap_set_option($ds,LDAP_OPT_PROTOCOL_VERSION,3);
            ldap_bind($ds, $config->bindDN, $config->bindPWD);
            $attrs = [$config->groupField];
            $rlt = ldap_search($ds, $config->groupSearchOU.','.$config->baseDN, $config->groupFilter, $attrs);
            $data = ldap_get_entries($ds, $rlt);
            return $data;
        }

        return null;
    }
    public function getUsers($config)
    {
        $ds = ldap_connect($config->host);
        if ($ds) {
            ldap_set_option($ds,LDAP_OPT_PROTOCOL_VERSION,3);
            ldap_bind($ds, $config->bindDN, $config->bindPWD);

            $attrs = [$config->uid, $config->mail, $config->name];

            $rlt = ldap_search($ds, $config->baseDN, $config->searchFilter, $attrs);
            $data = ldap_get_entries($ds, $rlt);
            return $data;
        }
        return null;
    }
    public function sync2db($config)
    {
        $ldapGroups = $this->getGroups($config);
        echo var_dump($ldapGroups);
        $user = new stdclass();
        $account = '';
        $i=0;
        // for (; $i < $ldapGroups['count']; $i++) {         
        //     $user->account = $ldapGroups[$i][$config->uid][0];
        //     $user->email = $ldapGroups[$i][$config->mail][0];
        //     $user->realname = $ldapGroups[$i][$config->name][0];

        //     $account = $this->dao->select('*')->from(TABLE_USER)->where('account')->eq($user->account)->fetch('account');
        //     if ($account == $user->account) {
        //         $this->dao->update(TABLE_USER)->data($user)->where('account')->eq($user->account)->autoCheck()->exec();
        //     } else {
        //         $this->dao->insert(TABLE_USER)->data($user)->autoCheck()->exec();
        //     }

        //     if(dao::isError()) 
        //     {
        //         echo js::error(dao::getError());
        //         die(js::reload('parent'));
        //     }
        // }

        // return $i;
    }
}
