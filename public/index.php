<?php
/**
 * This makes our life easier when dealing with paths. Everything is relative
 * to the application root now.
 */
chdir(dirname(__DIR__));

// Setup autoloading
require 'init_autoloader.php';

//Setup Stormpath Autoloading
require 'library/stormpath/stormpath-sdk-php/Services/Stormpath.php'; // invoke Stormpath SDK

//for user experience, set up error reporting.
error_reporting(E_ERROR | E_PARSE | E_NOTICE);

//set library folder as lib path so stormpath configuration file can be read.
ini_set('include_path', 'library');

require_once 'Zend/Loader/StandardAutoloader.php';
$loader = new Zend\Loader\StandardAutoloader(array(
    'autoregister_zf' => true,
    'namespaces' => array(
        'Tooter' => __DIR__.'/../library/Tooter',
    ),
    'fallback_autoloader' => true,
));

// Register with spl_autoload:
$loader->register();


// Run the application!
Zend\Mvc\Application::init(require 'config/application.config.php')->run();

