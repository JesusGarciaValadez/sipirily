<?php

class FilterInput {

    private static function validateAlphabetic(  $value  )
    {
        return preg_match( '/^[a-zA-Z áéíóúAÉÍÓÚÑñÜü\']+$/i', $value ) ? trim( $value ) : ''  ;
    }

    private static function validateAlphanumeric(  $value  )
    {
        return preg_match('/^[a-zA-Z0-9 áéíóúñÑÁÉÍÓÚÜü,\.\'¿¡!\?:()-_=]+$/i', $value) ? trim( $value ) : ''  ;
    }

    private static function validateString( $value )
    {
        return is_string( $value ) ? strip_tags( trim ( $value ) ) : '' ;
    }

    private static function validateInt( $value )
    {
        return intval( $value );
    }

    private static function validateDouble( $value )
    {
        return doubleval( $value ) ;
    }

    private static function validateDate( $value )
    {
        $value = trim( date( 'Y-m-d', strtotime( $value )  ) );
        return !empty( $value ) ? $value : '' ;
    }

    private static function validateBool( $value )
    {
         return (int)$value;
    }

    private static function validateVin( $value ){
        return preg_match("/^[a-zA-Z0-9]{17}$/", $value) ? $value : '' ;
    }

    private static function validateEmail( $value )
    {
         $value = strip_tags( trim( $value ) ) ;
         return preg_match( "/^[_\.0-9a-zA-Z-]+@([0-9a-zA-Z][0-9a-zA-Z-]+\.)+[a-zA-Z]{2,6}$/" , $value ) ? $value : '' ;
    }

    private static function validatePhone( $value )
    {
        return ( preg_match( "/^[0-9]{10}$/" , $value ) || preg_match( "/^[0-9]{7}$/" , $value ) ) ?
               (string) strip_tags( trim($value) ) : '' ;
    }


    private static function validateLada( $value )
    {
        return ( preg_match( "/^[0-9]{3}$/" , $value ) || preg_match( "/^[0-9]{2}$/" , $value ) ) ?
               (string) strip_tags( trim($value) ) : '' ;
    }

    private static function validateCellPhone( $value )
    {
        return preg_match( "/^[0-9]{10}$/" , $value ) ?  (string) strip_tags( trim($value) ) : '' ;
    }

    private static function validatePassword( $value )
    {
        $value = strip_tags( trim( $value ) ) ;
        return strlen( $value ) >= 6  ? md5( $value ) : '' ;
    }

    private static function validateClavePlanFia( $value )
    {
        return ( preg_match( "/^[0-9A-Z]{7}$/" , $value ) ) ?
               (string) strip_tags( trim($value) ) : '' ;
    }


    public static function FilterValue( $value , $type , $required = false )
    {
            switch ( $type ) {
                        case 'alphabetic'   :   $value = self::validateAlphabetic( $value );
                                                        break;

                        case 'alphanumeric' :   $value = self::validateAlphanumeric( $value );
                                                            break;

                        case 'string'   :  $value = self::validateString( $value );
                                                break;

                        case 'int'      :  $value = self::validateInt( $value );
                                                break;

                        case 'double'   :  $value = self::validateDouble( $value );
                                                  break;

                        case 'date'     :  $value = self::validateDate( $value );
                                                 break;

                        case 'bool'     :  $value = self::validateBool( $value );
                                                break;

                        case 'email'    :  $value = self::validateEmail( $value );
                                                 break;

                        case 'lada'     :  $value = self::validateLada( $value );
                                                 break;

                        case 'telefono' :     $value = self::validatePhone( $value );
                                                    break;

                        case 'celular'  :       $value = self::validateCellPhone( $value );
                                                     break;

                        case 'password' :   $value = self::validatePassword( $value );
                                                     break;

                        case 'vin'        : $value = self::validateVin( $value );
                                                break;

                        case 'clavePlanFia' :   $value = self::validateClavePlanFia( $value );
                                                     break;

                        default :   throw new Exception( 'El tipo de dato especificado no existe, favor de verificarlo.' );
                                       break;

                }

                return ( $required && empty( $value ) ) ? false : $value ;

    }

}

?>
