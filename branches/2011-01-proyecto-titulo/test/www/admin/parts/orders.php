<?php

$out.=<<<eos

Quick links:

<a href="index.php?p=orders"><img style="vertical-align:middle" src="images/billing.png" /> All orders</a>

<a href="index.php?p=orders&amp;status=0"><img style="vertical-align:middle" src="images/error.png" /> Orders pending approval</a>

<a href="index.php?p=orders&amp;status=-1"><img style="vertical-align:middle" src="images/cross.png" /> Cancelled orders</a>

<a href="index.php?p=orders&amp;status=1"><img style="vertical-align:middle" src="images/tick.png" /> Completed orders</a>

eos;

?>