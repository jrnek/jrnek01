<?php
/**
* Model for manage and analyse of Jrnek framwork modules
*/
class CMModules extends CObject {
	
	/**
	* Constructor
	*/
	public function __construct() {
		parent::__construct();
	}
	
	private $jrnekCoreModules = array('CJrnek', 'CDatabase', 'CRequest', 'CViewContainer', 'CSession', 'CObject');
	private $jrnekCMFModules = array('CForm', 'CCPage', 'CCBlog', 'CMUser', 'CCUser', 'CMContent', 'CCContent', 'CFormUserLogin',
									'CFormUserProfile', 'CFormUserCreate', 'CFormContent', 'CHTMLPurifier');

									/**
	* Returns a list of alla the available
	* controllers and methods of jrnek
	*/
	public function AvailableControllers() {
		$controllers = array();
		foreach($this->config['controllers'] as $key => $val) {
			if($val['enabled']) {
				$rc = new ReflectionClass($val['class']);
				$controllers[$key] = array();
				$methods = $rc->getMethods(ReflectionMethod::IS_PUBLIC);
				foreach($methods as $method) {
					if($method->name != '__construct' && $method->name != '__destruct' && $method->name != 'Index') {
						$methodName = mb_strtolower($method->name);
						$controllers[$key][] = $methodName;
					}
				}
				sort($controllers[$key], SORT_LOCALE_STRING);
			}
		}
		ksort($controllers, SORT_LOCALE_STRING);
		return $controllers;
	}
	
	/**
	* Read and analyse all modules.
	*/
	public function ReadAndAnalyse() {
		$src = JRNEK_INSTALL_PATH . '/src';
		if(!$dir = dir($src)) {
			throw new Exception('Could not open the directory.');
		}
		$modules = array();
		while (($module = $dir->read()) !== false) {
			if(is_dir("$src/$module")) {
				if(class_exists($module)) {
					$rc = new ReflectionClass($module);
					$modules[$module]['name'] = $rc->name;
					$modules[$module]['interface'] = $rc->getInterfaceNames();
					$modules[$module]['isController'] = $rc->implementsInterface('IController');
					$modules[$module]['isModel'] = preg_match('/^CM[A-Z]/', $rc->name);
					$modules[$module]['hasSQL'] = $rc->implementsInterface('IHasSQL');
					$modules[$module]['isJrnekCore'] = in_array($rc->name, $this->jrnekCoreModules);
																		
					$modules[$module]['isJrnekCMF'] = in_array($rc->name, $this->jrnekCMFModules);
					$modules[$module]['isManageable'] = $rc->implementsInterface('IModule');
				}
			}
		}
		$dir->close();
		ksort($modules, SORT_LOCALE_STRING);
		return $modules;
	}
	
	/**
	* Install all modules
	*/
	public function Install() {
		$allModules = $this->ReadAndAnalyse();
		uksort($allModules, function($a, $b) {
			return ($a == 'CMUser' ? -1 : ($b == 'CMUser' ? 1 : 0));
		  }
		);
		$installed = array();
		foreach($allModules as $module) {
			if($module['isManageable']) {
				$classname = $module['name'];
				$rc = new ReflectionClass($classname);
				$obj = $rc->newInstance();
				$method = $rc->getMethod('Manage');
				$installed[$classname]['name'] = $classname;
				$installed[$classname]['result'] = $method->invoke($obj, 'install');
			}
		}
		//ksort($installed, SORT_LOCALE_STRING);
		return $installed;
	}
	
	/**
	* Return details of a specific module
	*/
	private function GetDetailsOfModule($module) {
		$details = array();
		if(class_exists($module)) {
			$rc = new ReflectionClass($module);
			$details['name'] = $rc->name;
			$details['filename'] = $rc->getFileName();
			$details['doccomment'] = $rc->getDocComment();
			$details['interface'] = $rc->getInterfaceNames();
			$details['isController'] = $rc->implementsInterface('IController');
			$details['isModel'] = preg_match('/^CM[A-Z]/', $rc->name);
			$details['hasSQL'] = $rc->implementsInterface('IHasSQL');
			$details['isManageable'] = $rc->implementsInterface('IModule');
			$details['isJrnekCore'] = in_array($rc->name, $this->jrnekCoreModules);
			$details['isJrnekCMF'] = in_array($rc->name, $this->jrnekCMFModules);
			$details['publicMethods'] = $rc->getMethods(ReflectionMethod::IS_PUBLIC);
			$details['protectedMethods'] = $rc->getMethods(ReflectionMethod::IS_PROTECTED);
			$details['privateMethods'] = $rc->getMethods(ReflectionMethod::IS_PRIVATE);
			$details['staticMethods'] = $rc->getMethods(ReflectionMethod::IS_STATIC);
		}
		return $details;
	}
	
	/**
	* Get info and details about the methods of a module.
	*/
	private function GetDetailsOfModuleMethods($module) {
		$methods = array();
		if(class_exists($module)) {
			$rc = new ReflectionClass($module);
			$classMethods = $rc->getMethods();
			foreach($classMethods as $val) {
				$methodName = $val->name;
				$rm = $rc->GetMethod($methodName);
				$methods[$methodName]['name'] = $rm->getName();
				$methods[$methodName]['doccomment'] = $rm->getDocComment();
				$methods[$methodName]['startline'] = $rm->getStartLine();
				$methods[$methodName]['endline'] = $rm->getEndLine();
				$methods[$methodName]['isPublic'] = $rm->isPublic();
				$methods[$methodName]['isProtected'] = $rm->isProtected();
				$methods[$methodName]['isPrivate'] = $rm->isPrivate();
				$methods[$methodName]['isStatic'] = $rm->isStatic();
			}
		}
		ksort($methods, SORT_LOCALE_STRING);
		return $methods;
	}
	
	/**
	* Get info and details about a module.
	*/
	public function ReadAndAnalyseModule($module) {
		$details = $this->GetDetailsOfModule($module);
		$details['methods'] = $this->GetDetailsOfModuleMethods($module);
		return $details;
	}
}