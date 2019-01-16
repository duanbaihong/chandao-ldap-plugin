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
	        $user = parent::identify($account, $password);
	    }
	}else{
		$user = parent::identify($account, $password);
	}
    return $user;
}