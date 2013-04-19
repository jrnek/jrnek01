<?php

class CCAdminControlPanel extends CObject implements IController {

	public function __construct() {
		parent::__construct();
	}
	
	public function Index() {
		if($this->user->IsAdmin()) {
			$this->views->SetTitle('Admin control panel');
			$this->views->AddInclude(__DIR__ . '/index.tpl.php');
		}
		else {
			$this->session->AddMessage('error', 'You have no access to that page!');
			$this->RedirectTo($this->request->CreateUrl('user'));
		}
		
	}
}