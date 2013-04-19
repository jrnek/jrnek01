<?php

$jr->data['header'] = '<h1>jrnek Framework</h1>';
$jr->data['footer'] = '<p>&copy; jrnek by Felix</p>';

function getMainNav() {
	$jr = CJrnek::Instance();
	$html = <<<EOD
		<a href="{$jr->request->CreateUrl('index')}">Start</a>
		<a href="{$jr->request->CreateUrl('guestbook')}">Guestbook</a>
		<a href="{$jr->request->CreateUrl('developer')}">Developer</a>
		<a href="{$jr->request->CreateUrl('user')}">User</a>
EOD;
	
	return $html;
}