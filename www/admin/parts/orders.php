<?php

$out.=<<<eos

Quick links:

<a href="index.php?p=orders"><img style="vertical-align:middle" src="images/billing.png" /> Todos los pedidos</a>

<a href="index.php?p=orders&amp;status=0"><img style="vertical-align:middle" src="images/error.png" /> Los pedidos pendientes de aprobación</a>

<a href="index.php?p=orders&amp;status=-1"><img style="vertical-align:middle" src="images/cross.png" />Pedidos cancelados</a>

<a href="index.php?p=orders&amp;status=1"><img style="vertical-align:middle" src="images/tick.png" /> Completado órdenes</a>

eos;

?>