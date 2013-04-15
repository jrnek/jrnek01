<?php

	class CMDatabase {
		
		private $db = null;
		private $stmt = null;
		private static $nrQueries = 0;
		private static $queries = array();
		
		public function __construct($dsn, $user=null, $pwd=null, $driverOption = null) {
			$this->db = new PDO($dsn, $user, $pwd, $driverOption);
			$this->db->SetAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); 
		}
		
		public function SetAttribute($attribute, $value) {
			$this->db->setAttribute($attribute, $value);
		}
		
		public function GetNrQueries() {
			return self::$nrQueries;
		}
		
		public function GetQueries() {
			return self::$queries;
		}
		
		public function ExecuteAndFetch($query, $params=array()) {
			$this->stmt = $this->db->prepare($query);
			self::$queries[] = $query; 
			self::$nrQueries++;
			$this->stmt->execute($params);
			return $this->stmt->fetchAll(PDO::FETCH_ASSOC);
		}
		
		public function ExecuteQuery($query, $params=array()) {
			$this->stmt = $this->db->prepare($query);
			self::$queries[] = $query; 
			self::$nrQueries++;
			return $this->stmt->execute($params);
		}
		
		public function LastInsertId() {
			return $this->db->lastInsertid();
		}
		
		public function RowCount() {
			return is_null($this->stmt) ? $this->stmt : $this->stmt->rowCount();
		}
		
		
	}