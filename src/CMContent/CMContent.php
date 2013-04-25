<?php

class CMContent extends CObject implements IHasSQL, ArrayAccess {

	public $data = array();

	public function __construct($id = null) {
		parent::__construct();
		if(is_numeric($id)) {
			$this->LoadById($id);
		} else {
			$this->data = array();
		}

	}
	
	//Array Access
	public function offsetSet($offset, $value) { if (is_null($offset)) { $this->data[] = $value; } else { $this->data[$offset] = $value; }}
	public function offsetExists($offset) { return isset($this->data[$offset]); }
	public function offsetUnset($offset) { unset($this->data[$offset]); }
	public function offsetGet($offset) { return isset($this->data[$offset]) ? $this->data[$offset] : null; }

	public static function SQL($key=null, $args=array()) {
		$order = isset($args['order']) ? $args['order'] : 'title';
		$orderOrder = isset($args['order-order']) ? $args['order-order'] : 'DESC';
		
		$queries = array(
			'create table content'  => "CREATE TABLE IF NOT EXISTS Content 
									(
									  id INTEGER PRIMARY KEY, 
									  key TEXT KEY, 
									  type TEXT, 
									  title TEXT, 
									  data TEXT, 
									  idUser INT,
									  filter TEXT,
									  created DATETIME default (datetime('now')), 
									  updated DATETIME default NULL,
									  deleted DATETIME default NULL, 
									  FOREIGN KEY(idUser) REFERENCES User(id)
									);",
			'drop table content' => "DROP TABLE IF EXISTS Content",
			'insert to content' => "INSERT INTO Content (key, type, title, data, iduser, filter) Values(?,?,?,?,?,?);",
			'update content' => "UPDATE Content SET key=?, type=?, title=?, data=?, filter=?, updated=datetime('now') WHERE id=?;",
			'select * by id' => 'SELECT c.*, u.acronym as owner FROM Content AS c INNER JOIN User as u ON c.idUser=u.id WHERE c.id=?;',
			'select * by key' => 'SELECT c.*, u.acronym as owner FROM Content AS c INNER JOIN User as u ON c.idUser=u.id WHERE c.key=?;',
			'select *' => 'SELECT c.*, u.acronym as owner FROM Content AS c INNER JOIN User as u ON c.idUser=u.id;',
			'select * where' => "SELECT c.*, u.acronym as owner FROM Content AS c INNER JOIN User as u ON c.idUser=u.id WHERE type=? ORDER BY {$order} {$orderOrder};",
		);
		if(!isset($queries[$key])) {
			throw new Exception("No such SQL query, key '$key' was not found.");
		}
		return $queries[$key]; 
   	}
	
	public function Init() {
		try{ 
			$this->db->ExecuteQuery(self::SQL('drop table content'));
			$this->db->ExecuteQuery(self::SQL('create table content'));
			$this->db->ExecuteQuery(self::SQL('insert to content'), array('hello-world', 'post', 'Hello World!', 'Test post!', 1, 'plain'));
			$this->db->ExecuteQuery(self::SQL('insert to content'), array('test', 'post', 'Testing', 'Testing testing!', 2, 'plain'));
			$this->session->AddMessage('info', 'Successfully loaded database and created table content');
		} catch (Exception $e) {
			die("Something went wrong when trying to initiate database table Content\n" . $e);
		}
	}
	
	public function Save() {
		$msg = null;
		if($this['id']) {
			$this->db->ExecuteQuery(self::SQL('update content'), array($this['key'], $this['type'], $this['title'], $this['data'], $this['filter'], $this['id']));
			$msg = 'update';
		} else {
			$this->db->ExecuteQuery(self::SQL('insert to content'), array($this['key'], $this['type'], $this['title'], $this['data'], $this->user['id'], $this['filter']));
			$this['id'] = $this->db->LastInsertId();
			$msg = 'created';
		}
		$rowcount = $this->db->RowCount();
		if($rowcount) {
			$this->session->AddMessage('success', "Successfully {$msg} content '{$this['key']}'.");
		} else {
			$this->session->AddMessage('error', "Failed to {$msg} content '{$this['key']}'.");
		}
		return $rowcount === 1;
	}
	
	public function LoadById($id) {
		$content = $this->db->ExecuteAndFetch(self::SQL('select * by id'), array($id));
		if(empty($content)) {
			$this->session->AddMessage('error', "Cannot load content with id: '$id'");	
			return false;
		} else {
			$this->data = $content[0];	
			return true;
		}		
	}
	
	public function ListAll($sort = array()) {
		$sortType = isset($sort['type']) ? $sort['type'] : 'post';
		if(empty($sort)) {
			return $this->db->ExecuteAndFetch(self::SQL('select *'));
		}
		else {
			return $this->db->ExecuteAndFetch(self::SQL('select * where', $sort), array($sortType));
		}

	}
	
	public function GetFiltredData() {
		return $this->Filter($this['data'], $this['filter']);
	}
	
	public static function Filter($data, $filter) {
		switch($filter) {
			case 'php': $data = nl2br(makeClickable(eval('?>'.$data))); break;
			case 'html':
			case 'htmlpurify': $data = nl2br(CHTMLPurifier::Purify($data)); break;
			case 'bbcode': $data = nl2br(bbcode2html(htmlEnt($data))); break;
			case 'plain': 
			default: $data = nl2br(makeClickable(htmlent($data))); break;
		}
		return $data;
	}
}
	