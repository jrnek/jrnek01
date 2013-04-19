<?php

class CFormUserLogin extends CForm {

  public function __construct($object) {
    parent::__construct();
    $this->AddElement(new CFormElementText('acronym'))
         ->AddElement(new CFormElementPassword('password'))
         ->AddElement(new CFormElementSubmit('login', array('callback'=>array($object, 'DoLogin'))));
	
	$this->SetValidation('acronym', array('not_empty'));
	$this->SetValidation('password', array('not_empty'));
  }
  
}