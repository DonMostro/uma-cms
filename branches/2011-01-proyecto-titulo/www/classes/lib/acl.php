<?php
include_once("root.php");
include_once(ROOT."classes/models/MACLGroups.php");
include_once(ROOT."classes/models/MStats.php");

/**
 * _________________________________________
 * Acceso a las instalaciones de control.
 *___________________________________________
 */
class ACL {

	private $username;
	private $res_type;
	private $resource;
	private $res_access;
	
	private $data;
	private $enabled;
	
	function __construct($username, $res_type=null, $resource=null, $track_stats=false){
		$s=Settings::getInstance();
		$settings=$s->getSettings();
		$this->enabled=$settings['access_control']=='1';
		if($this->enabled){
			if(!empty($username)){
				$this->username=$username;
			}else{
				$this->username='anonymous';
			}
			$this->res_type=$res_type;
			$this->resource=$resource;
			
			$groups=new MACLGroups();
			$groups->setUsername($this->username);
			if($resource!==null&&$res_type!==null){
				$groups->setResourceId($resource);
				$groups->setRes_type($res_type);
			}
			
			$groups->addOrder(new DataOrder('b_time','DESC'));
			$groups->addOrder(new DataOrder('bandwidth','DESC'));
			$groups->addOrder(new DataOrder('v_time','DESC'));
			$groups->addOrder(new DataOrder('views','DESC'));
			$groups->addOrder(new DataOrder('featured','DESC'));
			$groups->addOrder(new DataOrder('videos','DESC'));
			$groups->addOrder(new DataOrder('categories','DESC'));
			$groups->addOrder(new DataOrder('channels','DESC'));
			$groups->addOrder(new DataOrder('favorites','DESC'));
			$groups->addOrder(new DataOrder('playlists','DESC'));
			$groups->addOrder(new DataOrder('subscriptions','DESC'));
			$groups->addOrder(new DataOrder('comments','DESC'));
			$groups->addOrder(new DataOrder('upload','DESC'));
			$groups->addOrder(new DataOrder('download','DESC'));
			$groups->addOrder(new DataOrder('groups','DESC'));
			$groups->addOrder(new DataOrder('pages','DESC'));
			$groups->addOrder(new DataOrder('reports','DESC'));
			$groups->addOrder(new DataOrder('products','DESC'));
			
			
			$groups->load();
			$this->data=$groups->next();
			if(empty($this->data)&&!empty($resource)&&!empty($res_type)){
				$groups->setResourceId(null);
				$groups->setRes_type(null);
				$groups->load();
				$this->data=$groups->next();
			}
			
			$this->res_access=true;
			if( $track_stats=='views' && (!empty($this->data['views'])||!empty($this->data['bandwidth'])) ) {
				$stats=new MStats();
				$stats->setUsername($this->username);
				if(!empty($this->data['views'])){
					$stats->setTt(time()-$this->data['v_time']);
					$stats->load();
					$data=$stats->next();
					if($data['views']>=$this->data['views'] && $this->data['views']!=-1){
						$this->res_access=false;
					}
			    }
			    if(!empty($this->data['bandwidth'])){
					$stats->setTt(time()-$this->data['b_time']);
					$stats->load();
					$data=$stats->next();
					if($data['bandwidth']>=$this->data['bandwidth'] && $this->data['bandwidth']!=-1){
						$this->res_access=false;
					}
			    }
			}
			if( $track_stats=='uploads' && !empty($this->data['uploads']) ){
				$stats=new MStats();
				$stats->setUsername($this->username);
				$stats->setTt(time()-$this->data['u_time']);
				$uploads=$stats->userUploads();
				if($uploads>=$this->data['uploads'] && $this->data['uploads']!=-1){
					$this->res_access=false;
				}
			}
		}
	}
	
	/**
	 * ¿Puede el acceso de los usuarios la parte dada bajo condiciones dadas?
	 *
	 * @return boolean
	 */
	public function canAccess(){
		if(!$this->enabled)return true;
		
		if(isset($this->data['access'])){
			return $this->res_access&&$this->data['access']>=1;
		}else{
			return !empty($this->data)&&$this->res_access&&$this->data[$this->res_type]>=1;
		}
	}
	
	/**
	 * ¿El usuario puede modificar la parte concreta en determinadas condiciones?
	 *
	 * @return boolean
	 */
	public function canModify(){
		if(!$this->enabled)return false;
		if(isset($this->data['access'])){
			return $this->data['access']>=2;
		}else{
			return !empty($this->data)&&$this->data[$this->res_type]>=2;
		}
	}
}

?>