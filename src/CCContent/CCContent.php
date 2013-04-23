<?php
	
class CCContent extends CObject implements IController {

	public function __construct() {
		parent::__construct();
	}
	
	public function Index() {
		
		$content = new CMContent();
		$this->views->SetTitle('Content controller');
		$this->views->AddInclude(__DIR__ . '/index.tpl.php', array('content' => $content->ListAll()));
	}
	
	public function Create() {
		$this->Edit();
	}
	
	public function Edit($id = null) {
		$content = new CMContent($id);
		$form = new CFormContent($content);
		//echo "<pre>" . print_r($content, true) . "</pre>";
		
		$status = $form->Check();
		if($status === false) {
			$this->session->AddMessage('notice', 'The form could not be processed.');
			$this->RedirectTo('content/edit', $id);
		} else if($status === true) {
			$this->RedirectTo('content/edit', $content['id']);
		}
		
		$title = ($id == null ? 'Create' : 'Edit');
		$this->views->SetTitle($title);
		$this->views->AddInclude(__DIR__ . '/edit.tpl.php', array(
				'content' => $content,
				'user' => $this->user,
				'form' => $form,
				));
		
	}
	
	public function Init() {
		$content = new CMContent();
		$content->Init();
		$this->RedirectTo('content');
	}
}