<?php
include_once("root.php");
include_once(ROOT."classes/lib/DAO.php");
include_once(ROOT."classes/lib/Debug.php");
include_once(ROOT."classes/lib/RecordSet.php");
include_once(ROOT."classes/lib/Types.php");
include_once(ROOT."classes/models/MModel.php");


class MWatched extends MModel{
  
  function __construct(){
    parent::__construct(new RecordSet());
    
    $this->table='watched';
    
    $this->columns=array(
    	'videos_id'=>null
    );
    
  }
  
  protected function setQuery(){
	$query="
	SELECT watched.id
		 , videos.*
		 , videos.id AS videos_id
		 , thumbs.filename AS thumb
	  FROM watched
	  JOIN videos
	    ON watched.videos_id=videos.id
 LEFT JOIN thumbs
        ON thumbs.videos_id=videos.id
	  ".$this->_where()."
  GROUP BY watched.id";

    $this->dataSet->setQuery($query);
  }
  
  protected function setCountQuery(){
  	$query="
  	SELECT COUNT(*) 
  	FROM watched 
  	JOIN videos
	    ON watched.videos_id=videos.id
  	".$this->_where()."
  GROUP BY watched.id";

  	$this->dataSet->setCountQuery($query);
  }
  
  public function add(){
  	
  	$this->delete();
  	
    $this->setState('change_delayed');
	$this->notifyObservers();
	
	return parent::add();
  }
  
  public function update(){
	  	
	$this->setState('change_delayed');
	$this->notifyObservers();
		
	return parent::update();
  }
  
  public function delete(){
	  	
	$this->setState('change_delayed');
	$this->notifyObservers();
  	
	return parent::delete();
  }
  
}
?>