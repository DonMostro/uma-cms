<?php
include_once("root.php");
include_once(ROOT."config.php");
include_once(ROOT."classes/lib/Settings.php");
include_once(ROOT."classes/lib/Form.php");
include_once(ROOT."classes/lib/ffmpeg.php");
include_once(ROOT."classes/models/MVideos.php");
include_once(ROOT."classes/admin/auth.php");
/*
ini_set("display_errors","On");
error_reporting(E_ALL);
*/
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
		
		$ffmpeg=new ffmpeg($settings['ffmpeg_path'],ROOT.FILES,$settings['watermark_path']);
		$oldfile=$data['filename_hd'];
		$oldframe=$data['frame'];
		$pathparts=pathinfo($data['orig_file']);
		$ext=!empty($pathparts['extension'])?$pathparts['extension']:'';
		$fname=$pathparts['dirname']!='.'?$pathparts['dirname'].'/'.$pathparts['filename_hd']:$pathparts['filename_hd'];
		$px=(int)rand(1,1000000);
		if($ffmpeg->convert($data['orig_file']
		  ,$fname.$px.'_hd.flv'
		  ,'640x480'
		  ,$settings['ffmpeg_bitrate']
		  ,$settings['ffmpeg_ar']
		  ,$settings['skip_flv_conversion']=='1'
		  ,$settings['watermark']=='1'
		  )){
			
			$images=array('gif','jpg','jpeg','png');
			if(!in_array($ext,$images)){
				$data['filename_hd']=$fname.$px."_hd.flv";
				$data['ext']="flv";
				//get video duration and calculate the time of a frame to capture
				$video_info=$ffmpeg->get_info($data['filename_hd']);
				//$ss=(int)($video_info['seconds']*0.5);
				//$h=$video_info['duration'][0]!='00'?$video_info['duration'][0].':':'';
				//$video->setDuration($h.$video_info['duration'][1].":".$video_info['duration'][2]);
			}
			
			//capture a frame
	/*		if($ffmpeg->create_thumbnail($data['filename_hd'],$fname.$px."_c.jpg",$ss,$settings['ffmpeg_size'],ROOT.FILES)){
				$frame=$fname.$px."_c.jpg";
				@unlink(ROOT.FILES.'/'.$oldframe);
			}else{
				$frame="";
			}
	*/		
			
			if($oldfile!=$data['orig_file'])@unlink(ROOT.FILES.'/'.$oldfile);

			$video->setfilename_hd($data['filename_hd']);
			$video->setType($data['ext']);
			if(!empty($frame))$video->setFrame($frame);
			$video->update();
			echo 'El video ha sido convertido.';
		}else{
			echo 'Ha ocurrido un error mientras se convertia el video.';
		}
	}else{
		echo 'Ha ocurrido un error mientras se convert&iacute;a el video.<br />
		Por favor note que esta funcionalidad trabaja solo con videos hospedados en este servidor.';
	}
}
@mysql_close();
?>