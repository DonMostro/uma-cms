<?php
include_once("root.php");
include_once(ROOT."classes/lib/DAO.php");
include_once(ROOT."classes/lib/Debug.php");
include_once(ROOT."classes/lib/RecordSet.php");
include_once(ROOT."classes/lib/Types.php");
include_once(ROOT."classes/models/MModel.php");
include_once(ROOT."classes/models/MVideos.php");


/**
 * Modelo de tipos de videos y scripts de conversi&oacute;n
 * @author rodrigo
 *
 */

class MTypes extends MModel{
	/**
	 * Constructor
	 */
	
  function __construct(){
    parent::__construct(new RecordSet());
    
    $this->table=TABLE_PREFIX.'types';
    
    $this->columns=array(
    	'title'=>null,
  		'thumb'=>null,
  		'script'=>null
    );
   
  }

  /**
   * (non-PHPdoc)
   * @see www/classes/models/MModel#setQuery()
   */
  
  protected function setQuery(){
  	
    $query="
    SELECT id, title, thumb, script 
	   FROM $this->table 
	".$this->_where();

    $this->dataSet->setQuery($query);
  }
  /**
   * (non-PHPdoc)
   * @see www/classes/models/MModel#setCountQuery()
   */
  
  
  protected function setCountQuery(){
  	$query="SELECT COUNT(*) FROM $this->table ".$this->_where();

  	$this->dataSet->setCountQuery($query);
  }
  
  /**
   * (non-PHPdoc)
   * @see www/classes/models/MModel#add()
   */
  
  function add(){
    
    $this->setState('change_immediate');
	$this->notifyObservers();
	
	return parent::add();
	
  }
  
  /**
   * (non-PHPdoc)
   * @see www/classes/models/MModel#update()
   */
  
  function update(){
	  	
  	$this->setState('change_immediate');
	$this->notifyObservers();
  	
  	return parent::update();

  }
  
  function delete(){
  	
  	$this->setState('change_immediate');
	$this->notifyObservers();
	
	return parent::delete();

  }
  
}
?>