<?php
public function identify($account, $password)
{
	$user=false; 
    $ldap = $this->loadModel('ldap');
    echo $this->config->ldap->ldapOpen;
	if($this->config->ldap->ldapOpen){ 
		if($account!="admin"){
		    $user = $ldap->userauth($account, $password);
		    echo "ldap";
		}
	    if(!is_object($user)){
	        $user = parent::identify($account, $password);
	        echo "dao"
	    }
	}else{
		$user = parent::identify($account, $password);
	}
	die('');
    return $user;
}