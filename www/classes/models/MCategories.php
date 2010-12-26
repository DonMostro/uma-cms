<?php
include_once("root.php");
include_once(ROOT."classes/lib/DAO.php");
include_once(ROOT."classes/lib/Debug.php");
include_once(ROOT."classes/lib/RecordSet.php");
include_once(ROOT."classes/lib/Types.php");
include_once(ROOT."classes/models/MModel.php");
include_once(ROOT."classes/models/MBufferedModel.php");
include_once(ROOT."classes/models/MVideos.php");

/**
 * Clase modelo de categor&iacute;as
 *  *
 */

class MCategories extends MBufferedModel {
	
  private $tmpbuff;
  private $table_videos;
  
  /**
   * Constructor
   */
	
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
  
  /**
   * (non-PHPdoc)
   * @see www/classes/models/MModel#setQuery()
   */

  protected function setQuery(){
  	
    //Get list of categories
    $query="
    SELECT $this->table.*
         , COUNT( DISTINCT $this->table_videos.id ) AS videos
	  FROM $this->table 
 	LEFT JOIN $this->table_videos
        ON $this->table.id = $this->table_videos.categories_id
	 ".$this->_where()."
	 GROUP BY $this->table.id";
	//echo $query;
    
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
   * @see www/classes/models/MModel#_where()
   */
  
  protected function _where(){
  	$where="WHERE 1";
  	$ids=$this->idToString("$this->table.id");
	if($ids!="")$where.=" AND $ids";
	if($_SERVER['PHP_SELF'] == '/index.php') $where.= " AND $this->table.title <> 'Prueba' ";	
	if($this->columns['approved']!==null)$where.=" AND $this->table.approved='".$this->columns['approved']."'";
  	
  	return $where;
  }
  
  /**
   * Obtiene los nodos hijos.
   */
  
  
  public function getLevels(){
  	$this->tmpbuff=array();
  	$parent_id=$this->columns['parent_id']!==null?$this->columns['parent_id']:0;
  	$this->iterate($parent_id);
  	$this->buffer=$this->tmpbuff;
  	$this->reset();
  }
  
  /**
   * funci&oacute;n recursiva que busca todos los hijos asociados a un canal.
   * @param $id
   * @param $level
   */
  
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
  
  /**
   * A&ntilde;adir canales hijos
   * @param $parent_id
   * @return unknown_type
   */
  
  private function addChildren($parent_id){
  	$dao=new DAO();
  	$cat=new MCategories();
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
  
  /**
   * Actualiza todos lo canales hijos en el campo childrens,
   * esta es una desnormalizaci�n a cambio de rendimiento de la base de datos 
   * @param $parent_id
   * @return unknown_type
   */
  
  private function updateChildrens($parent_id){
  	$dao=new DAO();
  	$cat=new MCategories();
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
  
  /**
   * Retorna id parent en base a otra id
   * @param $id
   * @return unknown_type
   */
  
  public function getParent($id){
  	$dao=new DAO();
  	$dao->query("SELECT parent_id FROM ztv_categories WHERE id=".(int)$id);
  	$parent_id=$dao->get(0,"parent_id");
  	if(!empty($parent_id)){
  		$dao->query("SELECT title FROM ztv_categories WHERE id = $parent_id");
  		return $dao->get(0,"title");
  	}else return false;
  }
  
  
  
  /**
   * (non-PHPdoc)
   * @see www/classes/models/MModel#add()
   */
  
  public function add(){
  	$this->setState('change_immediate');
	$this->notifyObservers();
  	$this->id=parent::add();
  	$this->updateChildrens($this->getParent_id());
  	return $this->id;
  }
  
  /**
   * (non-PHPdoc)
   * @see www/classes/models/MModel#update()
   */
  public function update(){
  	$this->setState('change_immediate');
	$this->notifyObservers();
	
	$return=parent::update();
	
	$this->updateChildrens($this->getParent_id());
	
	return $return;
  }
  
  /**
   * (non-PHPdoc)
   * @see www/classes/models/MModel#delete()
   */
  
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
  
  /**
   * (non-PHPdoc)
   * @see www/classes/models/MBufferedModel#load()
   */
  
  public function load(){
  	parent::load();
  	$this->getLevels();
  }
  
}
?>