<?php

class CCDeveloper extends CObject implements IController {

	public function __construct() {
		parent::__construct();
	}
	public function Index() {  
		$this->Menu();
	}

	public function Links() {  
		$this->Menu();
		
		//$jr = CJrnek::Instance();
		
		$url = 'developer/links';
		$current = $this->request->CreateUrl($url);

		$this->request->cleanUrl = false;
		$this->request->querystringUrl = false;    
		$default = $this->request->CreateUrl($url);
		
		$this->request->cleanUrl = true;
		$clean = $this->request->CreateUrl($url);    
		
		$this->request->cleanUrl = false;
		$this->request->querystringUrl = true;    
		$querystring = $this->request->CreateUrl($url);
		
		$this->data['main'] .= <<<EOD
			<h2>CRequest::CreateUrl()</h2>
			<p>Here is a list of urls created using above method with various settings. All links should lead to
			this same page.</p>
			<ul>
			<li><a href='$current'>This is the current setting</a>
			<li><a href='$default'>This would be the default url</a>
			<li><a href='$clean'>This should be a clean url</a>
			<li><a href='$querystring'>This should be a querystring like url</a>
			</ul>
			<p>Enables various and flexible url-strategy.</p>
EOD;
	}

	private function Menu() {  
		//$jr = CJrnek::Instance();
		$menu = array('developer', 'developer/index', 'developer/links');

		$html = null;
		foreach($menu as $val) {
			$html .= "<li><a href='" . $this->request->CreateUrl($val) . "'>$val</a>";  
		}

		$this->data['title'] = "The Developer Controller";
		$this->data['main'] = <<<EOD
			<h1>The Developer Controller</h1>
			<p>This is what you can do for now:</p>
			<ul>
			$html
			</ul>
EOD;
  }

	public function DisplayObject() {
		
		$this->Menu();
		$this->data['main'] .= "<h2>Dumping content of CDeveloper";
		$this->data['main'] .= "<p><pre>" . htmlentities(print_r($this, true)) . "</pre></p>";

	}
  
}  