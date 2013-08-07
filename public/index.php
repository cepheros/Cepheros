<?php

// Define path to application directory
defined('APPLICATION_PATH')
    || define('APPLICATION_PATH', realpath(dirname(__FILE__) . '/../application'));

defined('SYSCONFIGS_PATH')
|| define('SYSCONFIGS_PATH', realpath(dirname(__FILE__) . '/../application/configs'));

defined('NFeCONFIGS_PATH')
|| define('NFeCONFIGS_PATH', realpath(dirname(__FILE__) . '/../application/configs/NFe'));

// Define application environment
defined('APPLICATION_ENV')
    || define('APPLICATION_ENV', (getenv('APPLICATION_ENV') ? getenv('APPLICATION_ENV') : 'production'));
    
defined('LIBRARY_PATH')
    || define('LIBRARY_PATH', realpath(dirname(__FILE__) . '/../library'));


defined('UPDATES_PATH')
    || define('UPDATES_PATH', realpath(dirname(__FILE__) . '/../application/data/updates'));

// Ensure library/ is on include_path
set_include_path(implode(PATH_SEPARATOR, array(
    realpath(APPLICATION_PATH . '/../library'),
    get_include_path(),
)));

/** Zend_Application */
require_once 'Zend/Application.php';

// Create application, bootstrap, and run
$application = new Zend_Application(
    APPLICATION_ENV,
    APPLICATION_PATH . '/configs/application.ini'
);
$application->bootstrap()
            ->run();