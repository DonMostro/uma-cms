<?php
/**
 * Request handler. Besides being a wrapper for $_POST, $_GET and $_FILES, it decodes/encodes the input data.
 * The result values are HTML ready data.
 *
 */
class Form {
  function Form(){
    foreach($_GET as $k=>$v){
        if(is_array($v)){
          $temp=array();
          foreach($v as $k1=>$v1)$temp[$k1]=$this->encode($v1);
          $this->$k=$temp;
        }else $this->$k=$this->encode($v);
    }
    foreach($_POST as $k=>$v){
        if(is_array($v)){
          $temp=array();
          foreach($v as $k1=>$v1)$temp[$k1]=$this->encode($v1);
          $this->$k=$temp;
        }else $this->$k=$this->encode($v);
    }
  }
  
  function isPost() { return count($_POST); }
  function isGet() { return count($_GET); }
  
  function &getInstance(){
  	static $me;
  	if(!$me) {
  		$me=array(new Form());
  	}
  	return $me[0];
  }
  
  function upload($file, $dest, $max_size=999999999999){
  	if(!empty($_FILES[$file]['name'])&&is_array($_FILES[$file]['name'])){
  		$info=array();
  		foreach($_FILES[$file]['name'] as $i=>$f){
		    if($_FILES[$file]['size'][$i]>0 && $_FILES[$file]['size'][$i]<$max_size && substr($_FILES[$file]['name'][$i],-3,3)!='php'){
			  $fp=explode(".",$_FILES[$file]['name'][$i]);
			  $ext=$fp[count($fp)-1];
			  $filename=substr(md5(microtime().$_FILES[$file]['tmp_name'][$i]),0,8).".$ext";
			  @move_uploaded_file($_FILES[$file]['tmp_name'][$i],$dest."/".$filename);
			  $info[$i]['size']=$_FILES[$file]['size'][$i];
			  $info[$i]['filename']=$filename;
			  $info[$i]['ext']=$ext;
			}
	  	}
	  	return $info;
  	}else{
	    if($_FILES[$file]['size']>0 && $_FILES[$file]['size']<$max_size && substr($_FILES[$file]['name'],-3,3)!='php'){
		  $fp=explode(".",$_FILES[$file]['name']);
		  $ext=$fp[count($fp)-1];
		  $filename=substr(md5(microtime().$_FILES[$file]['tmp_name']),0,8).".$ext";
		  @move_uploaded_file($_FILES[$file]['tmp_name'],$dest."/".$filename);
		  $info=$_FILES[$file];
		  $info['filename']=$filename;
		  $info['ext']=$ext;
		  return $info;
		}else return false;
  	}
  }
  
  function encode($value){
    if(get_magic_quotes_gpc()==1)$value=stripslashes($value);
  	if(strlen($value)<1024){
        $value=htmlentities($this->_unescape($value),null,'UTF-8');
    }else{
        $value=htmlentities($value,null,'UTF-8');
    }
    return $value;
  }
  
  function _unescape($source, $iconv_to = 'UTF-8') {
	    $decodedStr = '';
	    $pos = 0;
	    $len = strlen ($source);
	    while ($pos < $len) {
	        $charAt = substr ($source, $pos, 1);
	        if ($charAt == '%') {
	            $pos++;
	            $charAt = substr ($source, $pos, 1);
	            if ($charAt == 'u') {
	                // we got a unicode character
	                $pos++;
	                $unicodeHexVal = substr ($source, $pos, 4);
	                $unicode = hexdec ($unicodeHexVal);
	                $decodedStr .= $this->_code2utf($unicode);
	                $pos += 4;
	            }
	            else {
	                // we have an escaped ascii character
	                $hexVal = substr ($source, $pos, 2);
	                $decodedStr .= chr (hexdec ($hexVal));
	                $pos += 2;
	            }
	        }
	        else {
	            $decodedStr .= $this->_isUTF8($source)?$charAt:utf8_encode($charAt);
	            $pos++;
	        }
	    }
	
	    if ($iconv_to != "UTF-8") {
	        $decodedStr = iconv("UTF-8", $iconv_to, $decodedStr);
	    }
	   
	    return $decodedStr;
	}
	
	function _code2utf($num){
	    if($num<128)return chr($num);
	    if($num<2048)return chr(($num>>6)+192).chr(($num&63)+128);
	    if($num<65536)return chr(($num>>12)+224).chr((($num>>6)&63)+128).chr(($num&63)+128);
	    if($num<2097152)return chr(($num>>18)+240).chr((($num>>12)&63)+128).chr((($num>>6)&63)+128) .chr(($num&63)+128);
	    return '';
	}
	
	function _isUTF8($string) { 
        // from http://w3.org/International/questions/qa-forms-utf-8.html 
        return preg_match('%^( 
                 [\x09\x0A\x0D\x20-\x7E]            # ASCII 
               | [\xC2-\xDF][\x80-\xBF]             # non-overlong 2-byte 
               |  \xE0[\xA0-\xBF][\x80-\xBF]        # excluding overlongs 
               | [\xE1-\xEC\xEE\xEF][\x80-\xBF]{2}  # straight 3-byte 
               |  \xED[\x80-\x9F][\x80-\xBF]        # excluding surrogates 
               |  \xF0[\x90-\xBF][\x80-\xBF]{2}     # planes 1-3 
               | [\xF1-\xF3][\x80-\xBF]{3}          # planes 4-15 
               |  \xF4[\x80-\x8F][\x80-\xBF]{2}     # plane 16 
           )*$%xs', $string); 
    }
}

?>
