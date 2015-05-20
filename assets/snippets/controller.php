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
            case 'jobForm':
                $parameters[ 'sucursal' ]   = trim( $_POST[ 'branch' ] );
                $parameters[ 'nombre' ]     = trim( $_POST[ 'name' ] );
                $parameters[ 'correo' ]     = trim( $_POST[ 'mail' ] );
                $curriculumFile = '';
                if ( !empty( $_FILES[ 'curriculum' ][ 'size' ] ) )
                {
                    if ( $_FILES[ 'curriculum' ][ 'type' ] === 'application/msword' || $_FILES[ 'curriculum' ][ 'type' ] === 'application/pdf')
                    {
                        $ext            = ( $_FILES[ 'curriculum' ][ 'type' ] === 'application/msword' ) ? 'docx' : 'pdf';
                        $curriculumFile['file'] = sprintf( './uploads/%s.%s', sha1_file($_FILES['curriculum']['tmp_name']), $ext );
                        $curriculumFile['name'] = $curriculumFile['file'];

                        $curriculumFile['type'] = ( $_FILES[ 'curriculum' ][ 'type' ] === 'application/msword' ) ? 'application/msword' : 'application/pdf';
                        if ( !move_uploaded_file( $_FILES['curriculum']['tmp_name'], $curriculumFile['name'] ) )
                        {
                            throw new RuntimeException('Failed to move uploaded file.');
                        }
                        else
                        {
                            {
                                $parameters[ 'curriculum' ] = $_FILES[ 'curriculum' ];
                            }
                        }
                    }
                    else
                    {
                        header('Location: ' . SITE_URL . DIRECTORY_SEPARATOR . 'error-con-tipo-de-archivo' );
                    }
                }
                else
                {
                    header('Location: ' . SITE_URL . DIRECTORY_SEPARATOR . 'error-con-tamano-de-archivo' );
                }
                $template   = 'curriculum.tpl';
                $subject    = 'Hay un nuevo correo con curriculum desde Sipirily.com';
                $sender     = 'contacto@sipirily.com';
                $cc         =   [
                                    [ 'mail'  => 'jesus.garciav@me.com',
                                      'name'  => 'Jesús' ]
                                ];
                $sended     = SenderEmail::sendCurriculum( $parameters, $template, $subject, $sender, $cc, $curriculumFile );
                if ( $sended[ 'success' ] === 'true' )
                {
                    //unlink( $curriculumFile[ 'file' ] );
                    //header( 'Location: http://sipirily.app/'
                    die();
                }
                else
                {
                    header('Location: ' . SITE_URL . DIRECTORY_SEPARATOR . 'error-con-envio-de-formulario' );
                }
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