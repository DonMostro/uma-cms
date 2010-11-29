<?php

$out.=<<<eos

<form>

	<input type="button" value="Process Queue Now" onclick="document.getElementById('_queue').src='../index.php?m=processqueue'" />

</form>

<iframe id="_queue" name="_queue" width="0" height="0" border="0" src=""></iframe>

eos;

?>