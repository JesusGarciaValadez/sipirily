<?php

class SenderEmail {

    public function __construct( )
    {}

    public static function send ( $parameters = [], $template = '', $subject = '', $correo = '', $cc = [] )
    {
        //  Validación de los datos
        $rules  =   [   'sucursal'  => [ 'requerido' => 1,
                                         'validador' => 'esEmail',
                                         'mensaje'   => utf8_encode( 'La sucursal es obligatoria.' ) ],
                        'nombre'    => [ 'requerido' => 1,
                                         'validador' => 'esAlfaNumerico',
                                         'mensaje'   => utf8_encode( 'El nombre es obligatorio.' ) ],
                        'correo'    => [ 'requerido' => 1,
                                         'validador' => 'esEmail',
                                         'mensaje'   => utf8_encode( 'El correo es obligatorio.' ) ],
                        'subject'   => [ 'requerido' => 1,
                                         'validador' => 'esAlfaNumerico',
                                         'mensaje'   => utf8_encode( 'El asunto es obligatorio.' ) ],
                        'mensaje'   => [ 'requerido' => 0,
                                         'validador' => 'esAlfaNumerico',
                                         'mensaje'   => utf8_encode( 'El mensaje es obligatorio.' ) ]
                    ];

        $form = new Validator( $parameters, $rules );

        // Si el formulario no es válido
        if ( $form->validate() )
        {
            try
            {
                $emails = explode( ',', $correo );
                $to     = array();
                foreach ( $emails as $email )
                {
                    $emailRules     =   [ 'mail' => [ 'requerido' => 1,
                                                      'validador' => 'esEmail',
                                                      'mensaje'   => utf8_encode( 'El correo no es v&aacute;lido.' ) ]
                                        ];

                    $destinatario   = [ 'name' => $email, 'mail' => $email ];

                    $form           = new Validator( $destinatario, $emailRules );
                    if ( ( $form->validate() ) === false )
                    {
                        throw new Exception('El correo ' . $email . ' no es v&aacute;lido.');
                    }
                    $to[]           = $destinatario;
                }

                $vars =   [ 'sucursal'    => $parameters[ 'sucursal' ],
                            'nombre'      => $parameters[ 'nombre' ],
                            'correo'      => $parameters[ 'correo' ],
                            'subject'     => $parameters[ 'subject' ],
                            'mensaje'     => $parameters[ 'mensaje' ]
                          ];

                $tpl  = ParserTemplate::parseTemplate( $template, $vars );
                $_cc  = $cc;

                if ( Mailer::sendMail( $subject, $tpl, $to , '' , $_cc ) )
                {
                    $response       =   [ 'success' => 'true',
                                          'message' => utf8_encode( 'Muchas gracias por contestar esta encuesta.' )
                                        ];
                }
                else
                {
                    $response       =   [ 'success' => 'false',
                                          'message' => utf8_encode( 'El servicio de correo no esta disponible' )
                                        ];
                }
            }
            catch ( phpmailerException $e )
            {
                $response   = [ 'success' => 'false',
                                'msg' => utf8_encode( 'el servicio de correo no esta disponible' )
                              ];
            }
        }
        else
        {
            $response = false;
        }
        return $response;
    }
}
