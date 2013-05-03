<?php

class CCDeveloper extends CObject implements IController {

	public function __construct() {
		parent::__construct();
	}
	public function Index() {  
		$this->views->SetTitle('Developer');
		$this->views->AddString('<h1>The Developer controller</h1>');
		$this->Menu();
	}

	public function Links() {  
		$this->Menu();
		
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
		
		$content = <<<EOD
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
		$this->views->SetTitle('Developer Links');
		$this->views->AddString($content, array(), 'primary');
	}

	private function Menu() {  
		//$jr = CJrnek::Instance();
		$menu = array('developer', 'developer/index', 'developer/links', 'developer/displayobject');

		$html = null;
		foreach($menu as $val) {
			$html .= "<li><a href='" . $this->request->CreateUrl($val) . "'>$val</a>";  
		}

		$this->views->AddString($html, array(), 'secondary');
  }

	public function DisplayObject() {
		$this->Menu();
		$this->views->SetTitle('Display Object');
		$this->views->AddString("<h2>Dumping content of CDeveloper</h2>");
		$this->views->AddString("<p><pre>" . htmlentities(print_r($this, true)) . "</pre></p>");

	}
  
}  