<?php session_start(); ob_start();
//Report all errors except E_NOTICE
error_reporting(E_ALL ^ E_NOTICE);

ini_set('display_errors', 1); // Value 0 Not Show Error,1 Show Error 
ini_set('magic_quotes_gpc', 'Off');
ini_set('register_globals', 'Off');
ini_set('date.timezone', 'Asia/Bangkok');

if (get_magic_quotes_gpc() === 1) if($_POST) foreach($_POST as $k => $v) $_POST[$k] = stripslashes($v);
define('DS', DIRECTORY_SEPARATOR);
define('SYSTEM_DIR','backoffice'	);
define('BASE_PATH', realpath(dirname(__FILE__)));
define('EXT', '.' . pathinfo(__FILE__, PATHINFO_EXTENSION));
define('CURRENT_TIME', time());
define('HTML', '.html');
define('DATETIME',		date('Y-m-d H:i:s'));

/*
if(empty($_SESSION['lang'])) $_SESSION['lang'] 	= 'th';
if(!empty($_GET['l']) && ( $_GET['l']=='th' || $_GET['l']=='en' ) ) $_SESSION['lang'] = $_GET['l'];
*/
require BASE_PATH . DS . SYSTEM_DIR . DS . 'ck.define' . EXT;


//-------------------------/ SET DEFAULTS SWITCH /------------------------//
?>
