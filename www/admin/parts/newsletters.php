<?php

include_once 'root.php';

include_once ROOT.'classes/models/MTags.php';

include_once ROOT.'classes/models/MNewsletters.php';

include_once ROOT.'classes/lib/Form.php';



if(!empty($_POST)){

	$form=new Form();

	$tagstr='';

	foreach($form->tags as $tag){

		$tagstr.=trim($tag,' ,').' ';

	}

	$tagstr=trim($tagstr);

	$nl=new MNewsletters();

	$nl->setTags($tagstr);

	$nl->add();

}



$tags=new MTags();

$tags->setTop(2);

$tags->setLimit(100);

$tags->addOrder(new DataOrder('hits'));

$tags->load();



$out.='<h3>Top etiquetas vistos</h3>

<p>Seleccione las etiquetas y crear un boletín informativo. Los miembros que ver vídeos etiquetados con las etiquetas recibirá el boletín de noticias.</p>

<p><form action="index.php?p=newsletters" method="post">

<div style="height:300px;overflow:scroll">';



while($tag=$tags->next()){

	if(!empty($tag['tag'])){

		$out.='<input type="checkbox" name="tags[]" value="'.$tag['tag'].'" />'.$tag['tag'].' ('.$tag['hits'].')<br />';

	}

}



$out.='</div><input type="submit" value="Create Newsletter" /><br /><br /></form></p><br />';

?>