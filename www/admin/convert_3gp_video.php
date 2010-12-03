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
		//Compatibilidad con versiones anteriores de nombres que no se han cambiado correctamente durante importaciones versi�n antigua
		
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
		$fname=$pathparts['dirname']!='.'?$pathparts['dirname'].'/'.$pathparts['filename_3gp']:$pathparts['filename_3gp'];
		$px=(int)rand(1,1000000);

		if($ffmpeg->_3gp_convert(ROOT.FILES.'/'.$data['orig_file']
		  ,ROOT.SMALL_VIDEOS.'/'.$year_month_folder.'/'.$fname.$px.'_c.3gp'
		  ,$settings['ffmpeg_size']
		  ,$settings['ffmpeg_bitrate']
		  ,$settings['ffmpeg_ar']
		  ,$settings['skip_flv_conversion']=='1'
		  ,true
		  )){
			
			$images=array('gif','jpg','jpeg','png');
			if(!in_array($ext,$images)){
				$data['filename_3gp']=$fname.$px."_c.3gp";
				$data['ext']="flv";
				//get duraci�n del v�deo y calcular el tiempo de un marco para captar
				
				$video_info=$ffmpeg->get_info($data['filename']);
				$ss=(int)($video_info['seconds']*0.5);
				$h=$video_info['duration'][0]!='00'?$video_info['duration'][0].':':'';
			}
		
			if($oldfile!=$data['orig_file'])@unlink(ROOT.SMALL_VIDEOS.'/'.$oldfile);

			$video->setFileName3GP($year_month_folder.'/'.$data['filename_3gp']);			
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
@mysql_close();
?>