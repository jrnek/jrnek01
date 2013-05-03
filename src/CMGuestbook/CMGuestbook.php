<?php

class CMGuestbook extends CObject implements IHasSQL, IModule {

	public function __construct() {
		parent::__construct();
	}
	
	public static function SQL($key=null) {
		$queries = array(
			'create table guestbook'  => "CREATE TABLE IF NOT EXISTS Guestbook (id INTEGER PRIMARY KEY, entry TEXT, created DATETIME default (datetime('now')));",
			'insert into guestbook'   => 'INSERT INTO Guestbook (entry) VALUES (?);',
			'select * from guestbook' => 'SELECT * FROM Guestbook ORDER BY id DESC;',
			'delete from guestbook'   => 'DELETE FROM Guestbook;',
		);
		if(!isset($queries[$key])) {
			throw new Exception("No such SQL query, key '$key' was not found.");
		}
		return $queries[$key];
   	}
	
	public function Manage($action=null) {
		switch($action) {
			case 'install':
				try{
					$this->db->ExecuteQuery(self::SQL('create table guestbook'));
					//$this->session->AddMessage('info', 'Successfully created database table');
					return array('success', 'Successfully created the database tables');
				} catch(Exception $e) {
					$error = "Failed to open database: " . $this->config['database'][0]['dsn'] . "<br/>" . $e;
					$this->session->AddMessage('error', $error);
				}
			break;
			
			default:
				throw new Exception($action .' is not supported from this method');
				break;
		}
	}
	
	public function Init() {
		try{
			$this->db->ExecuteQuery(self::SQL('create table guestbook'));
			$this->session->AddMessage('info', 'Successfully created database table');
		} catch(Exception $e) {
			$error = "Failed to open database: " . $this->config['database'][0]['dsn'] . "<br/>" . $e;
			$this->session->AddMessage('error', $error);
		}
	}
	
	public function Add($entry) {
		$this->db->ExecuteQuery(self::SQL('insert into guestbook'), array($entry));
		if($this->db->rowCount() != 1) {
			$error = "Failed to insert new message" ;
			$this->session->AddMessage('error', $error);
		} else {
			$this->session->AddMessage('info', 'Successfully addes entry to guestbook');
		}
	}
	
	public function DeleteAll() {
		$this->db->ExecuteQuery(self::SQL('delete from guestbook'));
		$this->session->AddMessage('info', 'Removed all messages from the database');
	}
	
	public function ReadAll() {
		try{
			return $this->db->ExecuteAndFetch(self::SQL('select * from guestbook'));
		} catch(Exception $e) {
			$this->session->AddMessage('error', 'Failed to read from database');
			return array();
		}
	}
}