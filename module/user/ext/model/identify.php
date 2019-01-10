<?php
public function identify($account, $password)
{
    $user = parent::identify($account, $password);
    if (!$user) {
        $record = $this->dao->select('*')->from(TABLE_USER)
            ->where('account')->eq($account)
            ->andWhere('deleted')->eq(0)
            ->fetch();
        if ($record && empty($record->password)) {
            $ldap = $this->loadModel('ldap');
            $ldap_user = $ldap->identify($account, $password);
            if (0 == strcmp('Success', $pass)) {
                $user = $record;
                $ip   = $this->server->remote_addr;
                $last = $this->server->request_time;
                $user->last = date(DT_DATETIME1, $user->last);
                $this->dao->update(TABLE_USER)->set('visits = visits + 1')->set('ip')->eq($ip)->set('last')->eq($last)->where('account')->eq($account)->exec();
                // throw new Exception($this->server->remote_addr, 1);
            }
        }
    }   
    throw new Exception($ldap_user, 1);
    return $user;
}