<?php
/**
* Manage and analyse the modules of Jrnek framwork
*/
class CCModules extends CObject implements IController {

	/**
	* Constructor
	*/
	public function __construct() {
		parent::__construct();
	}
	
	/**
	* Index-page
	*/
	public function Index() {
		$modules = new CMModules();
		$controllers = $modules->AvailableControllers();
		$allModules = $modules->ReadAndAnalyse();
		$this->views->SetTitle('Manage Modules');
		$this->views->AddInclude(__DIR__ . '/index.tpl.php', array('controllers' => $controllers), 'primary');
		$this->views->AddInclude(__DIR__ . '/sidebar.tpl.php', array('modules' => $allModules), 'secondary');
	}
	
	/**
	* Install the modules
	*/
	public function Install() {
		$modules = new CMModules();
		$results = $modules->Install();
		$allModules = $modules->ReadAndAnalyse();
		$this->views->SetTitle('Install Modules');
		$this->views->AddInclude(__DIR__ . '/install.tpl.php', array('modules' => $results), 'primary');
		$this->views->AddInclude(__DIR__ . '/sidebar.tpl.php', array('modules' => $allModules), 'secondary');
	}
	
	public function View($module) {
		if(!preg_match('/^C[a-zA-Z]+$/', $module)) {
			throw new Exception('Invalid characters in module name.');
		}
		$modules = new CMModules();
		$controllers = $modules->AvailableControllers();
		$allModules = $modules->ReadAndAnalyse();
		$aModule = $modules->ReadAndAnalyseModule($module);
		$this->views->SetTitle('Manage Modules');
		$this->views->AddInclude(__DIR__ . '/view.tpl.php', array('module' => $aModule), 'primary');
		$this->views->AddInclude(__DIR__ . '/sidebar.tpl.php', array('modules' => $allModules), 'secondary');
	}
}