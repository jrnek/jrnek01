<?php

class CFormElement implements ArrayAccess{

	public $attributes;
  
	public function __construct($name, $attributes=array()) {
		$this->attributes = $attributes;    
		$this['name'] = $name;
	}
	
	//ArrayAccess
	public function offsetSet($offset, $value) { if (is_null($offset)) { $this->attributes[] = $value; } else { $this->attributes[$offset] = $value; }}
	public function offsetExists($offset) { return isset($this->attributes[$offset]); }
	public function offsetUnset($offset) { unset($this->attributes[$offset]); }
	public function offsetGet($offset) { return isset($this->attributes[$offset]) ? $this->attributes[$offset] : null; }

	public function GetHTML() {
		$id = isset($this['id']) ? $this['id'] : 'form-element-' . $this['name'];
		$class = isset($this['class']) ? " {$this['class']}" : null;
		$validates = (isset($this['validation-pass']) && $this['validation-pass'] === false) ? ' validation-failed' : null;
		$class = (isset($class) || isset($validates)) ? " class='{$class}{$validates}'" : null;
		$name = " name='{$this['name']}'";
		$label = isset($this['label']) ? ($this['label'] . (isset($this['required']) && $this['required'] ? "<span class='form-element-required'>*</span>" : null)) : null;
		$autofocus = isset($this['autofocus']) && $this['autofocus'] ? " autofocus='autofocus'" : null;    
		$readonly = isset($this['readonly']) && $this['readonly'] ? " readonly='readonly'" : null;    
		$type    = isset($this['type']) ? " type='{$this['type']}'" : null;
		$value    = isset($this['value']) ? " value='{$this['value']}'" : null;
		$onlyValue    = isset($this['value']) ? "{$this['value']}" : null;

		$messages = null;
		if(isset($this['validation_messages'])) {
			$message = null;
			foreach($this['validation_messages'] as $val) {
				$message .= "<li>{$val}</li>\n";
			}
			$messages = "<ul class='validation-message'>\n{$message}</ul>\n";
		}
		
		if($type && $this['type'] == 'submit') {
			return "<p><input id='$id'{$type}{$class}{$name}{$value}{$autofocus}{$readonly} /></p>\n";   
		} elseif($this['type'] == 'textarea') {
			return "<p><label for='$id'>$label</label><br><textarea id='$id'{$type}{$class}{$name}{$autofocus}{$value}{$readonly}>{$onlyValue}</textarea></p>\n"; 
		} else {
			return "<p><label for='$id'>$label</label><br><input id='$id'{$type}{$class}{$name}{$value}{$autofocus}{$readonly} />{$messages}</p>\n";           
		}
	}

	public function UseNameAsDefaultLabel() {
		if(!isset($this['label'])) {
			$this['label'] = ucfirst(strtolower(str_replace(array('-','_'), ' ', $this['name']))).':';
		}
	}

	public function UseNameAsDefaultValue() {
		if(!isset($this['value'])) {
			$this['value'] = ucfirst(strtolower(str_replace(array('-','_'), ' ', $this['name'])));
		}
	}
	public function Validate($rules) {
		$tests = array(
		  'fail' => array(
			'message' => 'Will always fail.', 
			'test' => 'return false;',
		  ),
		  'pass' => array(
			'message' => 'Will always pass.', 
			'test' => 'return true;',
		  ),
		  'not_empty' => array(
			'message' => 'Can not be empty.', 
			'test' => 'return $value != "";',
		  ),
		);
		$pass = true;
		$messages = array();
		$value = $this['value'];
		foreach($rules as $key => $val) {
				$rule = is_numeric($key) ? $val : $key;
				if(!isset($tests[$rule])) throw new Exception('Validation of form element failed, no such validation rule exists.');
				if(eval($tests[$rule]['test']) === false) {
					$messages[] = $tests[$rule]['message'];
					$pass = false;
				}
			}
		if(!empty($messages)) $this['validation_messages'] = $messages;
		return $pass;

	}
}
class CFormElementHidden extends CFormElement {
	
	public function __construct($name, $attributes=array()) {
		parent::__construct($name, $attributes);
		$this['type'] = 'hidden';
	}
}

class CFormElementText extends CFormElement {
	
	public function __construct($name, $attributes=array()) {
		parent::__construct($name, $attributes);
		$this['type'] = 'text';
		$this->UseNameAsDefaultLabel();
	}
}

class CFormElementPassword extends CFormElement {
	
	public function __construct($name, $attributes=array()) { 
		parent::__construct($name, $attributes);
		$this['type'] = 'password';
		$this->UseNameAsDefaultLabel();
	}
}

class CFormElementSubmit extends CFormElement {
	
	public function __construct($name, $attributes=array()) {
		parent::__construct($name, $attributes);
		$this['type'] = 'submit';
		$this->UseNameAsDefaultValue();
	}
}

class CFormElementTextArea extends CFormElement {
	
	public function __construct($name, $attributes=array()) {
		parent::__construct($name, $attributes);
		$this['type'] = 'textarea';
		$this->UseNameAsDefaultLabel();
	}
}


class CForm implements ArrayAccess {

	public $form;     // array with settings for the form
	public $elements; // array with all form elements
  
	public function __construct($form=array(), $elements=array()) {
		$this->form = $form;
		$this->elements = $elements;
	}
	//ArrayAccess
	public function offsetSet($offset, $value) { if (is_null($offset)) { $this->elements[] = $value; } else { $this->elements[$offset] = $value; }}
	public function offsetExists($offset) { return isset($this->elements[$offset]); }
	public function offsetUnset($offset) { unset($this->elements[$offset]); }
	public function offsetGet($offset) { return isset($this->elements[$offset]) ? $this->elements[$offset] : null; }

	public function AddElement($element) {
		$this[$element['name']] = $element;
		return $this;
	}

	public function GetHTML($autoGenerate = true) {
		$id      = isset($this->form['id'])      ? " id='{$this->form['id']}'" : null;
		$class    = isset($this->form['class'])   ? " class='{$this->form['class']}'" : null;
		$name    = isset($this->form['name'])    ? " name='{$this->form['name']}'" : null;
		$action = isset($this->form['action'])  ? " action='{$this->form['action']}'" : null;
		$method = " method='post'";
		$elements = $this->GetHTMLForElements();
		$html = null;
		
		if(!$autoGenerate) {
			$html .= "<form{$id}{$class}{$name}{$action}{$method}>";
		} else {
			$html .= <<< EOD
				\n<form{$id}{$class}{$name}{$action}{$method}>
				<fieldset>
				{$elements}
				</fieldset>
				</form>
EOD;
		}
    
	return $html;
  }

	public function GetHTMLForElements() {
		$html = null;
		foreach($this->elements as $element) {
			$html .= $element->GetHTML();
		}
		return $html;
  }
  
	public function Check() {
		$validates = null;
		$callbackStatus = null;
		$values = array();
		if($_SERVER['REQUEST_METHOD'] == 'POST') {
		  unset($_SESSION['form-failed']);
		  $validates = true;
		  foreach($this->elements as $element) {
			if(isset($_POST[$element['name']])) {
			  $values[$element['name']]['value'] = $element['value'] = $_POST[$element['name']];
			  if(isset($element['validation'])) {
				$element['validation-pass'] = $element->Validate($element['validation']);
				if($element['validation-pass'] === false) {
				  $values[$element['name']] = array('value'=>$element['value'], 'validation-messages'=>$element['validation-messages']);
				  $validates = false;
				}
			  }
			  if(isset($element['callback']) && $validates) {
				if(isset($element['callback-args'])) {
							if(call_user_func_array($element['callback'], array_merge(array($this), $element['callback-args'])) === false) {
							  $callbackStatus = false;
							}
					} else {
				  if(call_user_func($element['callback'], $this) === false) {
							  $callbackStatus = false;
				  }
				}
			  }
			}
		  }
		} else if(isset($_SESSION['form-failed'])) {
		  foreach($_SESSION['form-failed'] as $key => $val) {
			$this[$key]['value'] = $val['value'];
			if(isset($val['validation-messages'])) {
			  $this[$key]['validation-messages'] = $val['validation-messages'];
			  $this[$key]['validation-pass'] = false;
			}
		  }
		  unset($_SESSION['form-failed']);
		}
		if($validates === false || $callbackStatus === false) {
		  $_SESSION['form-failed'] = $values;
		}
		if($callbackStatus === false)
		  return false;
		else 
		  return $validates;
	  }
  
  public function SetValidation($element, $rules) {
	$this[$element]['validation'] = $rules;
	return $this;
  }
  
  
}	