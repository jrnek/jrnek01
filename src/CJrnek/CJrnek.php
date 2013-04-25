<?php

class CJrnek implements ISingelton {
	
	private static $instance = null;
	public $config = array();
	public $request;
	public $data;
	public $db;
	public $views;
	public $session;

	public static function Instance() {
		if(self::$instance == null) {
			self::$instance = new CJrnek();
		}
		return self::$instance;
	}
	
	protected function __construct() {
		$jr = &$this;
		require(JRNEK_SITE_PATH.'/config.php'); 
		
		session_name($this->config['session_name']);
		session_start();
		$this->session = new CSession($this->config['session_key']);
		$this->session->PopulateFromSession();
		
		date_default_timezone_set($this->config['timezone']);
		
		if(isset($this->config['database'][0]['dsn'])){
			$this->db = new CMDatabase($this->config['database'][0]['dsn']);
		}
		$this->views = new CViewContainer();
		$this->user = new CMUser($this);
	}

	public function FrontControllerRoute() {
		$this->request = new CRequest($this->config['url_type']);
		$this->request->Init($this->config['base_url']);
		
		$controller = $this->request->controller;
		$method = $this->request->method;
		//$method = str_replace(array('_', '-'), '', $method);
		$arguments = $this->request->arguments;
		
		$controllerExists = isset($this->config['controllers'][$controller]);
		$controllerEnabled = false;
		$className = false;
		$classExists = false;
		
		if($controllerExists) {
			$controllerEnabled = ($this->config['controllers'][$controller]['enabled'] == true);
			$className = $this->config['controllers'][$controller]['class'];
			$classExists = class_exists($className);
		}
		
		if($controllerExists && $controllerEnabled && $classExists) {
			$rc = new ReflectionClass($className);
			if($rc->implementsInterface('IController')) {
				if($rc->hasMethod($method)) {
					$controllerObj = $rc->newInstance();
					$methodObj = $rc->getMethod($method);
					if($methodObj->isPublic()) {
						$methodObj->invokeArgs($controllerObj, $arguments);
					}
				} else {
					die("404. " . get_class() . ' error: Controller does not contain method.');
				}
			} else {
				die('404. ' .get_class() . 'error: Controller does not implement interface.');
			}
		} else {
			$error = "<br />{$controllerExists}<br>{$controllerEnabled}<br>{$classExists}";
			die('404. Page is not found.' . $error );
		}
		
		
	}
	
	public function ThemeEngineRender() {
		$this->session->StoreInSession();
		$themeName = $this->config['theme']['name'];
		$themePath = JRNEK_INSTALL_PATH . "/themes/{$themeName}";
		$themeUrl = $this->request->base_url . "themes/{$themeName}";
		
		$this->data['stylesheet'] = "{$themeUrl}/{$this->config['theme']['stylesheet']}";
		
		$jr = &$this;
		include(JRNEK_INSTALL_PATH . '/themes/functions.php');
		$functionPath = "{$themePath}/functions.php";
		if(is_file($functionPath)) {
			include $functionPath;
		}
		extract($this->data);
		extract($this->views->GetData());
		$templateFile = isset($this->config['theme']['template_file']) ? $this->config['theme']['template_file'] : 'default.tpl.php';
		if(isset($this->config['theme']['data'])) {
			extract($this->config['theme']['data']);
		}
		include("{$themePath}/{$templateFile}");
	}
	
}