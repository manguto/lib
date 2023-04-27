<?php
function deb($var, bool $die = true){
	echo "<pre>";
	echo var_dump($var);
	echo "</pre>";
	if($die)
		die();
}