<?php
public function identify($account, $password)
{
    $user = parent::identify($account, $password);
    if (!$user) {    
        $ldap = $this->loadModel('ldap');
        $user = $ldap->userauth($account, $password);
        if(!is_object($user)){
            echo $user;
            return false;
        }
    }   
    return $user;
}