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
		//	echo "copy(".ROOT.FILES.'/'.$data['orig_file'].",".ROOT.SMALL_VIDEOS.'/'.$year_month_folder.'/'.$nf.")";
			@copy(ROOT.FILES.'/'.$data['orig_file'],ROOT.SMALL_VIDEOS.'/'.$year_month_folder.'/'.$nf);
			$data['orig_file']=$nf;
		}
		
		//die("ffmpeg_tests(".$settings['ffmpeg_path'].ROOT.SMALL_VIDEOS."$year_month_folder,".$settings['watermark_path'].")");
		
		$ffmpeg=new ffmpeg($settings['ffmpeg_path'],ROOT.SMALL_VIDEOS.'/'.$year_month_folder,$settings['watermark_path']);
		//print_r($data);
		
		$oldfile=$data['small_filename]'];
		$oldframe=$data['frame'];
		$pathparts=pathinfo($data['orig_file']);
		$ext=!empty($pathparts['extension'])?$pathparts['extension']:'';

		
		$fname=$pathparts['dirname']!='.'?$pathparts['dirname'].'/'.$pathparts['small_filename']:$pathparts['small_filename'];

		//print_r($pathparts);
		
		
		$px=(int)rand(1,1000000);
		/*echo "mp4_convert(".ROOT.SMALL_VIDEOS.$year_month_folder.$data['orig_file'].",$fname.$px.".'_c.mp4,'.$settings['ffmpeg_size'].",".$settings['ffmpeg_bitrate']."
		  ,".$settings['ffmpeg_ar'].",".$settings['skip_flv_conversion']. "==1".",".$settings['watermark']. "=='1')<br/>";*/
		
		//die();
		//$ffmpeg->
		
		if($ffmpeg->mp4_convert(ROOT.FILES.'/'.$data['orig_file']
		  ,ROOT.SMALL_VIDEOS.'/'.$year_month_folder.'/'.$fname.$px.'_c.mp4'
		  ,$settings['ffmpeg_size']
		  ,$settings['ffmpeg_bitrate']
		  ,$settings['ffmpeg_ar']
		  ,$settings['skip_flv_conversion']=='1'
		  ,0
		  )){
			
			$images=array('gif','jpg','jpeg','png');
			if(!in_array($ext,$images)){
				$data['small_filename']=$fname.$px."_c.mp4";
				$data['ext']="flv";
				//get video duration and calculate the time of a frame to capture
				$video_info=$ffmpeg->get_info($data['small_filename']);
				$ss=(int)($video_info['seconds']*0.5);
				$h=$video_info['duration'][0]!='00'?$video_info['duration'][0].':':'';
				$video->setDuration($h.$video_info['duration'][1].":".$video_info['duration'][2]);
			}
			
			//capture a frame
		//	if($ffmpeg->create_thumbnail($data['small_filename'],$fname.$px."_c.jpg",$ss,$settings['ffmpeg_size'],ROOT.FILES)){
		//		$frame=$fname.$px."_c.jpg";
		//		@unlink(ROOT.FILES.'/'.$oldframe);
		//	}else{
		//		$frame="";
		//	}
			
			if($oldfile!=$data['orig_file'])@unlink(ROOT.SMALL_VIDEOS.'/'.$oldfile);

			$video->setSmallFileName($year_month_folder.'/'.$data['small_filename']);			
			$video->setFilename($data['filename']);
			$video->setType($data['ext']);
			if(!empty($frame))$video->setFrame($frame);
			$video->update();
			echo 'El video ha sido convertido a mp4.';
		}else{
			echo 'Ha ocurrido un error mientras se convert&iacute;a el video a mp4.<br/>';
		}
	}else{
		echo 'Ha ocurrido un error mientras se convert&iacute;a el video a mp4.<br />
		Por favor note que esta funcionalidad trabaja solo con videos hospedados en este servidor.<br/>';
	}
	
	if($ffmpeg->_3gp_convert(ROOT.FILES.'/'.$data['orig_file']
		  ,ROOT.SMALL_VIDEOS.'/'.$year_month_folder.'/'.$fname.$px.'_c.mp4'
		  ,$settings['ffmpeg_size']
		  ,$settings['ffmpeg_bitrate']
		  ,$settings['ffmpeg_ar']
		  ,$settings['skip_flv_conversion']=='1'
		  ,0
		  ))
	{	  
		echo 'El video ha sido convertido a 3GP <br/>.';
    }else{
    	echo 'Ha ocurrido un error mientras se convert&iacute;a el video a 3GP <br/>.';
    }
}
?>