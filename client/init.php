<?php
	// session_start(); // Requirement for server to store data for each user		
	// Determine the folder for the project withing htdocs
	$path = getcwd();
	$path = preg_replace('/^.+\\\\htdocs\\\\/', '/', $path);
	$path = str_replace('\\', '/', $path);
	// From this define a constant
	define("BASE", $path);
?>