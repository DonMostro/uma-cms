<?php
ini_set("display_errors","On");
error_reporting(E_ALL);

include_once("root.php");
include_once(ROOT."config.php");
include_once(ROOT."classes/lib/Settings.php");
include_once(ROOT."classes/lib/Form.php");
include_once(ROOT."classes/lib/ffmpeg.php");
include_once(ROOT."classes/models/MVideos.php");
include_once(ROOT."classes/admin/auth.php");

$conx = DAO::connect();
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
		/*if(mysql_close($conx)){
			echo "<script>alert('Se ha cerrado temporalmente la conexión a la BD')</script>";
		}else{
			echo "<script>alert('No se ha cerrado la conexión a la BD')</script>";
		}*/
		//Backward-compatibility for names that were not correctly changed during old version imports
		$nf=preg_replace('#[^\w/\.]#','_',$data['orig_file']);
		if($nf!=$data['orig_file']){
			@copy(ROOT.FILES.'/'.$data['orig_file'],ROOT.FILES.'/'.$nf);
			$data['orig_file']=$nf;
		}
		
		$ffmpeg=new ffmpeg($settings['ffmpeg_path'],ROOT.FILES,$settings['watermark_path']);
		$oldfile=$data['filename'];
		$oldframe=$data['frame'];
		
		
		$pathparts=pathinfo($data['orig_file']);
		$ext=!empty($pathparts['extension'])?$pathparts['extension']:'';
		
		//$fname=$pathparts['dirname']!='.'?$pathparts['dirname'].'/'.$pathparts['filename']:$pathparts['filename'];
		$fname = '';

		$px=(int)rand(1,1000000);
		if($ffmpeg->convert($data['orig_file']
		  ,$fname.$px.'_c.flv'
		  ,$settings['ffmpeg_size']
		  ,$settings['ffmpeg_bitrate']
		  ,$settings['ffmpeg_ar']
		  ,$settings['skip_flv_conversion']=='1'
		  ,$settings['skip_mp4_conversion']=='1'
		  ,$settings['watermark']=='1'
		  )){
			
		/*  
			var_dump($pathparts);
		  	var_dump($data);
		  	var_dump($settings);
		  	exit();
		*/  	
			
			$images=array('gif','jpg','jpeg','png');
			if(!in_array($ext,$images)){
				$data['filename']=$fname.$px."_c.flv";
				$data['ext']="flv";
				//get video duration and calculate the time of a frame to capture
				$video_info=$ffmpeg->get_info($data['filename']);
				$ss=(int)($video_info['seconds']*0.5);
				$h=$video_info['duration'][0]!='00'?$video_info['duration'][0].':':'';
				/*if (DAO::connect()) echo "<script>alert('Se ha Reabierto la Conexion a BD')</script>";*/									
				$video->setDuration($h.$video_info['duration'][1].":".$video_info['duration'][2]);
			}
			
			//capture a frame
			if($ffmpeg->create_thumbnail($data['filename'],$fname.$px."_c.jpg",$ss,$settings['ffmpeg_size'],ROOT.FILES)){
				$frame=$fname.$px."_c.jpg";
				@unlink(ROOT.FILES.'/'.$oldframe);
			}else{
				$frame="";
			}
			
			if($oldfile!=$data['orig_file'])@unlink(ROOT.FILES.'/'.$oldfile);

			if (DAO::connect()) echo "<script>alert('Se ha Reabierto la Conexion a BD')</script>";	
			$video->setFilename($data['filename']);
			$video->setType($data['ext']);
			
			if($pathparts['extension'] == 'mp4'){
				$filenameHD = str_replace('.flv','.mp4', $data["filename"]);
				$video->setFileNameHD($filenameHD);
		  	}
			/*if (DAO::connect()) echo "<script>alert('Se ha Reabierto la Conexion a BD')</script>";*/	
			if(!empty($frame))$video->setFrame($frame);
			$video->update();
			exit('The video has been converted.');
		}else{
			exit('An error occured while trying to convert the video.');
		}
	}else{
		exit('An error occured while trying to convert the video.<br />
		Please note that this feature only works for videos hosted on this server.');
	}
}
?>