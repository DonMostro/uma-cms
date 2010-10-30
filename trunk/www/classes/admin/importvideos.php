<?php

set_time_limit(0);

include_once("root.php");

include_once(ROOT."config.php");

include_once(ROOT."classes/lib/Form.php");

include_once(ROOT."classes/lib/Types.php");

include_once(ROOT."classes/lib/ffmpeg.php");

include_once(ROOT."classes/models/MCategoryList.php");

include_once(ROOT."classes/models/MUser.php");

include_once(ROOT."classes/models/MVideos.php");

include_once(ROOT."classes/models/MUploads.php");

include_once(ROOT."classes/models/MThumbnails.php");

include_once(ROOT."classes/admin/finduser.php");



class ImportVideos{

  

  public $page;

  private $settings;

  private $form;

  

  function ImportVideos($page){

    $this->page=$page;

    

    $settings=Settings::getInstance();

    $this->settings=$settings->getSettings();

    

    $this->form=new Form();

  }

  

  function display(){

	$out="<h1>Import Videos</h1>";

	

    if(!empty($this->form->path)&&!empty($this->form->username[0])&&!empty($this->form->category)&&!empty($this->form->description)&&!empty($this->form->tags)){

    	$files=glob(ROOT.FILES.'/'.$this->form->path.'/*');

    	//Debug::write('importvideos[32]:'.print_r($files,1));

    	$videos=0;

    	foreach ($files as $file){

    		$videos+=$this->_import_file($file);

    	}

    	$out.="$videos video(s) have been imported.";

    }

    

    $cats=new MCategoryList();

    $cats->load();

    $categories='';

    while($c=$cats->next()){

    	$categories.="<option value=\"$c[id]\">$c[title]</option>";

    }

    

    $e=new finduser(true,true,'username','username');

    $users=$e->edit(0,0);

    

    $filesdir=str_replace("\\", "/", realpath(ROOT.FILES));

    

    $out.=<<<EOB

<form action="index.php?p=$this->page" method="post">

	<p>

		Path to the videos:

		<br />

		$filesdir/<input type="text" value="" name="path" style="width:300px;" />

	</p>

	<p>

		Category:

		<br />

		<select name="category" style="width:300px;">

		  $categories

    	</select>

	</p>	

	<p>

		Username:

		<br />

		$users

	</p>

	<p>

		Description:

		<br />

		<textarea name="description" style="width:300px; height:200px;"></textarea>

	</p>

	<p>

		Tags:

		<br />

		<input type="text" name="tags" style="width:300px;" />

	</p>

	<p>

		<input type="submit" value="Import" />

	</p>

</form>

EOB;

    

	return $out;

  }



  function _import_file($filename){

  	$title=str_replace(ROOT.FILES.'/','',$filename);

  	$title=basename($title);

  	$title=substr($title,0,strpos($title,'.'));

  	$newfile=preg_replace('#[^\w/\.]#','_',$filename);

	copy($filename,$newfile);

	if($newfile!=$filename)unlink($filename);

	$filename=$newfile;

  	$filename=str_replace(ROOT.FILES.'/','',$filename);

  	$videosmodel=new MVideos();

  	$videosmodel->setFilename($filename);

  	$videosmodel->load();

  	if($videosmodel->countAll()==0){

	  	$file['filename']=$filename;

	  	list($fname,$ext)=explode(".",$file['filename']);

		$file['ext']=$ext;

		$images=array('gif','jpg','jpeg','png');

		

		if(!file_exists(ROOT.THUMBNAILS.'/'.$this->form->path)){

           $bits=explode('/',$this->form->path);

           $rec='';

           foreach($bits as $bit){

           		$rec=$rec!==''?$rec.='/'.$bit:$bit;

				if(!file_exists(ROOT.THUMBNAILS.'/'.$rec))mkdir(ROOT.THUMBNAILS.'/'.$rec);

				chmod(ROOT.THUMBNAILS.'/'.$rec, 0777);

			}

		}

		if(!in_array($ext,$images)){

		    if(!empty($this->settings['ffmpeg_path'])&&$this->settings['conversion_queue']!='1'){

			  	$ffmpeg=new ffmpeg($this->settings['ffmpeg_path'],ROOT.FILES,$this->settings['watermark_path']);

				if($ffmpeg->convert($file['filename']

				  ,$fname.'_c.flv'

				  ,$this->settings['ffmpeg_size']

				  ,$this->settings['ffmpeg_bitrate']

				  ,$this->settings['ffmpeg_ar']

				  ,$this->settings['skip_flv_conversion']=='1'

				  ,$this->settings['watermark']=='1'

				  )){

					

					

					//convert video to flv

					//unlink(FILES."/".$file['filename']);

					$file['filename']=$fname."_c.flv";

					$file['ext']="flv";

					//get video duration and calculate the time of a frame to capture

					$video_info=$ffmpeg->get_info($file['filename']);

					$ss=(int)($video_info['seconds']*0.5);

					$duration=$video_info['duration'][1].":".$video_info['duration'][2];



					//create a thumbnails

					$thumb_count=(int)$this->settings['ffmpeg_thumbnails'];

					$thumbnail=array();

					for($i=0; $i<$thumb_count; $i++){

						$ss=(int)(($video_info['seconds']/($thumb_count+1))*($i+1));

						if($ffmpeg->create_thumbnail($file['filename'],$fname."_c{$i}.jpg",$ss,$this->settings['ffmpeg_thumbnail_size'],ROOT.THUMBNAILS)){

							$thumbnail[]=$fname."_c{$i}.jpg";

						}

					}

					

					//capture a frame

					$ss=(int)($video_info['seconds']*0.5);

					if($ffmpeg->create_thumbnail($file['filename'],$fname."_c.jpg",$ss,$this->settings['ffmpeg_size'],ROOT.FILES)){

						$frame=$fname."_c.jpg";

					}else{

						$frame="";

					}						

				}

        	}

	        $videosmodel=new MUploads();

	        $videosmodel->setDuration($duration);

	        $videosmodel->setCategories_Id($this->form->category);

			$videosmodel->setDescription($this->form->description);

			$videosmodel->setUsername($this->form->username[0]);

			$videosmodel->setTags($this->form->tags);

			$videosmodel->setTitle($title);

		    $id=$videosmodel->add();

		        

			$videosmodel->setFilename($file['filename']);

			$videosmodel->setOrig_file($filename);

			//if(is_readable(ROOT.FILES.'/'.$filename))$videosmodel->setSize(filesize(ROOT.FILES.'/'.$filename));

			if($this->settings['auto_downloadable']=='1')$videosmodel->setDownloadable(1);

			$videosmodel->setType($file['ext']);

			if(!empty($frame))$videosmodel->setFrame($frame);

			if($this->settings['auto_approve']){

				$videosmodel->setApproved(1);

			}else{

				$videosmodel->setApproved(0);

			}

	

			if($this->settings['conversion_queue']=='1')$videosmodel->setConversion(1);

			$video_id=$videosmodel->upload($id);

			

			//add thumbnail

	        if(!empty($thumbnail)){

	        	$thumb=new MThumbnails();

	        	$thumb->setVideos_id($video_id);

	        	foreach($thumbnail as $t){

	        		$thumb->setFilename($t);

	        		$thumb->add();

	        	}

	        }

			return 1;

		}else{

			return 0;

		}

  	}else{

  		return 0;

  	}

  }

  

}





?>

