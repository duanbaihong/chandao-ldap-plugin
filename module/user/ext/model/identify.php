<?php
public function identify($account, $password)
{
	$user=false;   
	if($account!="admin"){
	    $ldap = $this->loadModel('ldap');
	    $user = $ldap->userauth($account, $password);
	}
    if(!is_object($user)){
        $user = parent::identify($account, $password);
    }
    return $user;
}