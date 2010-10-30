<?php
include_once("root.php");
include_once(ROOT."classes/lib/DAO.php");
include_once(ROOT."classes/lib/Debug.php");
include_once(ROOT."classes/lib/Observable.php");
include_once(ROOT."classes/models/MModelCache.php");

/**
 * Cacheable model
 *
 */
class MCacheableModel extends MModel {
	
	var $dataSet;
	var $id;
	var $buffer;
	var $cache;
	
	function MCacheableModel($dataSet=null){
		parent::MModel($dataSet);
		$this->buffer=array();
		$this->cache=new MModelCache();
		$this->cache->setModel(get_class($this));
	}
	
	
	function load(){
		$this->cache->setId(md5($this->dataSet->query));
		$this->cache->load();
		if($data=$this->cache->next()){
			$this->buffer=unserialize($data['data']);
		}else{
			if($this->dataSet){
				$this->setQuery();
				$this->dataSet->fill();
				$this->buffer=array();
				while ($row=$this->dataSet->next()) {
					$this->buffer[]=$row;
				}
			}
			$this->cache->setData(serialize($this->buffer));
			$this->cache->add();
		}
		
	}
	
	function add(){
		$this->cache->delete();
	}
	function update(){
		$this->cache->delete();
	}
	function delete(){
		$this->cache->delete();
	}
	
	function countAll(){
		$this->cache->setId(md5($this->dataSet->countQuery));
		$this->cache->load();
		if($data=$this->cache->next()){
			return $data['data'];
		}else{
			if($this->dataSet){
				$this->setCountQuery();
				$count=$this->dataSet->countAll();
			} else {
				$count=0;
			}
			$this->cache->setData($count);
			$this->cache->add();
			return $count;
		}
	}
	
	function next(){
		list($key, $value) = each($this->buffer);
		return $value;
	}
	
	function reset(){
		reset($this->buffer);
	}
	
	function getSize(){
		return count($this->buffer);
	}

}

?>