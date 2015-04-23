<?php

header("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); // Date in the past

if ( file_exists( 'config/config.php' ) )
{
    define( 'CURRENT_PATH',dirname(__FILE__) );
    require_once 'config/config.php';
}
else
{
    exit('no fue posible localizar el archivo de configuración.');
}

function __autoload( $className )
{
    require_once LIBS_PATH . "{$className}.php";
}


if ( !empty( $_GET['action'] ) )
{
    $action    = strip_tags( trim( $_GET[ 'action' ] ) );
    $data      = array();
    try
    {
        switch ( $action )
        {
            case 'contactForm':
                $parameters[ 'sucursal' ]   = trim( $_POST[ 'sucursal' ] );
                $parameters[ 'nombre' ]     = trim( $_POST[ 'nombre' ] );
                $parameters[ 'correo' ]     = trim( $_POST[ 'correo' ] );
                $parameters[ 'subject' ]    = trim( $_POST[ 'subject' ] );
                $parameters[ 'mensaje' ]    = trim( $_POST[ 'mensaje' ] );
                $template                   = 'email.tpl';
                $subject                    = 'Hay un nuevo correo de contacto desde Sipirily.com';
                $sender                     = 'contacto@sipirily.com';
                $cc                         =   [
                                                    [ 'mail'  => 'jesus.garciav@me.com',
                                                      'name'  => 'Jesús' ]
                                                ];
                $sended                     = SenderEmail::send( $parameters, $template, $subject, $sender, $cc );
                $data                       = json_encode ( $sended );
                break;
        }
        echo $data;
    }
    catch ( Exception $e )
    {
        switch ( $e->getCode() )
        {
            case 5910 :
                echo 'DATA BASE ERROR: '.$e->getMessage();
                $message = 'Lo sentimos, ocurrió un error inesperado al tratar de guardar los datos.';
                break;
            case 5810 :
                echo 'MAILER ERROR: '. $e->getMessage();
                $message = 'Lo sentimos, ocurrió un error inesperado al tratar de enviar el correo.';
                break;
            default : $message = $e->getMessage();
        }

        $data = array ('success' => false , 'message' => $message ) ;
        echo json_encode( $data );
    }
}