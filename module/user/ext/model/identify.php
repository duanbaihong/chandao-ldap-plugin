<?php
public function identify($account, $password)
{
    $user = parent::identify($account, $password);
    if ($user) {
        return $user;
    } else {
        $record = $this->dao->select('*')->from(TABLE_USER)
            ->where('account')->eq($account)
            ->andWhere('deleted')->eq(0)
            ->fetch();
        if ($record) {
            if($record->password!=md5($password)){
                $ldap = $this->loadModel('ldap');
                $account = $this->config->ldap->uid.'='.$account.','.$this->config->ldap->baseDN;
                $pass = $ldap->identify($this->config->ldap->host, $account, $password);
                if (0 == strcmp('Success', $pass)) {
                    $user = $record;
                    $ip   = $this->server->remote_addr;
                    $last = $this->server->request_time;
                    $this->dao->update(TABLE_USER)->set('visits = visits + 1')->set('ip')->eq($ip)->set('last')->eq($last)->where('account')->eq($account)->exec();
                    $user->last = date(DT_DATETIME1, $user->last);
                }
            }else{
                $user = $record;
            }
        }   
        return $user;
    }
}