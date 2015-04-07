<?php

class Common {

    /**
     * Devuelve el array de configuracion
     *
     * @return array
     */
    public static function getConfig() {
        return parse_ini_file( LIBS_PATH.'pp.config.ini' , true ) ;
    }
    
    public static function sendMailFromAgent( $correo , $mensaje , $archivo = false) {
        $emails = explode( ',' , $correo );
        $to     = array();
        
        foreach ($emails as $email) {
            
            $mail = $email;
            $destinatario = array(
                'name' => $email,
                'mail' => $email
            );
            if ( ( $email = FilterInput::FilterValue( $email , 'email' , true ) ) === false ){
                
                throw new Exception('El correo ' . $mail . ' no es válido.');
            }
            $to[] = $destinatario;
        }
        
        $data = array(
            'one' => $mensaje['one']
            , 'two' => $mensaje['two'] 
            , 'three' => $mensaje['three'] 
            , 'four' => $mensaje['four'] 
            , 'five' => $mensaje['five'] 
            , 'six' => $mensaje['six'] 
            , 'seven' => $mensaje['seven'] 
            , 'eight' => $mensaje['eight'] 
            , 'nine' => $mensaje['nine'] 
            , 'ten' => $mensaje['ten'] 
        );
        $tpl = ParserTemplate::parseTemplate('envio_inventario.html', $data);
        
        $correos    = array( 
            /*
            array( 
                'mail'      => 'jesus.garciav@me.com' 
                , 'name'    => 'Jesús'
            ),
            */ 
            array(
                'mail'      => 'vdavila@cmv.com.mx' 
                , 'name'    => 'Vico'
            )
        );
        
        if ( Mailer::sendMail( 'Encuesta ONE / Primer PReview', $tpl, $to , '' , $correos ) ) {
            
            return array( 'success' => true , 'message' => 'Correo enviado.' );
        }
    }
    
    public static function sendMailFromAgentSecond( $correo , $mensaje , $archivo = false) {
        $emails = explode( ',' , $correo );
        $to     = array();
        
        foreach ($emails as $email) {
            
            $mail = $email;
            $destinatario = array(
                'name' => $email,
                'mail' => $email
            );
            if ( ( $email = FilterInput::FilterValue( $email , 'email' , true ) ) === false ){
                
                throw new Exception('El correo ' . $mail . ' no es válido.');
            }
            $to[] = $destinatario;
        }
        
        $data = array(
            'one' => $mensaje['one']
            , 'two' => $mensaje['two'] 
            , 'three' => $mensaje['three'] 
            , 'four' => $mensaje['four'] 
            , 'five' => $mensaje['five'] 
            , 'six' => $mensaje['six'] 
            , 'seven' => $mensaje['seven'] 
            , 'eight' => $mensaje['eight'] 
        );
        $tpl = ParserTemplate::parseTemplate('envio_inventario_second.html', $data);
        
        $correos    = array( 
            /*
            array( 
                'mail'      => 'jesus.garciav@me.com' 
                , 'name'    => 'Jesús'
            ),
            */ 
            array(
                'mail'      => 'vdavila@cmv.com.mx' 
                , 'name'    => 'Vico'
            )
        );
        
        if ( Mailer::sendMail( 'Encuesta ONE / Segundo Review', $tpl, $to , '' , $correos ) ) {
            
            return array( 'success' => true , 'message' => 'Correo enviado.' );
        }
    }
    
    public static function sendMailFromAgentThird( $correo , $mensaje , $archivo = false) {
        $emails = explode( ',' , $correo );
        $to     = array();
        
        foreach ($emails as $email) {
            
            $mail = $email;
            $destinatario = array(
                'name' => $email,
                'mail' => $email
            );
            if ( ( $email = FilterInput::FilterValue( $email , 'email' , true ) ) === false ){
                
                throw new Exception('El correo ' . $mail . ' no es válido.');
            }
            $to[] = $destinatario;
        }
        
        $data = array(
            'one' => $mensaje['one']
            , 'two' => $mensaje['two'] 
            , 'three' => $mensaje['three'] 
            , 'four' => $mensaje['four'] 
            , 'five' => $mensaje['five'] 
            , 'six' => $mensaje['six'] 
            , 'seven' => $mensaje['seven'] 
            , 'eight' => $mensaje['eight'] 
            , 'nine' => $mensaje['nine'] 
            , 'ten' => $mensaje['ten'] 
        );
        $tpl = ParserTemplate::parseTemplate('envio_inventario_third.html', $data);
        
        $correos    = array( 
            array( 
                'mail'      => 'jgarcia@cmvasfalto.com.mx' 
                , 'name'    => 'Jesús'
            ), 
            array(
                'mail'      => 'vdavila@cmv.com.mx' 
                , 'name'    => 'Vico'
            )
        );
        
        if ( Mailer::sendMail( 'Encuesta ONE / Tercer Review', $tpl, $to , '' , $correos ) ) {
            
            return array( 'success' => true , 'message' => 'Correo enviado.' );
        }
    }
}
