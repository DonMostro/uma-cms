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
		$ffmpeg=new ffmpeg($settings['ffmpeg_path'], $newpath, @$settings['watermark_path']);
	
		$oldfile=$video_types_data['filename'];
		$oldframe=$video_data['frame'];
		$pathparts=pathinfo($video_data['orig_file']);
		$px=(int)rand(1,1000000);
		$ext=$types_data['extension'];
		$newfile="$px.$ext";

		if($ffmpeg->convert_by_type($types_data['script'], ROOT.FILES.'/'.$video_data['orig_file'], $newfile)){
			$data['filename']=ROOT."$newpath/$newfile";
			
			$video_info=$ffmpeg->get_info(ROOT.FILES.'/'.$video_data['orig_file']);

				
			$ss=(int)($video_info['seconds']*0.5);
			$h=$video_info['duration'][0]!='00'?$video_info['duration'][0].':':'';
			$video->setDuration($h.$video_info['duration'][1].":".$video_info['duration'][2]);
			
			$video_types->setFileName("$newpath/$newfile");
			
			if($ffmpeg->create_thumbnail(ROOT.FILES.'/'.$video_data['orig_file'],$px."_c.jpg",$ss,$settings['ffmpeg_size'],ROOT.FILES)){
				$frame=$px."_c.jpg";
				Debug::write($frame);
				@unlink(ROOT.FILES.'/'.$oldframe);
			}else{
				echo "noframe";
				$frame="";
			}
			
			if(!empty($frame)){
				$video->setFrame($frame);
				$video->update();
			}

			if(!empty($oldfile)){
				unlink(ROOT.$oldfile);
				$video_types->update();
			}else{
				$video_types->add();
			}
			
			echo "<p><a href=\"/index.php?m=filename&id={$video_data['id']}&type={$types_data['title']}\" target=\"_blank\">El video ha sido convertido, haga click ac&aacute; para revisar su video <br/>(es archivo XML).</a></p>";
		}else{
			echo '<p>Ha ocurrido un error mientras se convert&iacute;a el video.</p>';
		}
	}else{
		echo 'Ha ocurrido un error mientras se convert&iacute;a el video.<br />
		Por favor verifique que se haya subido un video y que el script de conversi&oacute;n exista.';
	}
}
?>
