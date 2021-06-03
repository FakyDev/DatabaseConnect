<?php
//To autoload classes
spl_autoload_register(function($className) {

	$className = str_replace("\\", DIRECTORY_SEPARATOR, $className);
	require_once $_SERVER['DOCUMENT_ROOT'] . '/class/' . $className . '.php';

});
?>