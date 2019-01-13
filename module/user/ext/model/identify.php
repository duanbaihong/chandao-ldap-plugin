<?php
public function identify($account, $password)
{
    $user = parent::identify($account, $password);
    if (!$user) {    
        if ($record && empty($record->password)) {
            $ldap = $this->loadModel('ldap');
            $user = $ldap->userauth($account, $password);
        }
    }   
    return $user;
}