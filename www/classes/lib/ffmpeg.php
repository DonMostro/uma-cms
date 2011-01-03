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

        public function exec($script){
        		exec($script);
                Debug::write($script);
        }

        
        public function get_info($source){
                $random=md5(microtime().rand(0,10));
                $command="\"$this->path\" -i $this->wd/$source 2> $this->wd/$random";

                $this->exec($command);
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
        
        
        
        public function convert_by_type($script, $orig_file, $dest_file){
        	$tpl=new Template(html_entity_decode($script));
        	$tpl->ffmpeg_path=$this->path;
        	$tpl->orig_file=$orig_file;
        	$tpl->dest_file=$dest_file;
        	$command=$tpl->output();
         	$this->exec($command);
        	return (file_exists($tpl->dest_file) && filesize($tpl->dest_file)>0);
        }
      
        public function create_thumbnail($source, $dest, $frame, $size, $path){
                $command="\"$this->path\" -y -i $source -s $size -ss $frame -t 1 -f image2 $path/$dest";
                $images=array('gif','jpg','jpeg','png');
                $pathparts=pathinfo($source);
                $ext=!empty($pathparts['extension'])?$pathparts['extension']:'';
                if(in_array($ext,$images)){
                          $size=explode('x',$size);
                          Thumbnail::makeThumb("$this->wd/$source",$size[0],$size[1],"$path/$dest");
                          return true;
                }else{
                        $this->exec($command);
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