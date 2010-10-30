<?php
include_once("root.php");
include_once(ROOT."config.php");
include_once(ROOT."classes/lib/Debug.php");

/**
 * Data access layer, which is nothing but a wrapper for data access functions.
 *
 */
class DAO{
  private $query;
  private $result;
  	
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
	echo mysql_error();
	return !empty($conx)&&!empty($dbresult);
  } 
	
  public function query($query){
  	$this->connect();
    if(is_resource($this->result))mysql_free_result($this->result);
    $this->query=$query;
    $this->result=@mysql_query($query)or Debug::write("[".gmstrftime("%Y-%d-%m %H:%M:%S",time())."] Error: ".mysql_error()."\n$query");
    return $this->result;
  }
  
  public function getAll(){
    return @mysql_fetch_array($this->result);
  }
  
  public function get($i, $field){
    return @mysql_result($this->result,$i,$field);
  }
  
  public function rowCount(){
    return @mysql_num_rows($this->result);
  }
  
  public function getRow($i){
    return @mysql_fetch_row($i);
  }
  
  public function seek($r){
  	@mysql_data_seek($this->result,$r);
  }
  
}
?>
