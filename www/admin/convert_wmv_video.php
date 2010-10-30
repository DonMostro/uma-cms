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
		$year_month_folder = date('Y/m');
		$nf=preg_replace('#[^\w/\.]#','_',$data['orig_file']);
		if($nf!=$data['orig_file']){
			@copy(ROOT.FILES.'/'.$data['orig_file'],ROOT.SMALL_VIDEOS.'/'.$year_month_folder.'/'.$nf);
			$data['orig_file']=$nf;
		}
		
		
		$ffmpeg=new ffmpeg($settings['ffmpeg_path'],ROOT.SMALL_VIDEOS.'/'.$year_month_folder,$settings['watermark_path']);
		$oldfile=$data['filename'];
		$oldframe=$data['frame'];
		$pathparts=pathinfo($data['orig_file']);
		$ext=!empty($pathparts['extension'])?$pathparts['extension']:'';
		$fname=$pathparts['dirname']!='.'?$pathparts['dirname'].'/'.@$pathparts['filename_wmv']:@$pathparts['filename_wmv'];
		$px=(int)rand(1,1000000);

		if($ffmpeg->wmv_convert(ROOT.FILES.'/'.$data['orig_file']
		  ,ROOT.SMALL_VIDEOS.'/'.$year_month_folder.'/'.$fname.$px.'_c.wmv'
		  ,"800x600"
		  ,$settings['ffmpeg_bitrate']
		  ,$settings['ffmpeg_ar']
		  ,$settings['skip_flv_conversion']=='1'
		  ,true
		  )){
			
			$images=array('gif','jpg','jpeg','png');
			if(!in_array($ext,$images)){
				$data['filename_wmv']=$fname.$px."_c.wmv";
				$data['ext']="flv";
				//get video duration and calculate the time of a frame to capture
				$video_info=$ffmpeg->get_info($data['filename']);
				$ss=(int)($video_info['seconds']*0.5);
				$h=$video_info['duration'][0]!='00'?$video_info['duration'][0].':':'';
		//		$video->setDuration($h.$video_info['duration'][1].":".$video_info['duration'][2]);
			}
			
			
			if($oldfile!=$data['orig_file'])@unlink(ROOT.SMALL_VIDEOS.'/'.$oldfile);

			$video->setFileNameWMV($year_month_folder.'/'.$data['filename_wmv']);			
			$video->setFilename($data['filename']);
			$video->setType($data['ext']);
			if(!empty($frame))$video->setFrame($frame);
			$video->update();
			echo 'El video ha sido convertido.';
		}else{
			echo 'Ha ocurrido un error mientras se convert&iacute;a el video.';
		}
	}else{
		echo 'Ha ocurrido un error mientras se convert&iacute;a el video.<br />
		Por favor note que esta funcionalidad trabaja solo con videos hospedados en este servidor.';
	}
}
?>