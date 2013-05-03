<?php

	error_reporting(-1);
	ini_set('display_errors', 1);
	
	$jr->config['session_name'] = preg_replace('/[:\.\/-_]/', '', $_SERVER["SERVER_NAME"]);
	$jr->config['timezone'] = 'Europe/Stockholm';
	$jr->config['charEncode'] = 'UTF-8';
	$jr->config['lang'] = 'en';
	
	/**
	* Define all available controllers
	*
	* Define all the controllers and what class they represent.
	*/
	$jr->config['controllers'] = array(
		'index' => array('enabled' => true, 'class' =>  'CCIndex'), 
		'developer' => array('enabled' => true,'class' => 'CCDeveloper'),
		'guestbook' => array('enabled' => true, 'class' => 'CCGuestbook'),
		'user' => array('enabled' => true, 'class' => 'CCUser'),
		'acp' => array('enabled' => true, 'class' => 'CCAdminControlPanel'),
		'content' => array('enabled' => true, 'class' => 'CCContent'),
		'blog' => array('enabled' => true, 'class' => 'CCBlog'),
		'page' => array('enabled' => true, 'class' => 'CCPage'),
		'theme' => array('enabled' => true, 'class' => 'CCTheme'),
		'modules' => array('enabled' => true, 'class' => 'CCModules'),
	);
	
	/**
	* Define a routing table for urls.
	*
	* Route custom urls to a defined controller/method/arguments
	*/
	$jr->config['routing'] = array(
		'home' => array('enabled' => true, 'url' => 'index/index'),
		'bloggen' => array('enabled' => true, 'url' => 'blog/index'),
		);
	
	/**
	* Define the controllers that shouldnt be displayed in main menu
	*
	* Add the name to the controllers that shouldnt be displayed in
	* main menu
	*/
	$jr->config['exclude_from_menu'] = array('acp');
	
	$jr->config['menus'] = array(
		'navbar' => array(
			'home' => 'index',
			'modules' => 'modules',
			'content' => 'content',
			'guestbook' => 'guestbook',
			'blog' => 'blog',
		),
	);
	
	/**
	* Define the current theme
	*
	* Define the current theme, with stylesheet, regions and basic
	* data.
	*/
	$jr->config['theme'] = array(
		'path' => 'site/themes/mytheme',
		'parent' => 'themes/grid',
		'stylesheet' => 'style.css',
		'template_file' => 'index.tpl.php',
		'regions' => array('navbar', 'flash','featured-first','featured-middle','featured-last',
							'primary','secondary','triptych-first','triptych-middle','triptych-last',
							'footer-one','footer-two','footer-three','footer-four',	'bottom'),
		'menu_to_region' => array('navbar' => 'navbar'),
		'data' => array(
					'logo' => 'logo2.png',
					'logo_width' => 80,
					'logo_height' => 80,
					'favicon' => 'logo2.png',
					'header' => 'jrnek Framework',
					'footer' => 'jrnek &copy; by Felix',
					),
		);
	$jr->config['base_url'] = null;
	
	$jr->config['database'][0]['dsn'] = 'sqlite:' . JRNEK_SITE_PATH . '/data/.ht.sqlite';
	
	$jr->config['session_key'] = 'jrnek';
	$jr->config['session_name'] = 'jrnek';
	
	/**
	* What type of urls should be used?
	* 
	* default      = 0      => index.php/controller/method/arg1/arg2/arg3
	* clean        = 1      => controller/method/arg1/arg2/arg3
	* querystring  = 2      => index.php?q=controller/method/arg1/arg2/arg3
	*/
	$jr->config['url_type'] = 1;
	
	/*
	* Display debuginfo?
	*/
	$jr->config['debug']['show'] = false;
	
	/**
	* Set what information should be displayd in debug
	*/
	$jr->config['debug']['jrnek'] = false;
	$jr->config['debug']['db-num-queries'] = true;
	$jr->config['debug']['db-queries'] = true;
	
	/**
	* Set hashing alogrithm: plain, md5salt, md5, sha1salt, sha1.
	*/
	$jr->config['hashing_algorithm'] = 'sha1salt';
	
	/**
	* Set to true if visitors should be able to create accounts.
	*/
	$jr->config['create_new_users'] = true;
	
	