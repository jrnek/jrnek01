<?php

class CCGuestbook extends CObject implements IController{

	private $pageTitle = "jrnek Guestbook";
	//private $pageHeader = "<h1>Guestbook example</h1>";
	private $guestbookModel = null;
	
	public function __construct(){
		parent::__construct();
		$this->guestbookModel = new CMGuestbook();
	}
	
	public function Index() {
		$this->views->SetTitle($this->pageTitle);
		$this->views->AddInclude(__DIR__ . '/index.tpl.php', array(
			'entries' => $this->guestbookModel->ReadAll()), 'primary');			
		$this->views->AddInclude(__DIR__ . '/form.tpl.php', array(
			'action' => $this->request->CreateUrl('', 'handler')), 'secondary');
	}
	
	public function Handler() {
		if(isset($_POST['postEntry'])) {
			$this->guestbookModel->Add(strip_tags($_POST['entry']));
		} elseif(isset($_POST['clear'])) {
			$this->guestbookModel->DeleteAll();
		} elseif(isset($_POST['create'])) {
			$this->guestbookModel->Init();
		}
		$this->RedirectTo($this->request->CreateUrl($this->request->controller));
	}
}