<?php

if(defined('LEPTON_PATH')) { die('By security reasons it is not permitted to load \'config.php\' twice!! Forbidden call from \''.$_SERVER['SCRIPT_NAME'].'\'!'); }



// installation LEPTON 1.3.2 or higher
define('DB_TYPE', 'mysql');
define('DB_HOST', 'localhost');
define('DB_PORT', '3306');
define('DB_USERNAME', 'root');
define('DB_PASSWORD', '');
define('DB_NAME', 'lepton');
define('TABLE_PREFIX', 'lep_');

define('LEPTON_PATH', dirname(__FILE__));
define('LEPTON_URL', 'http://127.0.0.1/cmspep');
define('ADMIN_PATH', LEPTON_PATH.'/admins');
define('ADMIN_URL', LEPTON_URL.'/admins');

define('LEPTON_GUID', '390b4d8e-a38c-47a3-a82e-171043509214');
define('LEPTON_SERVICE_FOR', '');
define('LEPTON_SERVICE_ACTIVE', 0);
define('WB_URL', LEPTON_URL);
define('WB_PATH', LEPTON_PATH);

if (!defined('LEPTON_INSTALL')) require_once(LEPTON_PATH.'/framework/initialize.php');

?>