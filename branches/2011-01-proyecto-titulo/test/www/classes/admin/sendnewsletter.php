<?php

include_once("root.php");

include_once(ROOT."config.php");

include_once(ROOT."classes/lib/Form.php");

include_once(ROOT."classes/lib/Settings.php");

include_once(ROOT."classes/lib/BatchMail.php");

include_once(ROOT."classes/models/MNewsletters.php");

include_once(ROOT."classes/models/MStats.php");

include_once(ROOT."classes/models/MUser.php");



class SendNewsletter{

  

  private $page;

  

  function SendNewsletter($page){

    $this->page=$page;

  }

  

  public function display(){

	$form=new Form();

	$out='<h2>Send Newsletter</h2>';

	if(!empty($form->id)){

		$nl=new MNewsletters();

		$nl->setId($form->id);

		$nl->load();

		$data=$nl->next();

		$tags=trim($data['tags']);

		if(empty($_POST)){

			$out.='<form action="index.php?p=send_newsletter&amp;id='.$data['id'].'" method="post">

			<input type="hidden" name="send" value="1" />';

			if(!empty($tags)){

				$out.='<p>Send newsletter <b>'.$data['subject'].'</b> to the users who watch videos tagged: <b>'.$tags.'.</b></p>';

			}else{

				$out.='<p>Send newsletter <b>'.$data['subject'].'</b> to all users.</p>';

			}

			$out.='<p><input type="submit" onclick="this.disabled=true; return true;" value="Send Newsletter" /></p></form>';

		}else{

			if(!empty($tags)){ //send to those who watch tagged videos

				$stats=new MStats();

				$stats->setTags($tags);

			}else{             //send to all

				$stats=new MUser();

			}

			if($data['active']=='1')$stats->setActive(1);

			if($data['banned']=='1')$stats->setBanned(0);

			$stats->load();

			$out.='<p><a href="index.php?p=newsletters">Go back</a></p>';

			

			$s=Settings::getInstance();

			$settings=$s->getSettings();

			if($settings['email_queue']=='1'){

				$mail=new BatchMail();

			}else{

				$mail=new Mail();

			}

			

			$mail->setSubject($data['subject']);

	    	$mail->setBody(html_entity_decode($data['body']));

	    	$mail->setSender(EMAIL);

			while($user=$stats->next()){

				if(!empty($user['email'])){

					//Debug::write('email sent to '.$user['email'].' from '.EMAIL.' with subject '.$data['subject'].' containing message:'.html_entity_decode($data['body']));

	    			$mail->setTo($user['email']);

					//mail($user['email'],$data['subject'],html_entity_decode($data['body']),'From: '.EMAIL);

					$out.='Newsletter queued to <a href="index.php?p=users&amp;username='.$user['username'].'">'.$user['username'].'</a><br />';

				}

			}

			$mail->send();

			

			$out.="<p>The newsletter has been sent.</p>";

			

			$nl->setSent(1);

			$nl->update();

			

			$out.='<p><a href="index.php?p=newsletters">Go back</a></p>';

		}

	}

	return $out;

  }



  

}





?>