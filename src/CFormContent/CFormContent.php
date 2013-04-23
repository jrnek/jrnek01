<?php

class CFormContent extends CForm {
	
	private $content = array();
	
	public function __construct($content) {
		parent::__construct();
		$this->content = $content;
		
		$mode = (isset($content['id']) ? 'edit' : 'save');
		
		$this->AddElement(new CFormElementHidden('id', array('value' => $content['id'])));
		$this->AddElement(new CFormElementText('title', array('required' => true, 'value' => $content['title'])));
		$this->AddElement(new CFormElementText('key', array('required' => true, 'value' => $content['key'])));
		$this->AddElement(new CFormElementTextarea('data', array('value' => $content['data'])));
		$this->AddElement(new CFormElementText('type', array('value' => $content['type'])));
		$this->AddElement(new CFormElementText('filter', array('value' => $content['filter'])));
		$this->AddElement(new CFormElementSubmit($mode, array('callback'=>array($this, 'DoSave'), 'callback-args'=>array($content))));
		$this->SetValidation('title', array('not_empty'));
		$this->SetValidation('key', array('not_empty'));
	}

	public function DoSave($form, $content) {
		$content['id']    = $form['id']['value'];
		$content['title'] = $form['title']['value'];
		$content['key']   = $form['key']['value'];
		$content['data']  = $form['data']['value'];
		$content['type']  = $form['type']['value'];
		$content['filter'] = $form['filter']['value'];
		return $content->Save();
	}
  	

}