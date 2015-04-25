<?php

class URL
{
	$r = explode('/', $_SERVER['REQUEST_URI'];
	
	$a = $r[1];
	$p = $r[2];
	$o = array_slice($r, 3);
}

?>