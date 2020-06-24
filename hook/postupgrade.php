<?php


    $extensionPath = $this->app->getModulePath('', 'extension') . 'ext' . DS . $extension . DS . 'db' . DS . 'install.sql';
    $this->loadModel('upgrade')->execSQL($extensionPath);
