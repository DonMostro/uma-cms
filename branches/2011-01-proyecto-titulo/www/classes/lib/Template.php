<?php
include_once('root.php');
include_once(ROOT."classes/lib/Debug.php");


/**
 * Motor de templates. Uso:
 * 
 * $tpl=new Template('template.html'); //o $tpl=new Template('<html><!-- html code --></html>');
 * $tpl->tag='content';
 * echo $tpl->output();
 * 
 *
 */
class Template{
    private $html;
    private $output;
    //private $lang;
    
    /**
     * Constructor
     * @param $page
     * @param $failsafe
     */
    
    function Template($page){
      $this->output="";
      if(@is_readable(ROOT.'templates/'.$page)){
      	$page=ROOT.'templates/'.$page;
      }
      if(strlen($page)<128&&@is_readable($page)&&!@is_dir($page)){
      	$this->html=file_get_contents($page);
      }else{
      	$this->html=$page;
      }
    }
    
    /**
     * Retorna el template parseado
     * @return string HTML
     */
    
    public function output(){
    	if(strpos($this->html,'<#')!==false){
			$this->_parse(0,strlen($this->html)-1);
    	}else{
    		$this->_parseBracket(0,strlen($this->html)-1);
    	}
		return $this->output;
    }
    
    /**
     * Parsea el template entre tags <#var/> 
     * @param $start
     * @param $end
     */
    
    private function _parse($start, $end){
    	//$len=$end-$start+1;
	    $pos=$start; //la posici�n actual en la cadena
	    
    	while((false !== ($tag_start=strpos($this->html,"<#",$pos))) && $tag_start<$end){
    		
    		$this->output.=substr($this->html,$pos,$tag_start-$pos);
    		
    		$tag_end=strpos($this->html,"/>",$tag_start+2);
    		$tag_name=substr($this->html,$tag_start+2,$tag_end-$tag_start-2);
    		
    		if(substr($tag_name,0,3)=='if '){
    			$condition=substr($tag_name,3); //expresi�n condicional
    			list($var,$val)=explode('==',$condition);
    			$val=trim($val,'"');
    			
    			$else=strpos($this->html,'<#else/>',$tag_end+2); //bloque else
    			$end_if=strpos($this->html,'<#endif/>',$tag_end+2); //bloque end if
    			
    			
    			if(isset($this->$var) && $this->$var==$val){ //La condicion a evaluar es true
    				$start_block=$tag_end+2;
    				if($else!==false)$end_block=$else-1;
    				else $end_block=$end_if-1;
    				$this->_parse($start_block,$end_block); //parse the if block
    			}elseif($else!==false){
    				$start_block=$else+8;
    				$end_block=$end_if-1;
    				$this->_parse($start_block,$end_block); //parse the else block
    			}
    			$pos=$end_if+9;
    		}else{
    			if(isset($this->$tag_name))$this->output.=$this->$tag_name;
    			/*
    			elseif(!empty($this->lang)){ //Reemplazar cadenas de idioma
    				$text=$this->lang->getText($tag_name);
    				if(!empty($text)){
    					if(strpos($text,'<#')!==false){
    						$tpl=new Template($text);
    						$default=array_keys(get_class_vars('Template'));
    						foreach (get_object_vars($this) as $key=>$val){
    							if(!in_array($key,$default))$tpl->$key=$val;
    						}
    						$text=$tpl->output();
    					}
    					$this->output.=$text;
    				}
    			}
    			*/
    			$pos=$tag_end+2;
    		}
    		
    	}
    	$this->output.=substr($this->html,$pos,$end-$pos+1);
    }
    
    /**
     * Parsea el template entre tags [:var:]
     * @param $start
     * @param $end
     */

    private function _parseBracket($start, $end){
    	//$len=$end-$start+1;
	    $pos=$start; //la posici�n actual en la cadena
	    
    	while((false !== ($tag_start=strpos($this->html,"[:",$pos))) && $tag_start<$end){
    		
    		$this->output.=substr($this->html,$pos,$tag_start-$pos);
    		
    		$tag_end=strpos($this->html,":]",$tag_start+2);
    		$tag_name=substr($this->html,$tag_start+2,$tag_end-$tag_start-2);
    		
    		if(substr($tag_name,0,3)=='if '){
    			$condition=substr($tag_name,3); //La expresi�n condicional
    			list($var,$val)=explode('==',$condition);
    			if(empty($condition) || empty($var) || empty($val))Debug::write("$var,$val,$condition");
    			$val=trim($val,'"');
    			
    			$else=strpos($this->html,'[:else:]',$tag_end+2); //find the else block
    			$end_if=strpos($this->html,'[:endif:]',$tag_end+2); //find the end of the if block
    			
    			if($else>$end_if)$else=false;
    			
    			if(isset($this->$var) && $this->$var==$val){ //La condicion a evaluar es true
    				$start_block=$tag_end+2;
    				if($else!==false)$end_block=$else-1;
    				else $end_block=$end_if-1;
    				$this->_parseBracket($start_block,$end_block); //parse the if block
    			}elseif($else!==false){
    				$start_block=$else+8;
    				$end_block=$end_if-1;
    				$this->_parseBracket($start_block,$end_block); //parse the else block
    			}
    			$pos=$end_if+9;
    		}else{
    			if(isset($this->$tag_name))$this->output.=$this->$tag_name;
    			/*elseif(!empty($this->lang)){ //Reemplazar cadenas de idioma
    				$text=$this->lang->getText($tag_name);
    				if(!empty($text)&&$text!=$tag_name){
    				//if(!empty($text)){
    					if(strpos($text,'[:')!==false){
    						$tpl=new Template($text);
    						$default=array_keys(get_class_vars('Template'));
    						foreach (get_object_vars($this) as $key=>$val){
    							if(!in_array($key,$default)){
    								$tpl->$key=$val;
    								//Debug::write('template[138]:'.$key.'='.$val);
    							}
    						}
    						$text=$tpl->output();
    					}
    					$this->output.=$text;
    				}
    			}*/
    			$pos=$tag_end+2;
    		}
    		
    	}
    	$this->output.=substr($this->html,$pos,$end-$pos+1);
    }
    
    /**
     * Busca a $needle en $haystack y retorna la posici&oacute;n
     * @param $haystack
     * @param $needle
     * @param $offset
     * @return int posici&oacute;n
     */
    
    private function _strrpos($haystack, $needle, $offset=0){
    	$last=false;
    	$pos=$offset;
    	while(false !== ($pos=strpos($haystack,$needle,$pos)) ){
    		$last=$pos;
    		$pos++;
    	}
    	return $last;
    }
    
}
?>
