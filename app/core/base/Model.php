<?php

namespace app\core\base;
use app\core\Db;


abstract class Model{
	public $pdo;
	protected $table;
	protected $key = 'id';
	

	public function __construct(){
		$this->pdo = Db::instance();
	}

	public function query($sql){
		return $this->pdo->execute($sql);
	}

	public function findAll(){
		$sql = "SELECT * FROM {$this->table}";
		return $this->pdo->query($sql);
	}
	public function findOne($id, $field=''){
		$field = $field ?: $this->key;
		$sql = "SELECT * FROM {$this->table} WHERE $field = ? LIMIT 1";
		return $this->pdo->query($sql, [$id]);
	}
	public function findAllRecords($id, $field=''){
		$field = $field ?: $this->key;
		$sql = "SELECT * FROM records WHERE $field = ? ";
		return $this->pdo->query($sql, [$id]);
	}
	public function findOneUser($login){
		
		$sql = "SELECT * FROM users WHERE login = ? LIMIT 1";
		return $this->pdo->query($sql, [$login]);
	}

	public function findBySql($sql, $params = []){
		return $this->pdo->query($sql, $params);
	}
	public function addToDb($sql, $params = []){
		return $this->pdo->query($sql, $params);
	}
	public function deleteRecordById($Id){
		$sql = "DELETE FROM records WHERE id=".$Id;
		return $this->pdo->query($sql);
	}
	public function updateUserBalance($balance, $Id ){
		$sql = "UPDATE  users SET balance  = ".$balance."  WHERE id = ".$Id ;
		return $this->pdo->query($sql);
	}
	public function updateRecord($description, $amount, $Id ){
		$sql = "UPDATE  records SET description  = ".$description.",  amount = ".$amount.", dt_edit = CURRENT_TIMESTAMP  WHERE id = ".$Id ;
		return $this->pdo->query($sql);
	}

	public function findLike($str, $field, $table = ''){
		$table = $table ?: $this->table;
		$sql = "SELECT * FROM $table WHERE $field LIKE ? LIMIT 2";
		return $this->pdo->query($sql, ['%'. $str .'%']);

	}
	
}