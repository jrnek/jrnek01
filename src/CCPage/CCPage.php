<?php

class CCPage extends CObject implements IController {
	
	public function __construct() {
		parent::__construct();
	}
	
	public function Index() {
		$content = new CMContent();
		
		$this->views->SetTitle('Page');
		$this->views->AddInclude(__DIR__ . '/index.tpl.php', array('pages' => $content->ListAll(array('type' => 'page'))));
	}
	
	public function View($id) {
		$content = new CMContent($id);
		$this->views->SetTitle($content['title']);
		$this->views->AddInclude(__DIR__ . '/index.tpl.php', array('content' => $content));
	}
}