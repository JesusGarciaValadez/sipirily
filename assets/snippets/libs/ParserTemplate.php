<?php
//!defined( 'TEMPLATES_PATH' )  ? define(  'TEMPLATES_PATH' , LIBS_PATH.'templates'.DIRECTORY_SEPARATOR ) : '';
//!defined( 'CHUNKS_PATH' )  ? define(  'CHUNKS_PATH' , BASE_PATH.'..'.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'chunks'.DIRECTORY_SEPARATOR ): '';

class ParserTemplate {
    
    private function __construct() {}
    
    private static function parseData( $data, $template ) {
        
        if ( empty( $data ) ) {
            
            throw new Exception( 'No se ha especificado el conjunto de datos para el template.' );
        }
        if ( empty( $template ) ) {
            
            throw new Exception( 'El template especificado no es válido.' );
        }
        foreach ( $data  as $key => $value) {
            
            $template = str_replace( "[+$key+]" , $value , $template );
        }
        return $template ;
    }
    
    public static function parseTemplate( $type = '', $data = array() ) {
        
        $file = TEMPLATES_PATH . $type;
        
        if( file_exists($file) ){
            
            return self::parseData( $data , file_get_contents( $file ) );
            
        }else{
            
            throw new Exception( 'Lo sentimos, el template solicitado no existe.' );
            
        }
        
    }
    
    public static function parseChunk( $type = '' , $data = array() ){
        $file = CHUNKS_PATH . $type;
        
        if( file_exists($file) ){
            
            return self::parseData( $data , file_get_contents( $file ) );
            
        }else{
            
            throw new Exception( 'Lo sentimos, el template solicitado no existe.' );
            
        }
        
    }
    
    public static function parseTransferencia($key, $idUser) {
        Common::validSession();
        $tpl  = '';
        $data = LocalStore::recoverData($key . '-' . $idUser);
        foreach ($data as $transferencia) {
            $keys = array(
                'Transferencia'    => $transferencia['folio'],
                'ClaveSolicitante' => $transferencia['clave_destino'],
                'ClaveOtorgante'   => $transferencia['clave_origen'],
                'Fecha'            => $transferencia['fecha'],
                'Vin'              => $transferencia['vin'],
                'Status'           => $transferencia['status'],
            );
            
            $tpl .= self::parseTemplate('fila_transferencia.html', $keys);
        }
        $keys   = array('filas' => $tpl);
        $result = self::parseTemplate('excel_transferencia.html', $keys);
        return $result;
    }
    
    public static function parseInventario( $key , $idUser ) {
        
        Common::validSession();
        
       $data = LocalStore::recoverData( 'inventarios/' . $key . '-' . $idUser );
       
       $tpl = '';
       
       foreach( $data as $inventario ){
           $keys = array(
                'ClaveDistribuidor'  => $inventario['ClaveDistribuidor'],
                'NombreDistribuidor' => utf8_decode($inventario['NombreDistribuidor']),
                'PlantaArmadora'     => utf8_decode($inventario['PlantaArmadora']),
                'NoSerie'            => $inventario['NoSerie'],
                'Year'               => $inventario['Year'],
                'Modelo'             => utf8_decode($inventario['Modelo']),
                'FechaIngresoPP'     => $inventario['FechaIngresoPP'],
                'ValorFactura'       => $inventario['ValorFactura'],
                'SaldoPlanPiso'      => $inventario['SaldoPlanPiso'],
                'DiasPlanPiso'       => $inventario['DiasPlanPiso'],
                'ReduccionVencida'   => $inventario['ReduccionVencida'],
                'ReferenciaBancaria' => sprintf('%d', $inventario['ReferenciaBancaria']),
            );
            $tpl .= self::parseTemplate('fila_inventario.html', $keys);
       }
       $keys   = array( 'filas' => $tpl);
       $result = self::parseTemplate( 'excel_inventario.html' , $keys );
       return $result;
    }
}