<?php
// Define path to application directory
defined('APPLICATION_PATH') || define('APPLICATION_PATH', realpath(dirname(__FILE__) . '/../application'));

// Define application environment
defined('APPLICATION_ENV') || define('APPLICATION_ENV', (getenv('APPLICATION_ENV') ? getenv('APPLICATION_ENV') : 'production'));

// Ensure library/ is on include_path
set_include_path(implode(PATH_SEPARATOR, array(realpath(APPLICATION_PATH . '/../library'), get_include_path(), )));

/** Zend_Application */
set_include_path('../library/');
require_once 'Zend/Application.php';

// Create application, bootstrap, and run
$application = new Zend_Application(APPLICATION_ENV,APPLICATION_PATH . '/configs/application.ini');

// Seta o timezone pra são paulo (>=PHP 5.1)
setlocale (LC_ALL, 'pt_BR');
date_default_timezone_set('America/Sao_Paulo');

// definindo o nome do sistema
define('SISTEMA','iZend');

$application->bootstrap()->run();
