<?php
include_once("root.php");
include_once(ROOT."config.php");
include_once(ROOT."classes/lib/DAO.php");
include_once(ROOT."classes/lib/Debug.php");
include_once(ROOT."classes/lib/RecordSet.php");
include_once(ROOT."classes/lib/Types.php");
include_once(ROOT."classes/lib/Settings.php");
include_once(ROOT."classes/models/MModel.php");
include_once(ROOT."classes/models/MCategoryList.php");
include_once(ROOT."classes/models/MThumbnails.php");
include_once(ROOT."classes/models/MImages.php");
include_once(ROOT."classes/models/MTags.php");
include_once(ROOT."classes/models/MWatched.php");

class MVideos extends MModel {
  
  private $search;
  private $group;
  private $visitor;
  private $public;
  private $exclude;
  private $iphone_id;
  private $get_childrens;
  private $join_category=true;
  private $join_thumbs=true;  
  
  function __construct(){
    parent::__construct(new RecordSet());
    
   
   if($_SERVER['SCRIPT_NAME']=="/admin/index.php"){
    	//echo "admin";
		//$this->dataSet->setStart(0);
		//$this->dataSet->setLimit(12);
		$this->dataSet->addOrder(new DataOrder("tt"));
    }	
	
	$this->table=TABLE_PREFIX.'videos';
	$this->table_categories=TABLE_PREFIX.'categories';
	$this->table_thumbs=TABLE_PREFIX.'thumbs';
	$this->table_hits=TABLE_PREFIX.'video_hits';
	$this->table_video_types=TABLE_PREFIX.'video_types';
	$this->table_videos_categories=TABLE_PREFIX.'videos_categories';
	
	$this->columns=array(
		'categories_id'=>null,
  		'approved'=>false,
  		'reported'=>false,
  		/*'username'=>null,*/
  		'title'=>null,
  		'description'=>null,
  		'tags'=>null,
  		/*'filename'=>null,
  		'small_filename'=>null,
  		'filename_3gp'=>null,
  		'filename_hd'=>null,
		'filename_wmv'=>null,*/
  		'type'=>null,
  		'tt'=>null,
  		'duration'=>null,
  		'uploads_id'=>null,
  		'private'=>false,
  		'frame'=>null,
  		'orig_file'=>null,
  		'downloadable'=>null,
  		'embed'=>null,
  		'servers_id'=>null,
  		'size'=>null,
  		'type_id'=>false,
		'hits_m'=>null,
		'hits_w'=>null,
		'hits_d'=>null
	);
	
    $this->search="";
    $this->iphone=false;
    $this->cellphone=false;
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
  
  public function byPassCategory($value=true){
  		$this->columns['bypass_category']=$value;
  }
  
  public function byPassThumbs($value=true){
  		$this->columns['bypass_thumbs']=$value;
  }
  
  
  public function setParent_Id($value) { 
  	if(is_array($value)){
  		$this->columns['parent_id']=array();
  		foreach ($value as $k=>$v){
  			$this->columns['parent_id'][$k]=(int)$v;
  		}
  	}else{
  		$this->columns['parent_id']=(int)$value; 
  	}
  	$this->get_childrens=true;
  }
  
  
  
  
  /**
   * 
   * setIphoneId @param $id
   * [TODO] esta funciÃ³n es temporal, deberÃ­a eliminarse
   * y ser reemplazada por $this->setId($id) una vez eliminados los conflictos
   */ 

  function setIphoneId($id){
  	$this->iphone_id=$id;
  }	

  function setSearch($value) {
  	$search=str_replace("%"," ",$value);
    $search=str_replace("_"," ",$search);
    $this->search=mysql_real_escape_string(trim($search));
  }
  function setPublic($value) { $this->public=(int)$value; }
  function setGroup($value) { $this->group=(int)$value; }
  function setVisitor($value) { $this->visitor=mysql_real_escape_string($value); }
  function setExclude($value) { $this->exclude=mysql_real_escape_string($value); }
  function setSmallFileName($value) { $this->columns['small_filename'] = mysql_real_escape_string($value); }
  function setFileName3GP($value) { $this->columns['filename_3gp'] = mysql_real_escape_string($value); }
  function setFileNameHD($value) { $this->columns['filename_hd'] = mysql_real_escape_string($value); }
  function setFileNameWMV($value) { $this->columns['filename_wmv'] = mysql_real_escape_string($value); }
  function setIphone($value=true) {$this->iphone = $value===false ? false : true; }
  function setCellphone($value=true) {$this->cellphone = $value===false ? false : true; }
  
  function getSearch() { return $this->search; }
  function getGroup() { return $this->group; }
  
  public function loadFilename() {
  	$query="
  	SELECT ";
    if(Util::getOS() != 'iPod' && Util::getOS() != 'iPhone' && Util::getOS() != 'BlackBerry' && Util::getOS() != 'iPad' && @$_GET['qua'] != 'hd'){
  	  $query .= " filename ";
    }elseif(@$_GET['qua'] == 'hd'){  
      $query .= " filename_hd AS filename ";	 	  
    }else{
      $query .= " small_filename AS filename ";	
    }
   	
  	$query .=", orig_file
  		 , downloadable
  		 , frame
  	   FROM $this->table 
  	   WHERE $this->table.id=".(int)$this->id;
	   
  	$this->dataSet->setQuery($query);
  	$this->dataSet->fill();
  }
  
  protected function setQuery(){
  	if(@$this->columns['bypass_category']) $this->join_category=false;
  	if(@$this->columns['bypass_thumbs']) $this->join_thumbs=false;
	$search='';

	$query="
	SELECT $this->table.title, $this->table.description, $this->table.tt, $this->table.approved, /*$this->table.username,*/
	$this->table.orig_file, $this->table_video_types.filename, 
	$this->table.frame, $this->table.rate, $this->table_hits.hits AS hits, $this->table.duration, $this->table_videos_categories.categories_id ";
	if($this->join_category)$query.=", $this->table_categories.title AS categories_title ";
	if($this->join_thumbs)$query.=", $this->table_thumbs.filename AS thumb ";
	$query.=", $search
	    $this->table.id
	    FROM $this->table ";
	if($this->join_thumbs)$query.=" LEFT JOIN $this->table_thumbs ON $this->table_thumbs.videos_id=$this->table.id";
	$query.=" 
	LEFT JOIN $this->table_videos_categories ON $this->table.id=$this->table_videos_categories.videos_id 
	LEFT JOIN $this->table_hits ON $this->table.id=$this->table_hits.videos_id 
	LEFT JOIN $this->table_video_types ON $this->table.id=$this->table_video_types.videos_id 
	   ";
	if($this->join_category)$query.=" LEFT JOIN $this->table_categories ON $this->table_categories.id=$this->table_videos_categories.categories_id ";
	$query.=$this->_where()." 
	   GROUP BY $this->table.id";
	/*	
	if(!isset($_GET['sort'])){
		if(isset($_GET['p'])){	
			$this->dataSet->addOrder(new DataOrder("videos.id","DESC"));
		}else{
			$this->dataSet->addOrder(new DataOrder("tt","DESC"));
		}
	}
	*/
	if($this->iphone || $this->cellphone){
		// SETEO DEL ORDER BY PARA MOBILES
		switch(@$_REQUEST['ord']){
			case 1:
				$field[0]="$this->table.tt";$dir[0]='DESC';
				break;
			case 2:
				$field[0]="$this->table_hits.hits";$dir[0]='DESC';
				$field[1]="$this->table.rate";$dir[1]='DESC';
				break;
			case 3:
				$field[0]="$this->table_hits.hits";$dir[0]='DESC';
				break;
			default:			
			    $field[0]="$this->table.tt";$dir[0]='DESC';
		}
		for($i=0;$i<count($field); $i++){
			$this->dataSet->addOrder(new DataOrder($field[$i], $dir[$i]));
		}	
	}
	//echo $query;
    $this->dataSet->setQuery($query);
  }
  
  protected function setCountQuery(){
  	
  	$query="
  	SELECT COUNT(*) 
  	   FROM $this->table 
	LEFT JOIN $this->table_categories 
	  ON $this->table.categories_id=$this->table_categories.id
	   ".$this->_where();
  	//echo $query;

  	$this->dataSet->setCountQuery($query);
  }
  
  protected function _where(){
  	$where="WHERE 1";
	if(!empty($this->iphone_id) && (int)$this->iphone != 0 ){
		$ids="$this->table.id=".(int)$this->iphone_id; 
	}else{
		$ids=$this->idToString("$this->table.id");
	}

	if(@$ids!="")$where.=" AND $ids";
	
	if(@$this->columns['parent_id']=="by_request"){
		$VPage=new VPage();
		$VPage->SetAllRequestItems();
		$this->columns['parent_id'] = (int)$VPage->req_c_parent;
		$this->get_childrens=true;
	}elseif(@$this->columns['categories_id']=="by_request"){
		$VPage=new VPage();
		$VPage->SetAllRequestItems();
		$this->columns['parent_id'] = (int)$VPage->req_c;
		$this->get_childrens=true;
	}

	if($this->get_childrens){
		//$categories = new MCategoryList();
		$strSQL = "SELECT children FROM $this->table_categories WHERE id =".(int)$this->columns['parent_id'];
		$qry = mysql_query($strSQL);
		$children=$this->columns['parent_id'];
		if($row=mysql_fetch_row($qry)){
			$children .= $row[0];
		}
		$where.=" AND $this->table.categories_id IN ($children)";
	}else{
	
	
		if($this->columns['categories_id']!=0&&!is_array($this->columns['categories_id'])){
			$where.=" AND $this->table.categories_id=".(int)$this->columns['categories_id'];
			
		}elseif(is_array($this->columns['categories_id'])){
			$where.=" AND $this->table.categories_id IN (".implode(',',$this->columns['categories_id']).")";
		}
	}	

	
	/**[TODO] estas comparaciones de URL debieran cambiarse por comparaciones con atributos de la clase */
	if($_SERVER['SCRIPT_NAME'] != '/mobile/index.php'){
		if(/*($_SERVER['SCRIPT_NAME'] == '/index.php' && !isset($_GET['m'])) 
		||*/ ($this->iphone && $_SERVER['SCRIPT_NAME'] == '/iphone/index.php' && @$_GET['ord'] == '3') 
		|| (empty($this->search) && $_SERVER['SCRIPT_NAME'] == '/iphone/index.php' && @$_GET['ord'] != '3')){
		  		$dateStart = time() - 86400 * 7; //86400 segundos tiene un dia
				$dateEnd = time();
				$where.=" AND $this->table.tt BETWEEN $dateStart AND $dateEnd ";
	  	}
	}  	
	
	/**[TODO] esto nos asegura que nunca se mostrarÃ¡n videos desaprobados en el front, 
	 * pero en lugar de esto debiera usarse un mÃ©todo setApproved*/
	/*
	if($_SERVER['SCRIPT_NAME'] == '/index.php' || $this->iphone || $this->cellphone){
	   $where.=" AND videos.approved= '1' ";
	}
	*/
	//if($this->group!==null)$where.=" AND groups.id=$this->group";
	
	if($this->iphone && @$_GET['m'] == 'search') $this->search = @$_GET['search'];
	elseif($this->cellphone && @$_GET['m'] == 'search') $this->search = @$_GET['search'];
	
	if($this->search!="")$where.="
	AND (( ".$this->_tags()." ) OR $this->table.title LIKE '%$this->search%')";
	
	if($this->exclude!=""){
		$tagstr='';
		$tags=explode(" ",trim($this->exclude));
        foreach($tags as $tag)$tagstr.="$this->table.tags NOT LIKE '%$tag%' AND ";
        $tagstr=substr($tagstr,0,-4);
		$where.=" AND $tagstr AND $this->table.title NOT LIKE '%$this->exclude%'";
	}
	
	if($this->columns['tt'])$where.=" AND $this->table.tt>".(int)$this->columns['tt'];
	//if($this->columns['username'])$where.=" AND $this->table.username='".$this->columns['username']."'";
	if($this->columns['approved']!==false)$where.=" AND $this->table.approved='".$this->columns['approved']."'";
	if($this->columns['reported']!==false)$where.=" AND $this->table.reported='".$this->columns['reported']."'";
	if($this->columns['type_id']!==false)$where.=" AND $this->table.type_id=".(int)$this->columns['type_id'];
	if($this->columns['type']!==null)$where.=" AND $this->table.type LIKE '".$this->columns['type']."'";
	if($this->public===0)$where.=" AND $this->table.private = '1'";
	if($this->iphone)$where.=" AND $this->table.small_filename IS NOT NULL AND $this->table.small_filename <> ''";
	if($this->cellphone)$where.=" AND $this->table.filename_3gp IS NOT NULL AND $this->table.filename_3gp <> ''";
	
	
	if($this->columns['filename']!==null)$where.=" AND ($this->table.orig_file='".$this->columns['filename']."' OR $this->table.filename='".$this->columns['filename']."')";

	
    return $where;
  }
  
  private function _tags(){
    $tagstr='';
	if($this->search!=""){
        $tags=explode(" ",$this->search);
        foreach($tags as $tag)$tagstr.="$this->table.tags LIKE '%$tag%' OR ";
        $tagstr=substr($tagstr,0,-4);
	}
	return $tagstr;
  }
  
    
  function add(){
  	/*No deben ser aprobados videos que no existen*/
  	if(is_null($this->columns['filename'])) $this->columns['approved'] = false;

  	$this->columns['tt']=time();
	$videos_id=parent::add();
	$this->setState('change_immediate');
	$this->notifyObservers();
  	
  	if(!empty($this->columns['tags'])){
		$tags=new MTags();
		$tags->setVideos_id($videos_id);
		$this->storeTags($videos_id);
  	}
	return $videos_id;
  }

  private function storeTags($videos_id){
    $dao=new DAO();
	$this->tags=explode(",",stripslashes($this->tags));
	$dao->query("DELETE FROM video_tags WHERE videos_id = ".(int)$videos_id);
	
	foreach($this->tags as $tag){
	  $tag=trim($tag, ' ,');
	  //$dao->query("SELECT id FROM tags WHERE LTRIM(RTRIM(LOWER(tag)))='".trim(mysql_real_escape_string(strtolower($tag)))."'");
 	  $dao->query("SELECT id FROM tags WHERE LOWER(tag)='".trim(mysql_real_escape_string(strtolower($tag)))."'");
 	  	
	  if($dao->rowCount()>0){
	    $tags_id=$dao->get(0,"id");
	  }else{
	    $dao->query("INSERT INTO tags (tag)VALUES('".mysql_real_escape_string(trim($tag))."')");
	    $dao->query("SELECT last_insert_id() AS id FROM tags");
	    $tags_id=$dao->get(0,"id");
	  }
	  if(trim($tag) != 'Array')
	  $dao->query("INSERT INTO video_tags (videos_id,tags_id)VALUES(".(int)$videos_id.",$tags_id)");
	}
  }
  
  function update(){
  	$this->setState('change_immediate');
	$this->notifyObservers();
	
	if(!empty($this->columns['tags'])){
		$tags=new MTags();
		$tags->setVideos_id($this->id);
		$this->storeTags($this->id);
	}
	
  	return parent::update();
  }
  
  function delete(){
  	//remove category
  	if($this->columns['categories_id']!=0){
  		$this->id=$this->columns['categories_id'];
  		$ids=$this->idToString("categories_id");
  	}else{
  		$ids=$this->idToString("id");
  		//remove video thumbnails
	  	$thumb=new MThumbnails();
	  	$thumb->setVideos_id($this->id);
	  	$thumb->delete();
	  	
	  	$thumb=new MImages();
	  	$thumb->setVideos_id($this->id);
	  	$thumb->delete();
  	}

  	//remove videos
  	if($ids!=""){
  		$where="WHERE $ids";
	  	$dao=new DAO();
	  	
	  	$query="SELECT filename, orig_file, frame FROM $this->table $where";
	  	$dao->query($query);
	  	while($row=$dao->getAll()) {
	  		@unlink(ROOT.FILES."/".$row['filename']);
	  		@unlink(ROOT.FILES."/".$row['orig_file']);
	  		@unlink(ROOT.FILES."/".$row['frame']);
	  	}
	  	
	  	$query="DELETE FROM $this->table $where";
	  	$dao->query($query);
	  	
	  	$this->setState('change_immediate');
		$this->notifyObservers();
		
		return true;
  	}else{
  		return false;
  	}
  }
  
  public function rate($rating){
  	$dao=new DAO();
    
  	//set video rating
    if(!isset($_COOKIE["rated_$this->id"])){
	    $rate=(int)$rating;
	    $dao->query("INSERT INTO ratings (videos_id,rate)VALUES(".(int)$this->id.",$rate)");
	    setcookie("rated_$this->id",1,time()+2592000);
    }
    
    
    $dao->query("SELECT AVG(ratings.rate)AS rate FROM ratings WHERE videos_id=".(int)$this->id);
    $rate=$dao->get(0,"rate");
    $dao->query("UPDATE $this->table SET rate=$rate WHERE id=".(int)$this->id);
    
    $this->setState('change_delayed');
	$this->notifyObservers();
		
    return $dao->get(0,"rate");
  }
  
  public function view(){
  	if((int)$this->id!=0){
	  	$dao=new DAO();
	    //$dao->query("UPDATE $this->table_hits SET hits=hits+1 WHERE videos_id=".(int)$this->id." ");
	    $dao->query("INSERT INTO $this->table_hits (videos_id, hits) VALUES (".(int)$this->id.",1) ON DUPLICATE KEY UPDATE hits=hits+1");
	
	    /*
	    $watched=new MWatched();
	    $watched->setVideos_id($this->id);
	    $watched->add();
	    */
	    
	    $this->setState('change_delayed');
		$this->notifyObservers();
  	}	
  }
  
  public function download(){
  	$dao=new DAO();
    //increase video view count
    $dao->query("UPDATE $this->table SET downloads=downloads+1 WHERE id=".(int)$this->id." AND approved='1'");
    
    $this->setState('change_delayed');
	$this->notifyObservers();
  }
  
  public function report(){
  	$dao=new DAO();
    $dao->query("UPDATE $this->table SET reported='1' WHERE id=".(int)$this->id);
    $this->setState('change_delayed');
	$this->setMethod('report');
	$this->notifyObservers();
  }
  
  
 
  /**
   * Busca la cortina publicitaria asociada al $this->id seleccionado
   * 
   * @author rodrigo riquelme
   *
   * @return integer 
   */
  
  
  public function getCurtainAd(){
  	$dao=new DAO();
  	$vpage = new VPage();
  	$vpage->SetAllRequestItems();
  	$qry="SELECT $this->table.filename FROM video_curtain_ads 
	LEFT JOIN $this->table 
	ON $this->table.id = video_curtain_ads.videos_id WHERE video_curtain_ads.approved = '1' AND
	(video_curtain_ads.categories_id =".(int) $vpage->req_c .")  LIMIT 0, 1";
	$dao->query($qry);

	return $row=$dao->get(0,'filename');
  }
  
  
  
  
  /**
   * crea XML para Top Carrusel, es un XML por canal
   * @return xml file
   *
   */
  public function create_xml(){
	$xml = fopen (ROOT.FILES_TOPVIDEOS."/topcategorias.xml", "w");
	
	if (!$xml) {
	  echo "<script>alert('no se pudo abrir el archivo XML.')</script>";
	  exit;
	}
	fwrite ($xml, '<?xml version="1.0" encoding="UTF-8" ' . '?' .'>
		<photos path="http://'.$_SERVER['HTTP_HOST'].'/thumbnails/">
		');
	$contenidoxml = "";
	
	$strSQL = "SELECT id, title FROM $this->table_categories ORDER BY title";
	$qry = mysql_query($strSQL);
	$i=0;
	
	while ($row = mysql_fetch_array($qry)){
		$arrCategorias['id'][$i] = $row['id'];
		$arrCategorias['title'][$i] = $row['title'];
		$i++;	
	}
	
	for ($i=0;$i<=count($arrCategorias['id']);$i++){
		if($arrCategorias['title'][$i] != ''){

			$strSQL = "SELECT DISTINCT($this->table.id), title, $this->table_thumbs.filename 
			FROM $this->table LEFT JOIN $this->table_thumbs
			ON $this->table.id = $this->table_thumbs.videos_id
			WHERE categories_id = ". $arrCategorias['id'][$i] ."
			ORDER BY $this->table.id DESC LIMIT 1,1 ";

			$qry = mysql_query($strSQL);
			$lngOldId = 0;
			while ($row = mysql_fetch_array($qry)){
				if($row["filename"] != '' && $row["id"] != ''){
					$contenidoxml ='<photo name="'. Util::LimitText(utf8_encode(Util::cadenaXML($row["title"])),56) .'" ';
			  		$contenidoxml .='url="'. $row["filename"] .'" ';
			  		$contenidoxml .='link="http://'.utf8_encode($_SERVER['HTTP_HOST'].'/index.php?m=video&v='.$row["id"]).'">';
			  		$contenidoxml .='</photo>
			  		';
			  		fwrite ($xml, $contenidoxml);
				}
			}
		}
	}
	fwrite ($xml, "		</photos>
	");
	
	if (fclose ($xml)){
	   // echo "<script>alert('Archivo XML actualizado con exito')</script>";
	} else {
	    echo ("<script>alert('Error al escribir el archivo')</script>");
	    exit;
	}
  }
}
?>