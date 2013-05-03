<?php

class CMUser extends CObject implements IHasSQL, IModule, ArrayAccess {

	public $profile = array();
	
	public function __construct($jr=null) {
		parent::__construct($jr);
		//$this->Init();	
		$profile = $this->session->GetAuthUser();
		$this->profile = is_null($profile) ? array() : $profile;
		$this['isAuthenticated'] = is_null($profile) ? false : true;
	}
	

	public function offsetSet($offset, $value) { if (is_null($offset)) { $this->profile[] = $value; } else { $this->profile[$offset] = $value; }}
	public function offsetExists($offset) { return isset($this->profile[$offset]); }
	public function offsetUnset($offset) { unset($this->profile[$offset]); }
	public function offsetGet($offset) { return isset($this->profile[$offset]) ? $this->profile[$offset] : null; }

	public static function SQL($key=null) {
		$queries = array(
			'create table user'  => "CREATE TABLE IF NOT EXISTS User (
										  id INTEGER PRIMARY KEY, 
										  acronym TEXT KEY, 
										  name TEXT, 
										  email TEXT,
										  algorithm TEXT,
										  password TEXT,
										  salt TEXT,
										  created DATETIME default (datetime('now'))
										);",
			'create table groups' => "CREATE TABLE IF NOT EXISTS Groups (
									  id INTEGER PRIMARY KEY, 
									  acronym TEXT KEY, 
									  name TEXT, 
									  created DATETIME default (datetime('now'))
									);",
			'create table user2groups' => "CREATE TABLE IF NOT EXISTS User2Groups (
											idUser INTEGER,
											idGroups INTEGER,
											created DATETIME default (datetime('now')),
											PRIMARY KEY(idUser, idGroups));",
			'insert into user' => 'INSERT INTO User (acronym, name, email, algorithm, password, salt) VALUES (?,?,?,?,?,?);',
			'insert into groups' => 'INSERT INTO Groups (acronym, name) VALUES (?,?);',
			'insert into user2groups' => 'INSERT INTO User2Groups (idUser, idGroups) VALUES (?,?);',
			'select * from user' => 'SELECT * FROM User ORDER BY id DESC;',
			'delete from user' => 'DELETE FROM User;',
			'drop table user' => "DROP TABLE IF EXISTS User;",
			'drop table groups' => "DROP TABLE IF EXISTS Groups;",
			'drop table user2groups' => "DROP TABLE IF EXISTS User2Groups;",
			'get group membership' => 'SELECT * FROM Groups AS g INNER JOIN User2Groups AS ug ON
										g.id=ug.idGroups WHERE ug.idUser=?;',
			'update profile' => "UPDATE User SET name=?, email=?, created=datetime('now') WHERE id=?;",
			'update password' => "UPDATE User SET password=?, salt=?, created=datetime('now') WHERE id=?;",
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
					//Create tables
					$this->db->ExecuteQuery(self::SQL('drop table user'));
					$this->db->ExecuteQuery(self::SQL('drop table groups'));
					$this->db->ExecuteQuery(self::SQL('drop table user2groups'));
					$this->db->ExecuteQuery(self::SQL('create table user'));
					$this->db->ExecuteQuery(self::SQL('create table groups'));
					$this->db->ExecuteQuery(self::SQL('create table user2groups'));
					
					//$jr = CJrnek::Instance();
					//Create admin user
					$pass = $this->CreatePassword('admin');
					$this->db->ExecuteQuery(self::SQL('insert into user'), array('admin', 'Adminstrator', "admin@jrnek.com",
																$this->config['hashing_algorithm'], $pass['password'], $pass['salt']));
					$adminuserId = $this->db->LastInsertId();
					
					//Create test user
					$pass = $this->CreatePassword('doe');
					$this->db->ExecuteQuery(self::SQL('insert into user'), array('doe', 'jrnek Test user', "doe@jrnek.com",
																$this->config['hashing_algorithm'], $pass['password'], $pass['salt']));
					$userId = $this->db->LastInsertId();
					
					//Create Admin group
					$this->db->ExecuteQuery(self::SQL('insert into groups'), array('admin', 'Administrators'));
					$admingroupId = $this->db->LastInsertId();
					
					//Create normal user group
					$this->db->ExecuteQuery(self::SQL('insert into groups'), array('user', 'Normal user'));
					$usergroupId = $this->db->LastInsertId();
					
					//Create connection between users and groups
					$this->db->ExecuteQuery(self::SQL('insert into user2groups'), array($adminuserId, $admingroupId));
					$this->db->ExecuteQuery(self::SQL('insert into user2groups'), array($adminuserId, $usergroupId));
					$this->db->ExecuteQuery(self::SQL('insert into user2groups'), array($userId, $usergroupId));
					//$this->session->AddMessage('info', 'Successfully loaded and filled database tables user');
					return array('success', 'Successfully created the database tables and created a default admin user as admin:admin and an ordinary user as doe:doe.');
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
			//Create tables
			$this->db->ExecuteQuery(self::SQL('drop table user'));
			$this->db->ExecuteQuery(self::SQL('drop table groups'));
			$this->db->ExecuteQuery(self::SQL('drop table user2groups'));
			$this->db->ExecuteQuery(self::SQL('create table user'));
			$this->db->ExecuteQuery(self::SQL('create table groups'));
			$this->db->ExecuteQuery(self::SQL('create table user2groups'));
			
			//$jr = CJrnek::Instance();
			//Create admin user
			$pass = $this->CreatePassword('admin');
			$this->db->ExecuteQuery(self::SQL('insert into user'), array('admin', 'Adminstrator', "admin@jrnek.com",
														$this->config['hashing_algorithm'], $pass['password'], $pass['salt']));
			$adminuserId = $this->db->LastInsertId();
			
			//Create test user
			$pass = $this->CreatePassword('test01');
			$this->db->ExecuteQuery(self::SQL('insert into user'), array('testuser', 'jrnek Test user', "testuser@jrnek.com",
														$this->config['hashing_algorithm'], $pass['password'], $pass['salt']));
			$userId = $this->db->LastInsertId();
			
			//Create Admin group
			$this->db->ExecuteQuery(self::SQL('insert into groups'), array('admin', 'Administrators'));
			$admingroupId = $this->db->LastInsertId();
			
			//Create normal user group
			$this->db->ExecuteQuery(self::SQL('insert into groups'), array('user', 'Normal user'));
			$usergroupId = $this->db->LastInsertId();
			
			//Create connection between users and groups
			$this->db->ExecuteQuery(self::SQL('insert into user2groups'), array($adminuserId, $admingroupId));
			$this->db->ExecuteQuery(self::SQL('insert into user2groups'), array($adminuserId, $usergroupId));
			$this->db->ExecuteQuery(self::SQL('insert into user2groups'), array($userId, $usergroupId));
			$this->session->AddMessage('info', 'Successfully loaded and filled database tables');
			
		} catch(Exception $e) {
			$error = "Failed to open database: " . $this->config['database'][0]['dsn'] . "<br/>" . $e;
			$this->session->AddMessage('error', $error);
		}
	}
	
	public function Add($username, $name, $email, $password) {
		$this->db->ExecuteQuery(self::SQL('insert into user'), array($username, $name, $email, $password));
		if($this->db->rowCount() != 1) {
			$error = "Failed to insert new user" ;
			$this->session->AddMessage('error', $error);
		} else {
			$this->session->AddMessage('info', 'Successfully added user to database');
		}
	}
	
	public function DeleteAll() {
		$this->db->ExecuteQuery(self::SQL('delete from user'));
		$this->session->AddMessage('info', 'Removed all users from database');
	}
	
	public function ReadAll() {
		try{
			return $this->db->ExecuteAndFetch(self::SQL('select * from user'));
		} catch(Exception $e) {
			$this->session->AddMessage('error', 'Failed to read from database');
			return array();
		}
	}
	
	public function Login($user, $password) {
		foreach($this->ReadAll() as $val) {
			if($val['acronym'] == $user || $val['email'] == $user) {
				if($this->CheckPassword($password, $val['salt'], $val['password'], $val['algorithm'])){
					unset($val['password']);
					$val['groups'] = $this->db->ExecuteAndFetch(self::SQL('get group membership'), array($val['id']));
					$this->session->SetAuthUser($val);
					$this->session->AddMessage('info', 'Welcome '. $val['name'] . '!');
					return true;
				}
			}
		}
		$this->session->AddMessage('error', 'Wrong username or password!');
		return false;
	}
	
	public function Create($acronym, $password, $name, $email) {
		$pass = $this->CreatePassword($password);
		$this->db->ExecuteQuery(self::SQL('insert into user'), array($acronym, $name, $email,
														$this->config['hashing_algorithm'], $pass['password'], $pass['salt']));
		$userId = $this->db->LastInsertId();
		$this->db->ExecuteQuery(self::SQL('insert into user2groups'), array($userId, 2));
		if($this->db->RowCount() == 0) {
			$this->session->AddMessage('error', "Failed to create user.");
			return false;
		}	
		return true;
	}
	
	public function Logout() {
		$this->session->UnsetAuthUser();
		$this->session->AddMessage('info', 'Successfully logged out');
	}
	
	public function IsAuthenticated() {
		return ($this->session->GetAuthUser() != false);
	}
	
	public function GetUserProfile() {
		return $this->session->GetAuthUser();
	}
	
	public function GetAcronym() {
		$user = $this->GetUserProfile();
		return isset($user['name']) ? $user['name'] : null;
	}
	
	public function IsAdmin() {
		$user = $this->GetUserProfile();
		foreach($user['groups'] as $val) {
			if($val['acronym'] == 'admin') {
				return true;
			}
		}
		return false;
	}
	
	public function Save() {
		$this->db->ExecuteQuery(self::SQL('update profile'), array($this['name'], $this['email'], $this['id']));
		$this->session->SetAuthUser($this->profile);
		return $this->db->RowCount() === 1;
  }
	
	public function ChangePassword($password) {
		$pass = $this->CreatePassword($password);
		$this->db->ExecuteQuery(self::SQL('update password'), array($pass['password'], $pass['salt'], $this['id']));
		return $this->db->RowCount() === 1;
	}
	
	public function CreatePassword($plain, $algorithm = null) {
		$password = array(
			'algorithm' => ($algorithm ? $algorithm : $this->config['hashing_algorithm']),
			'salt' => null,
		);
		switch($password['algorithm']) {
			case 'sha1salt': $password['salt'] = sha1(microtime()); $password['password'] = sha1($password['salt'].$plain); break;
			case 'md5salt': $password['salt'] = md5(microtime()); $password['password'] = md5($password['salt'].$plain); break;
			case 'sha1': $password['password'] = sha1($plain); break;
			case 'md5': $password['password'] = md5($plain); break;
			case 'plain': $password['password'] = $plain; break;
			default: throw new Exception('Unknown hashing algorithm');
		}
		return $password;
	}
	
	public function CheckPassword($plain, $salt, $password, $algorithm) {
		switch($algorithm) {
			case 'sha1salt': return $password === sha1($salt.$plain); break;
			case 'md5salt': return $password === md5($salt.$plain); break;
			case 'sha1': return $password === sha1($plain); break;
			case 'md5': return $password === md5($plain); break;
			case 'plain': return $password === $plain; break;
			default: throw new Exception('Unknown hashing algorithm');
		}
	}
}