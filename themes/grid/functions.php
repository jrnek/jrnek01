<?php

function getMainNav() {
	$jr = CJrnek::Instance();
	$html = <<<EOD
		<a href="{$jr->request->CreateUrl('index')}">Start</a>
		<a href="{$jr->request->CreateUrl('guestbook')}">Guestbook</a>
		<a href="{$jr->request->CreateUrl('developer')}">Developer</a>
		<a href="{$jr->request->CreateUrl('user')}">User</a>
		<a href="{$jr->request->CreateUrl('content')}">Content</a>
		<a href="{$jr->request->CreateURL('blog')}">Blog</a>
		<a href="{$jr->request->CreateURL('page')}">Page</a>
EOD;
	
	return $html;
}