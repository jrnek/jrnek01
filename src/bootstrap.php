<?php 

function __autoload($className) {
	$classFile = "/src/{$className}/{$className}.php";
	$file1 = JRNEK_INSTALL_PATH . $classFile;
	$file2 = JRNEK_SITE_PATH . $classFile;
	
	if(is_file($file1)) {
		require_once($file1);
	} elseif(is_file($file2)) {
		require_once($file2); 
	}
}

function htmlent($str, $flags = ENT_COMPAT) {
	return htmlentities($str, $flags, CJrnek::Instance()->config['charEncode']);
}

function exception_handler($e) {
	echo "jrnek: Uncaught exception: <p>" . $e->getMessage() . "</p>";
	echo "<pre>" . $e->getTraceAsString() . "</pre>";
}
set_exception_handler('exception_handler');