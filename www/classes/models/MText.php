<?php
include_once("root.php");
include_once(ROOT."classes/lib/DAO.php");
include_once(ROOT."classes/lib/Debug.php");
include_once(ROOT."classes/lib/RecordSet.php");
include_once(ROOT."classes/lib/Types.php");
include_once(ROOT."classes/models/MModel.php");

/**
 * Language text
 *
 */
class MText extends MModel{
  
  private $search;
	
  function __construct(){
    parent::__construct(new RecordSet());
    $this->addOrder(new DataOrder("code","ASC"));
    
    $this->table='ltext';
    
    $this->columns=array(
    	'string'=>null,
	  	'code'=>null,
	  	'lang_id'=>null,
	  	'lang_code'=>null
    );

  }
  
  public function setSearch($value) { $this->search=mysql_real_escape_string(utf8_decode($value)); }
  
  protected function setQuery(){

    $query="
    SELECT ltext.* 
       FROM ltext 
    JOIN lang 
      ON ltext.lang_id=lang.id 
       ".$this->_where();

    $this->dataSet->setQuery($query);
  }
  
  protected function setCountQuery(){
  	$query="SELECT COUNT(*) FROM ltext ".$this->_where();

  	$this->dataSet->setCountQuery($query);
  }
  
  protected function _where(){
  	$where="WHERE 1";
  	
  	$ids=$this->idToString("ltext.id");
	if($ids!="")$where.=" AND $ids";
  	
  	if($this->columns['lang_id']!=null)$where.=" AND lang_id=".(int)$this->columns['lang_id'];
  	if($this->columns['lang_code']!=null)$where.=" AND lang.code='".$this->columns['lang_code']."'";
  	if($this->columns['code']!=null)$where.=" AND ltext.code='".$this->columns['code']."'";
  	if($this->search!=null)$where.=" AND (ltext.code LIKE '%$this->search%' OR string LIKE '%$this->search%')";
    return $where;
  }
  
  public function add(){
    
    $this->setState('change_immediate');
	$this->notifyObservers();
	
	return parent::add();
  }
  
  public function update(){
	  	
  	$this->setState('change_immediate');
	$this->notifyObservers();
	
  	return parent::update();
  }
  
  public function delete(){
	  	
  	$this->setState('change_immediate');
	$this->notifyObservers();
	
	return parent::delete();
  	
  }
  
}
?>