<?php

$jr->data['header'] = '<h1>jrnek Framework</h1>';
$jr->data['footer'] = '<p>&copy; jrnek by Felix 2013</p>';
//$jr->data['footer'] .= '<p><a href="http://www.student.bth.se/~febj13/phpmvc/kmom03	/src/source.php">Source</a>';

/*
function get_debug() {
	$jr = CJrnek::Instance();
	$html = "";
	if($jr->config['debug']) {
		$html .= "<h2>Debuginformation</h2><hr><p>The content of the config array:</p><pre>" . htmlentities(print_r($jr->config, true)) . "</pre>";
		$html .= "<hr><p>The content of the data array:</p><pre>" . htmlentities(print_r($jr->data, true)) . "</pre>";
		$html .= "<hr><p>The content of the request array:</p><pre>" . htmlentities(print_r($jr->request, true)) . "</pre>";
	}

	return $html;
*/
