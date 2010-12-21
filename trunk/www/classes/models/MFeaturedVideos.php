<?php
include_once("root.php");
include_once(ROOT."classes/lib/DAO.php");
include_once(ROOT."classes/lib/Debug.php");
include_once(ROOT."classes/lib/RecordSet.php");
include_once(ROOT."classes/lib/Types.php");
include_once(ROOT."classes/models/MModel.php");

class MFeaturedVideos extends MModel {
  
  private $username;
  private $categories_id;
  private $approved;
  private $acomodar = true;
  private $max_num_featured = 1;
  private $join_category=true;
  private $join_thumbs=true;
  private $table_videos;
  private $table_video_hits;
  private $table_categories; 
  private $table_thumbs;   
  
  /**
   * Constructor
   */
  
  function __construct(){
    parent::__construct(new RecordSet());

    $this->table=TABLE_PREFIX.'featured';
    $this->table_videos=TABLE_PREFIX.'videos';
    $this->table_video_hits=TABLE_PREFIX.'video_hits';
    $this->table_categories=TABLE_PREFIX.'categories';
    $this->table_thumbs=TABLE_PREFIX.'thumbs';
	$this->pk='videos_id';
    
	
	$this->columns=array(
		'orden'=>1
	);
	
	$this->categories_id=0;
	$this->approved=null;	
  }
  

  public function setCategories_Id($value) { $this->categories_id=(int)$value; }
  public function setApproved($value) { $this->approved=(int)$value; }
  
  public function getUsername() { return $this->username; }
  public function getCategories_Id() { return $this->categories_id; }
  
  /**
   * Evita hacer join con la tabla categories para alivianar la query
   * @param $value
   * @return unknown_type
   */
  
  public function byPassCategory($value=true){
  		$this->columns['bypass_category']=$value;
  }
  
  /**
   * Evita hacer join con tabla thumbs para alivianar la query
   * @param $value
   * @return unknown_type
   */
  
  public function byPassThumbs($value=true){
  		$this->columns['bypass_thumbs']=$value;
  }
  
  /**
   * 
   * @return unknown_type
   */
  
  public function getApproved() { return $this->approved; }
  
  /**
   * (non-PHPdoc)
   * @see www/classes/models/MModel#setQuery()
   */
  
  protected function setQuery(){
  	if(@$this->columns['bypass_category']) $this->join_category=false;
  	if(@$this->columns['bypass_thumbs'])$this->join_thumbs=false;
  	
    $query="
    SELECT $this->table_videos.*
    	 , $this->table_videos.id AS videos_id, $this->table.orden, $this->table_video_hits.hits ";
	if($this->join_category){$query.=" , $this->table_categories.title AS categories_title ";}
	if($this->join_thumbs){$query.=" , $this->table_thumbs.filename AS thumb ";}
	$query.="FROM $this->table LEFT JOIN $this->table_videos 
		   ON $this->table.videos_id =$this->table_videos.id	"; 
    if($this->join_category){$query.=" LEFT JOIN $this->table_categories ON $this->table_videos.categories_id=$this->table_categories.id ";}
	if($this->join_thumbs){$query.=" LEFT JOIN $this->table_thumbs ON $this->table_thumbs.videos_id=$this->table_videos.id ";}
	$query.=" LEFT JOIN $this->table_video_hits ON $this->table_videos.id=$this->table_video_hits.videos_id ";
 	$query.=$this->_where()." GROUP BY videos_id ";

    $this->dataSet->setQuery($query);
  }
  
  /**
   * (non-PHPdoc)
   * @see www/classes/models/MModel#setCountQuery()
   */
  
  protected function setCountQuery(){
  	$query="
  	SELECT COUNT(*) 
  	   FROM $this->table_videos 
  	JOIN $this->table_categories 
  	  ON $this->table_videos.categories_id=$this->table_categories.id
  	JOIN $this->table 
  	  ON $this->table.videos_id=$this->table_videos.id
  	   ".$this->_where()."
  	   AND $this->table_videos.id IN (SELECT videos_id FROM $this->table)";

  	$this->dataSet->setCountQuery($query);
  }
  
  /**
   * (non-PHPdoc)
   * @see www/classes/models/MModel#_where()
   */
  
  protected function _where(){
  	$where="WHERE 1";
  	
  	$ids=$this->idToString("videos.id");
	if($ids!="")$where.=" AND $ids";
  	
  	if($this->categories_id!=0)$where.=" AND $this->table_categories.id=$this->categories_id";
  	if($this->approved!=null)$where.=" AND $this->table_videos.approved='1'";
 
    return $where;
  }
  
  
  public function add2(){
    $this->setState('change_immediate');
	$this->notifyObservers();
	return parent::add();
  }
  
  /**
   * (non-PHPdoc)
   * @see www/classes/models/MModel#add()
   */
  
  function add(){
  	 //$this->updateTail($this->columns['orden']);	
	 $this->add2();
	 /*if($this->acomodar)*/// $this->synchOrder();
   	 //$this->deleteSpare();
     //$this->create_xml();
  }
  
  /**
   * (non-PHPdoc)
   * @see www/classes/models/MModel#delete()
   */
   
  
  public function delete(){
  	$ids=$this->idToString("videos_id");
	if($ids!=""){
		$where="WHERE $ids";
		$this->setState('change_immediate');
	    $this->notifyObservers();
	
		$dao=new DAO();
		$dao->query("DELETE FROM $this->table $where");
		//$this->synchOrder();
		//$this->create_xml();
	}
  }
  
  
  protected function update2(){
  	$this->setState('change_immediate');
	$this->notifyObservers();
  	return parent::update();
  }
  
  /**
   * (non-PHPdoc)
   * @see www/classes/models/MModel#update()
   */
  
  function update(){
 	//$this->updateTail($this->columns['orden']);
	$this->update2();
	/*if($this->acomodar)*/ $this->synchOrder();
  	//$this->deleteSpare();
    //$this->create_xml();
  }
  

 
 /**
  * Extrae todos los registros de featured ordenados por 'orden' y les da un orden correlativo 
  * elimina los "espacios muertos"
  *
  * @return int
  */
 

  	protected function synchOrder(){
  		$arrIds = array();
  		$query = "SELECT id FROM $this->table ORDER BY orden ASC;";
  		$rs=mysql_query($query);
  		$i=1;
  		while($row = mysql_fetch_array($rs)) {
  			$arrIds[$i] = $row['id'];
  			$i++;
  		}
  		//print_r($arrIds);
  		for($i=1;$i<=count($arrIds);$i++){
  			$id=$arrIds[$i];
  			$query = "UPDATE $this->table SET orden = $i WHERE id=$id";
  			mysql_query($query);
  			//echo($query . '<br/>');
  		}
  	//	die();
  	}
 
 /**
  * Incrementa el campo orden de todos los registros que sean mayor a 'orden' 
  * solo si esta ocupado el espacio del nuevo registro, como una pila.
  * Deja el espacio para insertar un nuevo registro, incrementando el indice orden de los que estan sobre el)
  *
  * @param int $orden
  * @return int - num regs actualizados
  */
   	
  
	protected function updateTail($orden){
		$ok = 0;
		$query = "SELECT * FROM $this->table WHERE orden = $orden AND videos_id <> 0;";
		//echo $query . '<br/>';
		if(mysql_num_rows(mysql_query($query)) >= 1 && $orden <= $this->max_num_featured){
			//die($query);
			for($i=$this->max_num_featured; $i>=$orden; $i--){
		  	$query = "UPDATE $this->table SET orden = orden+1 WHERE orden = $i ;";
		  	if(mysql_query($query)) $ok++;
		  	//echo $query . '<br/>';
			}
		}else{
			$this->acomodar = false;
		}
		//die();
		return $ok;
	}
	
	/**
	 * Borra los registros cuyo campo orden excede el num de destacados
	 *
	 * @return query
	 */
	
	protected function deleteSpare(){
		$query="DELETE FROM $this->table WHERE orden > $this->max_num_featured OR videos_id = 0";
		//die($query);
		return mysql_query($query);
	}
}
?>
