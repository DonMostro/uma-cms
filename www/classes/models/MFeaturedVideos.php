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
    $this->categories_id = ctype_digit(@$_GET['category']) && !empty($_GET['category']) ? $_GET['category'] : 0;
    
	$this->pk='videos_id';
    
	
	$this->columns=array(
		'orden'=>1,
		'categories_id'=>0
	);
	
	$this->approved=null;	
  }
  

 public function setCategories_Id($value) { 
  		if(is_array($value)){
  			$this->columns['categories_id']=array();
  			foreach ($value as $k=>$v){
  				$this->columns['categories_id'][$k]=(int)$v;
  			}
  		}else{
  			$this->columns['categories_id']=(int)$value; 
  		}
	}
	
  public function setApproved($value) { $this->approved=(int)$value; }
  /**
   * TODO esta funcion debe ser borrada y usarse solo setCategories_Id
   * @param $value
   * @return unknown_type
   */
  public function setCategoriesId($value) { $this->categories_id=(int)$value; }
  

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
    	 , $this->table_videos.id AS videos_id, $this->table.orden, $this->table.categories_id, $this->table_video_hits.hits ";
	if($this->join_category){$query.=" , $this->table_categories.title AS categories_title ";}
	if($this->join_thumbs){$query.=" , $this->table_thumbs.filename AS thumb ";}
	$query.="FROM $this->table LEFT JOIN $this->table_videos 
		   ON $this->table.videos_id =$this->table_videos.id	"; 
    if($this->join_category){$query.=" LEFT JOIN $this->table_categories ON $this->table.categories_id=$this->table_categories.id ";}
	if($this->join_thumbs){$query.=" LEFT JOIN $this->table_thumbs ON $this->table_thumbs.videos_id=$this->table_videos.id ";}
	$query.=" LEFT JOIN $this->table_video_hits ON $this->table_videos.id=$this->table_video_hits.videos_id ";
 	$query.=$this->_where()." GROUP BY videos_id ";
	//	Debug::write($query);
    $this->dataSet->setQuery($query);
  }
  
  /**
   * (non-PHPdoc)
   * @see www/classes/models/MModel#setCountQuery()
   */
  
  protected function setCountQuery(){
  	$query="
  	SELECT COUNT(*) 
  	   FROM $this->table
  	JOIN $this->table_categories 
  	  ON $this->table.categories_id=$this->table_categories.id
  	JOIN $this->table_videos  
  	  ON $this->table.videos_id=$this->table_videos.id
  	   ".$this->_where()."  ";
  	$this->dataSet->setCountQuery($query);
  }
  
  /**
   * (non-PHPdoc)
   * @see www/classes/models/MModel#_where()
   */
  
  protected function _where(){
  	$where="WHERE 1";
  	
  	$ids=$this->idToString("$this->table_videos.id");
	if($ids!="")$where.=" AND $ids";
  	
  	if($this->categories_id!=0)$where.=" AND $this->table.categories_id=$this->categories_id";
  	if($this->columns['categories_id']!=0)$where.=" AND $this->table.categories_id={$this->columns['categories_id']}";
  	if($this->approved!=null)$where.=" AND $this->table_videos.approved='1'";
 
    return $where;
  }
  
  
  public function add(){
    $this->setState('change_immediate');
	$this->notifyObservers();
	return parent::add();
  }
  
 
  /**
   * (non-PHPdoc)
   * @see www/classes/models/MModel#delete()
   */
   
  
  public function delete(){
  	$ids=$this->idToString("$this->table.id");
	if($ids!=""){
		$where="WHERE $ids";
		$this->setState('change_immediate');
	    $this->notifyObservers();
	
		$dao=new DAO();
		return $dao->query("DELETE FROM $this->table $where");
	}
  }
  
  public function update(){
  	$this->setState('change_immediate');
	$this->notifyObservers();
  	return parent::update();
  }

}  
?>
