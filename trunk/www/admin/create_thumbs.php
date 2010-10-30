<?php
include_once("root.php");
include_once(ROOT."config.php");
include_once(ROOT."classes/lib/Settings.php");
include_once(ROOT."classes/lib/Form.php");
include_once(ROOT."classes/lib/ffmpeg.php");
include_once(ROOT."classes/models/MVideos.php");
include_once(ROOT."classes/admin/auth.php");

DAO::connect();
$form=new Form();

$auth=new Auth();

$auth->authenticate(!empty($form->username)?$form->username:'',!empty($form->password)?$form->password:'');

if(isset($form->v)){
	$s=Settings::getInstance();
	$settings=$s->getSettings();
	
	$video=new MVideos();
	$video->setId($form->v);
	$video->load();
	$data=$video->next();
	
	if(!empty($data['orig_file'])){
		//Backward-compatibility for names that were not correctly changed during old version imports
		$nf=preg_replace('#[^\w/\.]#','_',$data['orig_file']);
		if($nf!=$data['orig_file']){
			@copy(ROOT.FILES.'/'.$data['orig_file'],ROOT.FILES.'/'.$nf);
			$data['orig_file']=$nf;
		}
		
		$ffmpeg=new ffmpeg($settings['ffmpeg_path'],ROOT.FILES);
		$video_info=$ffmpeg->get_info($data['orig_file']);
		$ss=(int)($video_info['seconds']*0.5);
		
		$pathparts=pathinfo($data['orig_file']);
		$ext=!empty($pathparts['extension'])?$pathparts['extension']:'';
		$fname=$pathparts['dirname']!='.'?$pathparts['dirname'].'/'.$pathparts['filename']:$pathparts['filename'];
		
		$px=(int)rand(1,1000000);
		$thumb_count=(int)$settings['ffmpeg_thumbnails'];
		$thumbnail=array();
		for($i=0; $i<$thumb_count; $i++){
			$ss=(int)(($video_info['seconds']/($thumb_count+1))*($i+1));
			if($ffmpeg->create_thumbnail($data['orig_file'],$fname.$px."{$i}.jpg",$ss,$settings['ffmpeg_thumbnail_size'],ROOT.THUMBNAILS)){
				$thumbnail[]=$fname.$px."{$i}.jpg";
			}
		}
		
		if(!empty($thumbnail)){
			$thumb=new MThumbnails();
			$thumb->setVideos_id($data['id']);
			$thumb->delete();
			foreach ($thumbnail as $t){
				$thumb->setFilename($t);
				$thumb->add();
			}
			echo 'The thumbnails have been created.';
		}else{
			echo '<br/>';
			echo 'An error occured while trying to create the thumbnails.';
		}
	}else{
		echo 'An error occured while trying to create the thumbnails.<br />
		Please note that this feature only works for videos hosted on this server.';
	}
}
?>