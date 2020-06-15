<?php


namespace app\core;
use PDO;

class Db{
	protected $pdo;
	protected static $instance;
	protected $db = [
			'host' => 'localhost',
			'name' => 'accountant',
			'user' => 'ac_admin',
			'password' => '12345',
];
	

	protected function __construct(){
		
		$this->pdo = new PDO('mysql:host='.$this->db['host'].';dbname='.$this->db['name'].';charset=UTF8', $this->db['user'], $this->db['password']);
		
	}

	public static function instance(){
		if(self::$instance === null){
			self::$instance = new self;
		}
		return self::$instance;
	}

	public function query($sql, $params = []) {
		
		$stmt = $this->pdo->prepare($sql);
		$res = $stmt->execute($params);
		if($res !== false){
			return $stmt->fetchAll(PDO::FETCH_ASSOC);
		}
		return [];
	}
	public function queryTest($sql, $params = []) {
		
		$stmt = $this->pdo->prepare($sql);
		if (!empty($params)) {
			foreach ($params as $key => $val) {
				$stmt->bindValue(':'.$key, $val);
			}
			$stmt->execute();
			return  $stmt;
		}
		
		return [];
	}

}