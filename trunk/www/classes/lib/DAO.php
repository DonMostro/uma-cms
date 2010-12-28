<?php
include_once("root.php");
include_once(ROOT."config.php");
include_once(ROOT."classes/lib/Debug.php");

/**
 * Capa de acceso a datos, funcionalidad nativa para acceder a base de datos.
 *
 */
class DAO{
  private $query;
  private $result;
  /**
   * Conexion a base de datos
   * @param $server
   * @param $user
   * @param $pwd
   * @param $db
   * @return boolean
   */
  	
  public function connect($server='',$user='',$pwd='',$db='') {
	static $conx = null;
	if(!is_resource($conx)){
		$conx=@mysql_connect(DB_SERVER,DB_USER,DB_PASSWORD)or Debug::write("[".gmstrftime("%Y-%d-%m %H:%M:%S",time())."] Cannot connect: ".mysql_error());
		$dbresult=@mysql_select_db(DB_DATABASE,$conx)or Debug::write("[".gmstrftime("%Y-%d-%m %H:%M:%S",time())."] Cannot select database: ".mysql_error());
	}elseif ($server&&$user&&$pwd&&$db){
		mysql_close($conx);
		$conx=@mysql_connect($server,$user,$pwd)or Debug::write("[".gmstrftime("%Y-%d-%m %H:%M:%S",time())."] Cannot connect: ".mysql_error());
		$dbresult=@mysql_select_db($db,$conx)or Debug::write("[".gmstrftime("%Y-%d-%m %H:%M:%S",time())."] Cannot select database: ".mysql_error());
	}
	return !empty($conx)&&!empty($dbresult);
  }

  /**
   * Consultas a base de datos
   * @param $query
   * @return mysql_query
   */
	
  public function query($query){
  	$this->connect();
    if(is_resource($this->result))mysql_free_result($this->result);
    $this->query=$query;
    //Debug::write($query);
    $this->result=@mysql_query($query)or Debug::write("[".gmstrftime("%Y-%d-%m %H:%M:%S",time())."] Error: ".mysql_error()."\n$query");
    return $this->result;
  }
  
  /**
   * Devuelve array RecordSet
   * @return mysql_fetch_array
   */
  
  public function getAll(){
    return @mysql_fetch_array($this->result);
  }
  
  /**
   * Obtiene valor de campo a trav&eacute;s de su nombre y posici&oacute;n de RecordSet
   * @param $i
   * @param $field
   * @return mysql_result
   */
  public function get($i, $field){
    return @mysql_result($this->result,$i,$field);
  }
  
  /**
   * Cuenta el n&uacute;mero de filas
   * @return int
   */
  
  public function rowCount(){
    return @mysql_num_rows($this->result);
  }
  
  /**
   * Devuelve una matriz num&eacute;rica que corresponde a una fila.
   * @param $i
   * @return mysql_fetch_row
   */
  
  public function getRow($i){
    return @mysql_fetch_row($i);
  }
  
  /**
   * Mueve el puntero de MySQL para que apunte al n&uacute;mero de fila especificada
   * @param $r
   * @return mysql_data_seek
   */
  
  
  public function seek($r){
  	@mysql_data_seek($this->result,$r);
  }
  
}
?>
