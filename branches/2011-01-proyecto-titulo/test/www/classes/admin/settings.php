<?php
include_once("root.php");
include_once(ROOT."classes/lib/Form.php");
include_once(ROOT."classes/lib/Types.php");
include_once(ROOT."classes/models/MSettings.php");
include_once(ROOT."classes/admin/test/test_ffmpeg.php");
include_once(ROOT."classes/admin/test/test_maxuploadsize.php");
include_once(ROOT."classes/admin/test/test_red5server.php");
include_once(ROOT."classes/admin/test/test_red5streams.php");
include_once(ROOT."classes/admin/test/clear_cache.php");
include_once(ROOT."classes/admin/test/find_watermark.php");

class ASettings{
  public $page;
  function ASettings($page){
    $this->page=$page;
  }

  function display(){
    $form=new Form();
	$settings=new MSettings();
    $out = "<h2>Settings</h2>\r\n";

    if(isset($form->save)){
    	foreach ($form->id as $i=>$v){
    		$settings->setId($v);
    		$value=isset($form->value[$i])?$form->value[$i]:"";
    		$settings->setValue($value);
    		$settings->update();
    	}
    }

    if(isset($form->f)&&isset($form->v)){
    	if(class_exists($form->f)){
    		$f=new $form->f;
    		$f->setValue($form->v);
    		return $f->run();
    	}
    }

    $out.='<script type="text/javascript">
    function showtab(tab,area){
      var e=document.getElementsByTagName("div");
      for (i=0; i<e.length; i++){
        if(e[i].className=="settings_area")e[i].style.display="none";
      }
      var e=document.getElementsByTagName("a");
      for (i=0; i<e.length; i++){
        if(e[i].className=="settings_tab")e[i].style.backgroundColor="";
      }
      document.getElementById(area).style.display="block";
      tab.style.backgroundColor="#cccccc";
    }
    </script>
    ';
    	

	$out.="<form action=\"index.php?p=$this->page\" method=\"post\">\r\n";

	$settings->loadGroups();
	$groups=array();
	$h='style="background:#cccccc"';
	$i=0;
	while($group=$settings->next()){
		$g=!empty($group['group'])?$group['group']:'General';
		$groups[]=$group['group'];
		$out.='<a class="settings_tab" '.$h.' onclick="showtab(this,\'tab'.++$i.'\')">'.$g.'</a>';
		$h='';
	}
	$out.='<div class="brclear"></div><br />';
	$settings=new MSettings();
	$hidden='';
	$i=0;
	
	foreach($groups as $j=>$group){
		$settings->addOrder(new DataOrder("ord","ASC"));
		$settings->setGroup($group);
	    $settings->load();
	    $out.='<div id="tab'.($j+1).'" class="settings_area" '.$hidden.'>';

		while($row=$settings->next()){
			$out.="<input type=\"hidden\" name=\"id[]\" value=\"$row[id]\" />\r\n";
			$out.="<b>$row[id]:</b> ";
			$e=new $row['type'](true,true,"","value",$row['value']);

			if(!empty($row['enum'])){

				$out.=$this->_select("value[$i]",$row['enum'],$row['value']);

			}else{

				$out.=$e->edit($i,0);

			}

			if(!empty($row['function'])&&class_exists($row['function'])){

				$f=new $row['function'];
				$f->setId($i);
				$out.=$f->display();
			}
			$out.="<br />".nl2br($row['description']);
			$out.="\r\n<br /><br />\r\n";
			$i++;
		}
		$hidden='style="display:none"';
		$out.='</div>';
	}
	$out.="<input type=\"hidden\" name=\"save\" value=\"save\" />\r\n";
	$out.="<input type=\"submit\" value=\"Save\" /></form>";
	return $out;
  }

  function _select($name, $values, $value){
  	$r='<select name="'.$name.'">';
  	foreach(explode(',',$values) as $v){
  		if($value==$v){
  			$r.='<option value="'.$v.'" selected="selected">'.$v.'</option>';
  		}else{
  			$r.='<option value="'.$v.'">'.$v.'</option>';
  		}
  	}
  	$r.='</select>';
  	return $r;
  }
}
?>