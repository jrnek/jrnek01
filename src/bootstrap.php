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

function makeClickable($text) {
  return preg_replace_callback(
    '#\bhttps?://[^\s()<>]+(?:\([\w\d]+\)|([^[:punct:]\s]|/))#', 
    create_function(
      '$matches',
      'return "<a href=\'{$matches[0]}\'>{$matches[0]}</a>";'
    ),
    $text
  );
}

function bbcode2html($text) {
  $search = array( 
    '/\[b\](.*?)\[\/b\]/is', 
    '/\[i\](.*?)\[\/i\]/is', 
    '/\[u\](.*?)\[\/u\]/is', 
    '/\[img\](https?.*?)\[\/img\]/is', 
    '/\[url\](https?.*?)\[\/url\]/is', 
    '/\[url=(https?.*?)\](.*?)\[\/url\]/is' 
    );   
  $replace = array( 
    '<strong>$1</strong>', 
    '<em>$1</em>', 
    '<u>$1</u>', 
    '<img src="$1" />', 
    '<a href="$1">$1</a>', 
    '<a href="$1">$2</a>' 
    );     
  return preg_replace($search, $replace, $text);
}

function htmlent($str, $flags = ENT_COMPAT) {
	return htmlentities($str, $flags, CJrnek::Instance()->config['charEncode']);
}

function exception_handler($e) {
	echo "jrnek: Uncaught exception: <p>" . $e->getMessage() . "</p>";
	echo "<pre>" . $e->getTraceAsString() . "</pre>";
}
set_exception_handler('exception_handler');