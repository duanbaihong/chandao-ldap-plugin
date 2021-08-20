<?php
public function identify($account, $password)
{
    $user=false; 
    $ldap = $this->loadModel('ldap');
    if($this->config->ldap->ldapOpen){ 
        if($account!="admin"){
            $user = $ldap->userauth($account, $password);
        }
        if(!is_object($user)){
            $record = $this->dao->select('account')->from(TABLE_USER)
                 ->where('account')->eq($account)
                 ->beginIF(strlen($password) < 32)->andWhere('password')->eq(md5($password))->fi()
                 ->andWhere('deleted')->eq(0)
                 ->andWhere('user_type')->eq("ldap")
                 ->fetch();
            if(!$record){
                $user = parent::identify($account, $password);
            }else{
                $user=false;
            }
        }
    }else{
        $user = parent::identify($account, $password);
    }
    return $user;
}