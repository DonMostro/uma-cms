<?php
include_once("root.php");
include_once(ROOT."classes/lib/DAO.php");
include_once(ROOT."classes/lib/Debug.php");
include_once(ROOT."classes/lib/RecordSet.php");
include_once(ROOT."classes/lib/Types.php");
include_once(ROOT."classes/models/MModel.php");
include_once(ROOT."classes/models/MVideos.php");


class MVideoTypes extends MModel{
	
  function __construct(){
    parent::__construct(new RecordSet());
    
    $this->table=TABLE_PREFIX.'video_types';
    
    $this->columns=array(
    	'videos_id'=>null,
  		'types_id'=>null,
  		'filename'=>null
    );
   
  }
  
  public function setVideosId($value){
	$this->columns['videos_id']=(int)$value; 	
  }

  public function setTypesId($value){
	$this->columns['types_id']=(int)$value;   	
  }
  
  public function setFileName($value){
  	$this->columns['filename']=mysql_escape_string($value);
  }
  
  protected function setQuery(){
  	
    //Get lista de categories
    $query="
    SELECT videos_id, types_id, filename 
	   FROM $this->table 
	".$this->_where();

    $this->dataSet->setQuery($query);
  }
  
  protected function setCountQuery(){
  	$query="SELECT COUNT(*) FROM $this->table ".$this->_where();

  	$this->dataSet->setCountQuery($query);
  }
  
  function add(){
    
    $this->setState('change_immediate');
	$this->notifyObservers();
	
	return parent::add();
	
  }
  
  /**
   * (non-PHPdoc)
   * Acá se rompen un poco las reglas para acomodarnos a una doble clave primaria,
   * se escribe la query directamente acá y no se usa el constructor de queries de la superclase
   * @see www/classes/models/MModel#update()
   */
  
  function update(){
	$dao=new DAO();	
  	$this->setState('change_immediate');
	$this->notifyObservers();
  	
  	//return parent::update();
  	return $dao->query("UPDATE $this->table SET filename='{$this->columns['filename']}' WHERE videos_id={$this->columns['videos_id']} AND types_id='{$this->columns['types_id']}'");

  }
  
  function delete(){
  	
  	$this->setState('change_immediate');
	$this->notifyObservers();
	
	return parent::delete();

  }
  
}
?>