<?php

	static $errors = array();
    $installSqlFile = $this->app->getModulePath('', 'extension') . 'ext' . DS . $extension . DS . 'db' . DS . 'install.sql';
    $uninstallSqlFile = $this->app->getModulePath('', 'extension') . 'ext' . DS . $extension . DS . 'db' . DS . 'uninstall.sql';
    //$this->loadModel('upgrade')->execSQL($extensionPath);

    $installsql = file_get_contents($installSqlFile);
    $uninstallsql = file_get_contents($uninstallSqlFile);

    $installsql = str_replace('`xxxx_', '`'.$this->config->db->prefix, $installsql);
    $uninstallsql = str_replace('`xxxx_', '`'.$this->config->db->prefix, $uninstallsql);

   
    file_put_contents($installSqlFile,$installsql);
    file_put_contents($uninstallSqlFile,$uninstallsql);