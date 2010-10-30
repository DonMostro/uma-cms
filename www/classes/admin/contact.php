<?php

include_once("root.php");

include_once(ROOT."config.php");

include_once(ROOT."classes/lib/Form.php");

include_once(ROOT."classes/lib/Types.php");

include_once(ROOT."classes/lib/BatchMail.php");

include_once(ROOT."classes/lib/Settings.php");

include_once(ROOT."classes/models/MUser.php");

include_once(ROOT."classes/models/MACLGroups.php");

include_once(ROOT."classes/models/MACLGroupUsers.php");

include_once(ROOT."classes/admin/finduser.php");



class Contact{

  

  var $page;

  

  function Contact($page){

    $this->page=$page;

  }

  

  function display(){

    $form=new Form();

	$users=new MUser();

    

    $out = "<h2>Email members</h2>\r\n";

	

    if(isset($form->email)){

    	if(!empty($form->all)){

	    	$s=Settings::getInstance();

	    	$settings=$s->getSettings();

	    	if($settings['email_queue']=='1'){

	    		$mail=new BatchMail();

	    	}else{

	    		$mail=new Mail();

	    	}

	    	if(!empty($form->groups) || !empty($form->notgroups)){

	    		$users=new MACLGroupUsers();

	    		if(!empty($form->groups)){

	    			if(!is_array($form->groups))$form->groups=array($form->groups);

	    			$users->setACL_groups_id($form->groups);

	    		}

	    		if(!empty($form->notgroups)){

	    			if(!is_array($form->notgroups))$form->groups=array($form->notgroups);

	    			$users->setNotgroup($form->notgroups);

	    		}

	    	}

	    	if(!empty($form->banned))$users->setBanned(0);

	    	if(!empty($form->active))$users->setActive(1);

    		$users->load();

    		$mail->setSubject($form->subject);

    		$mail->setBody($form->message);

    		$mail->setSender(EMAIL);

    		while($row=$users->next()){

    			if(!empty($row['email'])){

	    			//Debug::write("Email sent to: ".$row['email']."\r\nSubject: $form->subject\r\nMsg: $form->message");

	    			$mail->setTo($row['email']);

	    			//mail($row['email'],$form->subject,$form->message,"From: ".EMAIL);

    			}

    		}

    		$mail->send();

    	}else{

    		$mail=new Mail();

    		//Debug::write("Email sent to: ".$form->username[0]."\r\nSubject: $form->subject\r\nMsg: $form->message");

    		$mail->setTo($form->username[0]);

    		$mail->setSubject($form->subject);

    		$mail->setBody(html_entity_decode($form->message));

    		$mail->setSender(EMAIL);

    		$mail->send();

    		//mail($form->username,$form->subject,$form->message,"From: ".EMAIL);



    	}

    	$out.="Message sent.<br /><br />\r\n";

    }

    	

	$out.="<form action=\"index.php?p=$this->page\" method=\"post\">\r\n";

	$out.='<p>Member: ';

	$e=new finduser(true,true,'username','username',isset($form->user_id)?$form->user_id:'');

	$out.=$e->edit(0,0);

	$out.="</p>\r\n";

	$out.='<p>All members: <input type="checkbox" name="all" value="1" onclick="document.getElementById(\'edit0_0\').disabled=this.checked; document.getElementById(\'all\').style.display=this.checked?\'block\':\'none\';" /></p>';

	$out.='<div id="all" style="display:none">';

	$out.='<p>Not banned: <input type="checkbox" name="banned" value="1" /></p>';

	$out.='<p>Active: <input type="checkbox" name="active" value="1" /></p>';

	$out.='<p>Members of: ';

	$products=new MACLGroups();

	$products->load();

	while($p=$products->next()){

		$out.=" <input type=\"checkbox\" name=\"groups[]\" value=\"$p[id]\" /> $p[name]";

	}

	$out.='</p>';

	$products->reset();

	$out.='<p>Not members of: ';

  	while($p=$products->next()){

		$out.=" <input type=\"checkbox\" name=\"notgroups[]\" value=\"$p[id]\" /> $p[name]";

	}

	$out.='</p>';

	$out.='</div>';

	

	$out.="<p>Subject:<br /><input type=\"text\" name=\"subject\" style=\"width:400px\" /></p>\r\n";

	$out.="<p>Message:<br /><textarea style=\"width:400px;height:300px\" name=\"message\"></textarea>\r\n";

	$out.="\r\n</p>\r\n";

	$out.="<input type=\"hidden\" name=\"email\" value=\"email\" />\r\n";

	$out.="<input type=\"submit\" value=\"Send\" /></form>";

	

	return $out;

  }

  

}





?>