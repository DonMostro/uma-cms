<?php
include_once("root.php");
/**
 * Clase para depuraci&oacute;n de errores, almacena los mensajes del sistema,

 *
 */
class Debug{
  /**
   * Escribe el reporte de error en un archivo de texto plano llamado debug.txt
   * @param $message
   * @return unknown_type
   */	
  function write($message=null){
    $trace=debug_backtrace();
    if($message!==null){
	    if(!empty($trace[0])&&!empty($trace[0]['file'])&&!empty($trace[0]['line'])){
	    	$message=$trace[0]['file'].'['.$trace[0]['line'].']['.strftime('%Y-%m-%d %H:%M:%S').']: '.print_r($message,1);
	    }else{
	    	$message=print_r($message,1);
	    }
    }else{
    	$message=print_r($trace,1);
    }
    $ff=fopen(ROOT."debug.txt","a");
    fwrite($ff,"$message\r\n");
    fclose($ff);
  }
}
?>
