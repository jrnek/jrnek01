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
		$this->request->Init($this->config['base_url'], $this->config['routing']);
		
		$controller = $this->request->controller;
		$method = $this->request->method;
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
		//$themeName = $this->config['theme']['name'];
		//$themePath = JRNEK_INSTALL_PATH . "/themes/{$themeName}";
		//$themeUrl = $this->request->base_url . "themes/{$themeName}";
		$themePath  = JRNEK_INSTALL_PATH . '/' . $this->config['theme']['path'];
		$themeUrl   = $this->request->base_url . $this->config['theme']['path'];
		
		$parentPath = null;
		$parentUrl = null;
		if(isset($this->config['theme']['parent'])) {
			$parentPath = JRNEK_INSTALL_PATH . '/' . $this->config['theme']['parent'];
			$parentUrl  = $this->request->base_url . $this->config['theme']['parent'];
		}
		
		//$this->data['stylesheet'] = "{$themeUrl}/{$this->config['theme']['stylesheet']}";
		$this->data['stylesheet'] = $this->config['theme']['stylesheet'];
		
		$this->themeUrl = $themeUrl;
		$this->themeParentUrl = $parentUrl;
		
		if(is_array($this->config['theme']['menu_to_region'])) {
			foreach($this->config['theme']['menu_to_region'] as $key => $val) {
				$this->views->AddString($this->DrawMenu($key), null, $val);
			}
		}
		
		$jr = &$this;
		include(JRNEK_INSTALL_PATH . '/themes/functions.php');
		if($parentPath) {
			if(is_file("{$parentPath}/functions.php")) {
				include "{$parentPath}/functions.php";
			}
		}
		if(is_file("{$themePath}/functions.php")) {
			include "{$themePath}/functions.php";
		}
		extract($this->data);
		extract($this->views->GetData());
		if(isset($this->config['theme']['data'])) {
			extract($this->config['theme']['data']);
		}
		$templateFile = isset($this->config['theme']['template_file']) ? $this->config['theme']['template_file'] : 'default.tpl.php';
		if(is_file("{$themePath}/{$templateFile}")) {
			include("{$themePath}/{$templateFile}");
		} else if(is_file("{$parentPath}/{$templateFile}")) {
			include("{$parentPath}/{$templateFile}");
		} else {
			throw new Exception('No such template file.');
		}

	}

	public function DrawMenu($menu) {
		$html = null;
		if(isset($this->config['menus'][$menu])) {
			foreach($this->config['menus'][$menu] as $key => $val) {
				$active = null;
				if($val == $this->request->controller) {
					$active = " id='active'";
				}
				$html .= "<a {$active} href='". $this->request->CreateUrl($val) . "'>". ucfirst($key) . "</a>";
			}
		} else {
			throw new Exception('No such menu.');
		}     
		return $html;
	}
	
}