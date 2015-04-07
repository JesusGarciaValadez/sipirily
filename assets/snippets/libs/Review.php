<?php
//require_once LIBS_PATH.'filter.input.php';
//require_once LIBS_PATH.'mailer.php';
//require_once LIBS_PATH.'ParserTemplate.php';

class Review extends Model {

    private $_PDOConn = null;

    public function __construct( $conn, $db_table )
    {
        $this->_tableName = $db_table;
        $this->_primaryKey = 'id';
        $this->setMysqlConn( $conn );
        $this->_PDOConn = $conn;
    }

    public function insertInit ( $info, $template, $subject, $correo, $cc = array() )
    {

        //  Validación de los datos
        $parameters = array(
            'name' => array(
                'requerido' => 1,
                'validador' => 'esAlfaNumerico',
                'mensaje' => utf8_encode( 'La primera pregunta es obligatoria.' ) ),
            'email' => array(
                'requerido' => 1,
                'validador' => 'esEmail',
                'mensaje' => utf8_encode( 'La segunda pregunta es obligatoria.' ) ),
            'comments' => array(
                'requerido' => 0,
                'validador' => 'esAlfaNumerico',
                'mensaje' => utf8_encode( 'La tercera pregunta es obligatoria.' ) ),
        );

        $form = new Validator( $info, $parameters );

        // Si el formulario no es válido
        if ( !$form->validate() ) {

            return false;
        } else {

            try {

                $this->_PDOConn->beginTransaction();

                $info[ 'date_answer' ] = date( "Y-m-d H:i:s" );

                $success    = $this->insert( $info );

                if ( $success ) {

                    $emails = explode( ',' , $correo );
                    $to     = array();

                    foreach ( $emails as $email ) {

                        $params = array(
                            'mail' => array(
                                'requerido' => 1 ,'validador' => 'esEmail', 'mensaje' => utf8_encode( 'El correo no es v&aacute;lido.' ) )
                        );

                        $destinatario = array(
                            'name' => $email,
                            'mail' => $email
                        );

                        $form   = new Validator( $destinatario, $params );
                        if ( ( $form->validate() ) === false ) {

                            throw new Exception('El correo ' . $email . ' no es v&aacute;lido.');
                        }
                        $to[] = $destinatario;
                    }

                    $vars   = array(
                            'name'      => $info[ 'name' ],
                            'email'     => $info[ 'email' ],
                            'comments'  => $info[ 'comments' ]
                    );

                    $tpl    = ParserTemplate::parseTemplate( $template, $vars );

                    $_cc    = $cc;

                    if ( Mailer::sendMail( $subject, $tpl, $to , '' , $_cc ) ) {

                        $response       = array (
                            'success' => 'true',
                            'message' => utf8_encode( 'Muchas gracias por contestar esta encuesta.' )
                        );
                    } else {

                        $response = array (
                            'success'=>'false',
                            'message'=>utf8_encode( 'El servicio de correo no esta disponible' )
                        );
                    }
                } else {

                    $response = array(
                        'success' => 'false',
                        'message' => utf8_encode('No fue posible guardar la información.')
                    );
                }

                $this->_PDOConn->commit();

            } catch ( PDOException $e ) {

                $this->_PDOConn->rollBack();
                $response   = array ( 'success'=>'false', 'msg'=>'el servicio de DB no esta disponible' );
            } catch ( phpmailerException $e ) {

                $this->_PDOConn->rollBack();
                $response   = array ( 'success'=>'false', 'msg'=>'el servicio de correo no esta disponible' );
            }
        }
        return $response;
    }

    public function shareImage ( $info, $template, $subject, $correo, $cc = array() )
    {
        //  Validación de los datos
        $parameters = array(
            'email' => array(
                             'requerido' => 1,
                             'validador' => 'esEmail',
                             'mensaje' => utf8_encode( 'La segunda pregunta es obligatoria.' )
                            )
        );

        $form = new Validator( $info, $parameters );

        // Si el formulario no es válido
        if ( !$form->validate() )
        {
            return array (
                          'success'=>'false',
                          'message'=>utf8_encode( 'Un campo no es válido.' )
                          );
        }
        else
        {
            try
            {
                    $emails = explode( ',' , $correo );
                    $to     = array();

                    foreach ( $emails as $email )
                    {

                        $params = array(
                            'mail' => array(
                                            'requerido' => 1 ,
                                            'validador' => 'esEmail',
                                            'mensaje' => utf8_encode( 'El correo no es v&aacute;lido.' )
                                            )
                        );

                        $destinatario = array(
                            'name' => $email,
                            'mail' => $email
                        );

                        $form   = new Validator( $destinatario, $params );
                        if ( ( $form->validate() ) === false )
                        {
                            throw new Exception('El correo ' . $email . ' no es v&aacute;lido.');
                        }
                        $to[] = $destinatario;
                    }

                    $vars   = array(
                            'email' => $info[ 'email' ],
                            'image' => $info[ 'image' ]
                    );

                    $tpl    = ParserTemplate::parseTemplate( $template, $vars );

                    $_cc    = $cc;

                    if ( Mailer::sendMail( $subject, $tpl, $to , '' , $_cc ) )
                    {
                        $response       = array (
                            'success' => 'true',
                            'message' => utf8_encode( 'Muchas gracias por contestar esta encuesta.' )
                        );
                    }
                    else
                    {
                        $response = array (
                            'success'=>'false',
                            'message'=>utf8_encode( 'El servicio de correo no esta disponible' )
                        );
                    }
            }
            catch ( phpmailerException $e )
            {
                $this->_PDOConn->rollBack();
                $response   = array ( 'success'=>'false', 'msg'=>'el servicio de correo no esta disponible' );
            }
        }
        return $response;
    }
}
