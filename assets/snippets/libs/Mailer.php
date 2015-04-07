<?php
//require_once LIBS_PATH.'Common.php';
require_once LIBS_PATH.'phpmailer/class.phpmailer.php';
//require_once LIBS_PATH.'filter.input.php';

class Mailer {

    private $_mailer ;
    private static $instance ;

    private function __construct( ) {
        $config = Common::getConfig();
        $port = intval( $config['mail_service']['port'] );

        $this->_mailer = new PHPMailer( true );
        $this->_mailer->isHTML( true );
        $this->_mailer->IsSMTP( );
        $this->_mailer->Port        = $port;
        $this->_mailer->Host        = $config['mail_service']['smtp_host'] ;
        $this->_mailer->SMTPSecure  = $config['mail_service']['smtp_secure'] ;
        $this->_mailer->SMTPAuth    = true;
        $this->_mailer->CharSet     = $config['mail_service']['char_set'] ;
        $this->_mailer->Username    = $config['mail_service']['smtp_user'] ;
        $this->_mailer->Password    = $config['mail_service']['smtp_password'] ;
            //De:
        $this->_mailer->From        = $config['mail_service']['sender_mail']  ;
        $this->_mailer->FromName    = $config['mail_service']['sender_name'] ;
        $this->_mailer->ClearAddresses();
    }

    public function addSubject ( $subject = '' ) {

        if(  ( $subject = FilterInput::FilterValue( $subject , 'string' , true ) ) === false )
            self::throwMailerException( 'Es necesario que agregues el asunto del correo.' );

        $this->_mailer->Subject = $subject  ;
    }

    public function addBody ( $body = '' ) {

        if( empty( $body ) )
            self::throwMailerException( 'Es necesario que agregues el cuerpo del correo.' );

        $this->_mailer->Body = $body;

    }

    public function addTo( $to = array() ) {

        if ( empty( $to ) ) {

            self::throwMailerException( 'TO: Es necesario que agregues cuando menos un destinatario' );
        }

        foreach ( $to as $dest ) {

            if ( empty( $dest['mail'] ) || empty( $dest['name'] ) ) {

                self::throwMailerException( 'TO: La lista de destinatarios no está en el formato correcto. ' );
            }
            if ( ( $mail = FilterInput::FilterValue( $dest['mail'] , 'email' , true ) ) === false ) {

                self::throwMailerException( 'TO: El correo proporcionado no es válido.' );
            }

            if ( ( $nombre = FilterInput::FilterValue( $dest['name'] , 'string' , true ) ) === false ) {

                self::throwMailerException( 'TO: El nombre del destinatario no es correcto.' );
            }
            $this->_mailer->AddAddress( $mail , $nombre );
        }
    }

    public function addCopy( $cc = array() ) {

        if( empty( $cc ) )
            self::throwMailerException( 'CC: Es necesario que agregues cuando menos un destinatario.' );

        foreach ( $cc as $dest ){

            if( empty( $dest['mail'] ) || empty( $dest['name'] ) )
                self::throwMailerException( 'CC: La lista de destinatarios no está en el formato correcto.' );

            if( ( $mail = FilterInput::FilterValue( $dest['mail'] , 'email' , true ) ) === false )
                self::throwMailerException( 'CC: El correo proporcionado no es válido.' );

            if( ( $nombre = FilterInput::FilterValue( $dest['name'] , 'string' , true ) ) === false )
                self::throwMailerException( 'CC: El nombre del destinatario no es correcto.' );

            $this->_mailer->AddCC( $mail , $nombre );
        }
    }

    public function addBackCopy( $bcc = array() ) {
        if( empty( $bcc ) )
            self::throwMailerException( 'BCC: Es necesario que agregues cuando menos un destinatario.' );

        foreach ( $bcc as $dest ){

            if( empty( $dest['mail'] ) || empty( $dest['name'] ) )
                self::throwMailerException( 'BCC: La lista de destinatarios no está en el formato correcto.' );

            if( ( $mail = FilterInput::FilterValue( $dest['mail'] , 'email' , true ) ) === false )
                self::throwMailerException( 'BCC: El correo proporcionado no es válido.' );

            if( ( $nombre = FilterInput::FilterValue( $dest['name'] , 'string' , true ) ) === false )
                self::throwMailerException( 'BCC: El nombre del destinatario no es correcto.' );

            $this->_mailer->AddBCC( $mail , $nombre );
        }

    }

    public function addAttach( $atach = array() ) {

        if( empty( $atach ) )
            self::throwMailerException( 'Attach: Es necesario que agregues cuando menos un archivo.' );

        foreach ( $atach as $file ){

            if( empty( $file['file'] ) || empty( $file['name'] ) )
                self::throwMailerException( 'Attach: La lista de archivos no está en el formato correcto.' );

            $att = $file['file'];

            if( ( $nombre = FilterInput::FilterValue( $file['name'] , 'string' , true ) ) === false )
                self::throwMailerException( 'Attach: El nombre del destinatario no es correcto.' );

            $this->_mailer->AddAttachment( $att , $nombre );
        }
    }

    public function send() {

        if ( $this->_mailer->Send() ) {

            return true ;
        } else {

            self::throwMailerException( 'Lo sentimos, no pudo enviarse el correo electrónico.' );
            return false;
        }
    }

    private static function throwMailerException ( $message ) {

        throw new Exception( $message , 5810  );
    }

    public static function sendMail( $subject = '', $body = '', $to = array(), $cc = array(), $bcc = array(), $att = array() ) {

        try {

            self::$instance = new Mailer();
            self::$instance->addSubject( $subject );
            self::$instance->addBody( $body );
            self::$instance->addTo( $to );
            if( !empty( $cc ) )
                self::$instance->addCopy( $cc );

            if( !empty( $bcc ) )
                self::$instance->addBackCopy( $bcc );

            if( !empty( $att ) )
                self::$instance->addAttach( $att );

            return self::$instance->send() ;
        } catch ( phpmailerException $e ) {

            self::throwMailerException( $e->getMessage() );
            return false;
        }

    }
}