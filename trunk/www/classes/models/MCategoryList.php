<?php
include_once("root.php");
include_once(ROOT."classes/lib/DAO.php");
include_once(ROOT."classes/lib/Debug.php");
include_once(ROOT."classes/lib/RecordSet.php");
include_once(ROOT."classes/lib/Types.php");
include_once(ROOT."classes/models/MModel.php");
include_once(ROOT."classes/models/MBufferedModel.php");
include_once(ROOT."classes/models/MVideos.php");


class MCategoryList extends MBufferedModel {
	
  private $tmpbuff;
	
  function __construct(){
    parent::__construct(new RecordSet());
    $this->addOrder(new DataOrder("orden","ASC"));
    
    $this->table=TABLE_PREFIX.'categories';
    $this->table_videos=TABLE_PREFIX.'videos';
    
    $this->columns=array(
    	'approved'=>null,
  		'title'=>null,
  		'thumb'=>null,
    	'parent_id'=>null,
    	'children'=>null,
    	'orden'=>1
    );
    
  }

  protected function setQuery(){
  	
    //Get list of categories
    $query="
    SELECT categories.*
         , COUNT( DISTINCT $this->table_videos.id ) AS videos
	  FROM categories
 	LEFT JOIN $this->table_videos
        ON $this->table.id = $this->table_videos.categories_id
	 ".$this->_where()."
	 GROUP BY $this->table.id";
	echo $query;
    
    $this->dataSet->setQuery($query);
  }
  
  protected function setCountQuery(){
  	$query="SELECT COUNT(*) FROM $this->table ".$this->_where();
  	$this->dataSet->setCountQuery($query);
  }
  
  protected function _where(){
  	$where="WHERE 1";
  	$ids=$this->idToString("$this->table.id");
	if($ids!="")$where.=" AND $ids";
	if($_SERVER['PHP_SELF'] == '/index.php') $where.= " AND $this->table.title <> 'Prueba' ";	
	if($this->columns['approved']!==null)$where.=" AND $this->table.approved='".$this->columns['approved']."'";
  	
  	return $where;
  }
  
  public function getLevels(){
  	$this->tmpbuff=array();
  	$parent_id=$this->columns['parent_id']!==null?$this->columns['parent_id']:0;
  	$this->iterate($parent_id);
  	$this->buffer=$this->tmpbuff;
  	$this->reset();
  }
  
  private function iterate($id=0, $level=0){
    foreach ($this->buffer as $cat){
  		if($cat['parent_id']==$id){
  			$children=explode(',',$cat['children']);
  			foreach($children as $child){
  				if(!empty($this->buffer[$child])){
  					$cat['videos']+=$this->buffer[$child]['videos'];
  				}
  			}
  			$cat['level']=$level;
  			if($this->columns['parent_id']===null || $cat['parent_id']==$this->columns['parent_id'])$this->tmpbuff[]=$cat;
  			$this->iterate($cat['id'],$level+1);
  		}
  	}
  }
  
  private function addChildren($parent_id){
  	$dao=new DAO();
  	$cat=new MCategoryList();
  	$cat->setId($parent_id);
  	$cat->load();
  	$data=$cat->next();
  	if(!empty($data)){
  		$dao->query("UPDATE $this->table SET children='".mysql_real_escape_string($data['children'].','.$this->id)."' WHERE id=$parent_id");
  	}
  	if($data['parent_id']!=0){
  		$this->addChildren($data['parent_id']);
  	}
  }
  
  private function updateChildrens($parent_id){
  	$dao=new DAO();
  	$cat=new MCategoryList();
  	$cat->setId($parent_id);
  	$cat->load();
  	$data=$cat->next();
  	if(!empty($data)){
  		$dao->query("SELECT GROUP_CONCAT(id) AS id FROM $this->table WHERE parent_id=$parent_id");
  		$childrens=",".$dao->get(0, "id");
  		$dao->query("UPDATE $this->table SET children='$childrens' WHERE id=$parent_id");
  		//echo "UPDATE $this->table SET children=(SELECT GROUP_CONCAT(id) FROM $this->table WHERE parent_id=$parent_id) WHERE id=$parent_id";
  	}
  }
  
  
  
  
  public function add(){
  	
  	
  	$this->setState('change_immediate');
	$this->notifyObservers();
	
  	$this->id=parent::add();
  	
  	$this->updateChildrens($this->getParent_id());
  	
  	return $this->id;

  }
  
  public function update(){
  	$this->setState('change_immediate');
	$this->notifyObservers();
	
	$return=parent::update();
	
	$this->updateChildrens($this->getParent_id());
	
	return $return;
  }
  
  public function delete(){
  	//delete videos
  	$videos=new MVideos();
  	$videos->setCategories_Id($this->id);
  	$videos->delete();
  	
  	$this->setState('change_immediate');
	$this->notifyObservers();
	
	$return=parent::delete();
	$this->updateChildrens($this->getParent_id());
	return $return;
	
  }
  
  public function load(){
  	parent::load();
  	$this->getLevels();
  }
  
}
?>