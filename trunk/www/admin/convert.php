<?php
ini_set("display_errors","On");
error_reporting(E_ALL);

@session_start();


include_once("root.php");
include_once(ROOT."config.php");
include_once(ROOT."classes/lib/Settings.php");
include_once(ROOT."classes/lib/Form.php");
include_once(ROOT."classes/lib/ffmpeg.php");
include_once(ROOT."classes/models/MVideos.php");
include_once(ROOT."classes/models/MVideoTypes.php");
include_once(ROOT."classes/models/MTypes.php");
include_once(ROOT."classes/admin/auth.php");



DAO::connect();
$form=new Form();

$auth=new Auth();

$auth->authenticate(!empty($form->username)?$form->username:'',!empty($form->password)?$form->password:'');
if(isset($form->videos_id) && isset($form->types_id)){
	$s=Settings::getInstance();
	$settings=$s->getSettings();

	$video=new MVideos();
	$video->setId((int)$form->videos_id);
	$video->load();

	$types=new MTypes();
	$types->setId((int)$form->types_id);
	$types->load();
	
	$video_data=$video->next();
	$types_data=$types->next();

	
	if(!empty($video_data['orig_file']) && !empty($types_data['script'])){
		$video_types = new MVideoTypes();
		$video_types->setVideosId($video_data['id']);
		$video_types->setTypesId($types_data['id']);
		$video_types->load();
		$video_types_data = $video_types->next();
		
		//Carpeta con el año y mes en que se subió el video
		$year_month_folder = date('Y/m');
		$nf=preg_replace('#[^\w/\.]#','_',$video_data['orig_file']);
		
		//buscar si existe la carpeta, si no existe se debe crear 
		/*if(!is_dir(ROOT.FILES.'/'.$year_month_folder.'/')){
			echo "mkdir(".ROOT.FILES.'/'.$year_month_folder."/)";
				mkdir('"'.ROOT.FILES.'/'.$year_month_folder.'/"', 0777, true);
		}*/
			
		copy(ROOT.FILES.'/'.$video_data['orig_file'], ROOT.FILES.'/'.$year_month_folder.'/'.$nf);
		$video_data['orig_file']=$nf;

		//$ffmpeg=new ffmpeg($settings['ffmpeg_path'],ROOT.SMALL_VIDEOS.'/'.$year_month_folder,$settings['watermark_path']);
		$newpath= FILES."/$year_month_folder";
		$ffmpeg=new ffmpeg($settings['ffmpeg_path'], $newpath, $settings['watermark_path']);
	
		$oldfile=$video_types_data['filename'];
		$oldframe=$video_data['frame'];
		$pathparts=pathinfo($video_data['orig_file']);
		$px=(int)rand(1,1000000);
		$ext=$types_data['extension'];
		$newfile="$px.$ext";

		if($ffmpeg->convert_by_type($types_data['script'], ROOT.FILES.'/'.$video_data['orig_file'], $newfile)){
			
			//$images=array('gif','jpg','jpeg','png');
			//if(!in_array($ext,$images)){
				//get duraci�n del v�deo y calcular el tiempo de un marco para captar
				$video_info=$ffmpeg->get_info($video_data['filename']);
				
				$ss=(int)($video_info['seconds']*0.5);
				$h=$video_info['duration'][0]!='00'?$video_info['duration'][0].':':'';
				$video->setDuration($h.$video_info['duration'][1].":".$video_info['duration'][2]);
			//}
			
			$video_types->setFileName("$newpath/$newfile");
			
			
			echo $oldfile;
			if(!empty($oldfile)){
				echo "<p>update</p>";
				unlink(ROOT.$oldfile);
				$video_types->update();
			}else{
				echo "<p>add</p>";
				$video_types->add();
			}
					

			//if(!empty($frame))$video->setFrame($frame);
			
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