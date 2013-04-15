<?php

class CObject {

	public $config;
	public $request;
	public $data;
	public $db;
	public $views;
	public $session;	
	
	protected function __construct() {
		$jr = CJrnek::Instance();
		$this->config = &$jr->config;
		$this->request = &$jr->request;
		$this->data = &$jr->data;
		$this->db = &$jr->db;
		$this->views = &$jr->views;
		$this->session = &$jr->session;
	}
	
	protected function RedirectTo($url) {
		$jr = CJrnek::Instance();
		if(isset($jr->config['debug']['db-num-queries']) && $jr->config['debug']['db-num-queries'] && isset($jr->db)) {
		  $this->session->SetFlash('database_numQueries', $this->db->GetNrQueries());
		}    
		if(isset($jr->config['debug']['db-queries']) && $jr->config['debug']['db-queries'] && isset($jr->db)) {
		  $this->session->SetFlash('database_queries', $this->db->GetQueries());
		}    
		if(isset($jr->config['debug']['timer']) && $jr->config['debug']['timer']) {
			$this->session->SetFlash('timer', $jr->timer);
		}    
		$this->session->StoreInSession();
		header('Location: ' . $this->request->CreateUrl($url));
  }
}