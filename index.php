<?php
//Phase: Bootstrap
//session_start();
define('JRNEK_INSTALL_PATH', dirname(__FILE__));
define('JRNEK_SITE_PATH', JRNEK_INSTALL_PATH . '/site');

require_once(JRNEK_INSTALL_PATH.'/src/bootstrap.php');
$jr = CJrnek::Instance();

//Phase: Frontcontrollerroute 
$jr->FrontControllerRoute();

//Phase ThemeEningeRender
$jr->ThemeEngineRender();
