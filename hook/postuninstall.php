<?php


    $extensionPath = $this->app->getModulePath('', 'extension') . 'ext' . DS . $extension . DS . 'db' . DS . 'uninstall.sql';
    $this->loadModel('upgrade')->execSQL($extensionPath);
