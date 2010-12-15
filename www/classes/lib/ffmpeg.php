<?php
include_once("root.php");
include_once(ROOT."config.php");
include_once(ROOT."classes/lib/Thumbnail.php");
include_once(ROOT."classes/lib/Template.php");

class ffmpeg{
        
        private $path;
        private $wd;
        private $watermark;
        
        function ffmpeg($path, $wd=null, $watermark=''){
                $this->path=$path;
                $this->wd=$wd;
                $this->watermark=$watermark;
        }

        public function Exec($chorizo){
                $log = fopen(ROOT.FILES . '/Whatzup.log', 'a+');
                fwrite( $log, "\n" );
                fwrite( $log, "CLASS: " . __CLASS__  ."\n" );
                fwrite( $log, "FILE: " . __FILE__  ."\n" );
                fwrite( $log, "FUNCTION: " . __FUNCTION__  ."\n" );
                fwrite( $log, "METHOD: " . __METHOD__  ."\n" );
                fwrite( $log, "LINE: " . __LINE__  ."\n" );
                fwrite( $log, 'PATH: '. getcwd() . "\n");
                fwrite( $log, 'COMMAND: '. $chorizo. "\n");
                exec( $chorizo);
				echo "Se ejecut&oacute <b>'".$chorizo."'</b><br/>";
                fclose( $log );
        }

        
        public function get_info($source){
                $random=md5(microtime().rand(0,10));
                $command="\"$this->path\" -i $this->wd/$source 2> $this->wd/$random";

                $this->Exec($command);
                $out=@file_get_contents("$this->wd/$random");
                @unlink("$this->wd/$random");
                
                $preg="/Duration: ([0-9.:]+)/is";
                preg_match($preg,$out,$matches);

                @list($h,$m,$s)=@explode(":",@$matches[@1]);
                $ret['seconds']=$h*3600+$m*60+$s;
                
                $ret['duration']=array($h,$m,substr($s,0,2));
                
                $preg="/Duration: ([0-9.:]+).* ([0-9]+x[0-9]+)[, ]/is";
                preg_match($preg,$out,$matches);
                
                $ret['size']=isset($matches[2])?$matches[2]:"0x0";
                
                return $ret;
        }
        
        public function convert($source, $dest, $size, $br, $ar, $skip=false, $skip_mp4=false, $watermark=false){
			set_time_limit(0);

			$size=explode('x',$size);
			$info=$this->get_info($source);
			
			$origsize=explode('x',$info['size']);
			//$wd=$size[0];
			$wd=640;
			$hg=480;
			
			if($watermark&&!empty($this->watermark)){
					$hg=$wd/$origsize[0]*$origsize[1];
					$wmarkfile=$this->watermark(ROOT.FILES.'/watermark.gif',$wd,$hg);
					$wmark="-vhook '$this->watermark -f $wmarkfile'";
			}else{
					$wmark='';
			}
			
			if(!empty($origsize[0])&&!empty($origsize[1])){
				//$hg=$wd/$origsize[0]*$origsize[1];
				$command="\"$this->path\" -y -i $this->wd/$source $wmark -s {$wd}x{$hg} -b $br -ar $ar $this->wd/$dest 2>> ".ROOT.FILES.'/ffmpeg.log';
			}else{
				$command="\"$this->path\" -y -i $this->wd/$source $wmark -b $br -ar $ar $this->wd/$dest 2>> ".ROOT.FILES.'/ffmpeg.log';
			}

			$pathparts=pathinfo($source);
			$ext=!empty($pathparts['extension'])?$pathparts['extension']:'';
			$images=array('gif','jpg','jpeg','png');
			if(in_array($ext,$images)){
				return true;
			}else{
				if($skip&&$ext=='flv'){
						echo "es flv";
						@copy($this->wd."/".$source,$this->wd.'/'.$dest);
				}else{
						$this->Exec($command);        
						if(/*$skip_mp4&&*/$ext=='mp4'){
								$dest = str_replace('.flv', '.mp4', $dest);
								@copy($this->wd.'/'.$source,$this->wd.'/'.$dest);

						}
						/**/
				}
				if(file_exists("$this->wd/$dest") && filesize("$this->wd/$dest")>0){
						echo "return true";
						return true;
				}else{
						$command="\"$this->path\" -y -i $this->wd/$source $wmark -s {$size[0]}x{$size[1]} -b $br -ar $ar $this->wd/$dest 2>> ".ROOT.FILES.'/ffmpeg.log';
						$this->Exec($command);
						return file_exists("$this->wd/$dest") && filesize("$this->wd/$dest")>0;
				}
		   }
        }
        
        
        public function convert_by_type($script, $orig_file, $dest_file){
        	$tpl=new Template(html_entity_decode($script));
        	$tpl->orig_file=$orig_file;
        	$tpl->dest_file=$dest_file;
        	$script=$tpl->output();
        	echo $script;
        	exit();
        	
        	
        }
        
        
        public function mp4_convert($source, $dest, $size='320x240', $br, $ar, $skip=false, $watermark=false){
                set_time_limit(0);

                $size=explode('x',$size);
                $info=$this->get_info($source);
                $this->wd='';
                $origsize=explode('x',$info['size']);
                $wd=$size[0];
                $wd=320;
                $aux_wd=$this->wd;
                $this->wd=ROOT.FILES;
                if($watermark&&!empty($this->watermark)){
					try{
                        @$hg=$wd/$origsize[0]*$origsize[1];
					}catch(Exception $e){
						echo "*";
					}		
                        $wmarkfile=$this->watermark(ROOT.FILES.'/watermark.gif',($wd / 4),($hg /4));
                        $wmark="-vhook '$this->watermark -f $wmarkfile'";
                        $wmark='';
                }else{
                        $wmark='';
                }
                $this->wd = $aux_wd;
                
               // $command="\"$this->path\" -y -i $source $wmark -s 320x240 -acodec libfaac -ab 128kb -ac 1 -vcodec mpeg4 -b 270kb -r 12 -qmin 8 -qmax 30 -deinterlace  $dest 2>> ".ROOT.FILES.'/ffmpeg.log';
			   // $command="\"$this->path\" -y -i $source $wmark -s 320x240 -acodec libfaac -ab 128kb -ac 1 -vcodec mpeg4 -b 270kb -r 12 -qmin 8 -qmax 30 -deinterlace  $dest 2>> ".ROOT.FILES.'/ffmpeg.log';	
			   //$command="\"$this->path\" -y -i $source $wmark -s 320x240 -acodec libfaac -ab 128kb -ac 1 -vcodec mpeg4 -b 270kb -r 12 -qmin 8 -qmax 30 -deinterlace  $dest 2>> ".ROOT.FILES.'/ffmpeg.log';
			   
				$command="\"$this->path\" -y -i $source $wmark -b 12 -s 320x240 -b 160k -vcodec libx264 -vpre fast -acodec libfaac -ac 1 -ar 44100 -ab 96k -threads 0  $dest 2>> ".ROOT.FILES."/ffmpeg.log";

				if(file_exists("$this->wd/$dest") && filesize("$this->wd/$dest")>0){
					return true;
				}else{
					$this->Exec($command);
					return file_exists("$dest") && filesize("$dest")>0;
				}
        }
        
        
        public function _3gp_convert($source, $dest, $size='176x144', $br, $ar, $skip=false, $watermark=false){
			set_time_limit(0);

			$size=explode('x',$size);
			$info=$this->get_info($source);
			$this->wd='';
			$origsize=explode('x',$info['size']);
			$wd=$size[0];
			$wd=176;
			$aux_wd=$this->wd;
			$this->wd=ROOT.FILES;
			if($watermark&&!empty($this->watermark)){
				try{
					@$hg=$wd/$origsize[0]*$origsize[1];
				}catch(Exception $e){
					echo "*";
				}	
				$wmarkfile=$this->watermark(ROOT.FILES.'/watermark.gif',$wd,$hg);
				$wmark="-vhook '$this->watermark -f $wmarkfile'";
				$wmark='';
			}else{
					$wmark='';
			}
			$this->wd = $aux_wd;
                //$command = "ffmpeg -i $source $wmark -s qcif -vcodec h263 -r 10 -acodec amr_nb -ar 8000 -ac 1 -ab 32  -y  $dest2";
            //$command = "ffmpeg -i $source $wmark -s qcif -vcodec h263 -acodec amr_nb -ar 8000 -ac 1 -ab 12 -y $dest";
            $command = "\"$this->path\" -y  -i $source $wmark -s 176x144 -vcodec mpeg4 -r 12 -b 90kb -acodec libfaac -ac 1 -ab 32kb -ar 8000 $dest";

			if(file_exists("$this->wd/$dest") && filesize("$this->wd/$dest")>0){
					return true;
			}else{
					$this->Exec($command);
					return file_exists("$dest") && filesize("$dest")>0;
					//return file_exists("$this->wd/$dest") && filesize("$this->wd/$dest")>0;
			}
        }
        
		
		public function wmv_convert($source, $dest, $size='800x600', $br, $ar, $skip=false, $watermark=false){
			set_time_limit(0);
	
			$size=explode('x',$size);
			$info=$this->get_info($source);
			$this->wd='';
			$origsize=explode('x',$info['size']);
			$wd=$size[0];
			$hg=$size[1];
			$aux_wd=$this->wd;
			$this->wd=ROOT.FILES;
			if($watermark&&!empty($this->watermark)){
				try{
					@$hg=$wd/$origsize[0]*$origsize[1];
				}catch(Exception $e){
					echo "*";
				}	
				$wmarkfile=$this->watermark(ROOT.FILES.'/watermark.gif',$wd,$hg);
				$wmark="-vhook '$this->watermark -f $wmarkfile'";
				$wmark='';
			}else{
				$wmark='';
			}
			$this->wd = $aux_wd;
			$command = "\"$this->path\" -i $source $wmark -s 1152x768 -b 600kb -vcodec wmv2 -acodec wmav2 -ar 44100 -ab 48000 -ac 1 -y $dest 2> ".ROOT.FILES."/ffmpeg.log";
			
			if(file_exists("$this->wd/$dest") && filesize("$this->wd/$dest")>0){
				return true;
			}else{
				$this->Exec($command);
				return file_exists("$dest") && filesize("$dest")>0;
			}
	    }
		
        
        public function create_thumbnail($source, $dest, $frame, $size, $path){
                $command="\"$this->path\" -y -i $this->wd/$source -s $size -ss $frame -t 1 -f image2 $path/$dest";
                $images=array('gif','jpg','jpeg','png');
                $pathparts=pathinfo($source);
                $ext=!empty($pathparts['extension'])?$pathparts['extension']:'';
                if(in_array($ext,$images)){
                          $size=explode('x',$size);
                          Thumbnail::makeThumb("$this->wd/$source",$size[0],$size[1],"$path/$dest");
                          return true;
                }else{
                        $this->Exec($command);
                        return file_exists("$path/$dest");
                }
        }
        
        private function watermark($wmark, $width, $height){
                $img=@imagecreate($width,$height);
                $background_color = @imagecolorallocate($img, 127, 127, 127);
                @imagefill($img,0,0,$background_color);
                $wimg=@imagecreatefromgif($wmark);
                list($wwd,$whg)=@getimagesize($wmark);
                $x=$width-$wwd-5;
                $y=$height-$whg-5;
                @imagecopy($img,$wimg,$x,$y,0,0,$wwd,$whg);
                Debug::write($wwd.'x'.$whg);
                @imagegif($img,$this->wd.'/watermark0.gif');
                return $this->wd.'/watermark0.gif';
        }
}

?>
