<?php
// error_reporting( E_ALL | E_STRICT ) ;
// ini_set( 'display_errors', 'On' );
error_reporting( 0 ) ;
ini_set( 'display_errors', 'Off' );
date_default_timezone_set( 'America/Mexico_City' );

define( 'BASE_PATH', dirname(dirname(dirname((dirname(__FILE__))))) . DIRECTORY_SEPARATOR );
define( 'SITE_URL', 'http://sipirily.app' . DIRECTORY_SEPARATOR );
define( 'BASE_URL', 'http://sipirily.app' . DIRECTORY_SEPARATOR );
define( 'ASSETS_PATH', BASE_PATH . 'assets' . DIRECTORY_SEPARATOR );
define( 'SNIPPETS_PATH', ASSETS_PATH . 'snippets' . DIRECTORY_SEPARATOR );
define( 'CLASSES_PATH', SNIPPETS_PATH . 'classes'. DIRECTORY_SEPARATOR );
define( 'LIBS_PATH', SNIPPETS_PATH . 'libs' . DIRECTORY_SEPARATOR );
define( 'TEMPLATES_PATH', LIBS_PATH . 'templates' . DIRECTORY_SEPARATOR );

//require_once LIBS_PATH . 'Common.php';

function _convertUTF8 ( &$item , $keys ){
    $item = utf8_encode($item);
}
?>
