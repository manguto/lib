<?php



function deb($var, bool $die=true){
	
	echo "<pre>".var_dump($var)."</pre>";
	
	if($die) die();
	
}