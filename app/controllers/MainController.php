<?php

namespace app\controllers;
use app\core\base\Controller;
use app\models\Main;
use app\models\User;
use app\models\Record;

class MainController extends Controller{
	public $layout = 'main';

	public function indexAction(){
		$this->layout = 'default';
		session_start();
		$users = new User();
		$records = new Record;
		if(!empty($_SESSION[ 'userId' ])){
				$user = $users->findOne($_SESSION[ 'userId'], 'id');
				$this->set(compact('user'));
				if(!empty($users))
				$all_records = $records->findAllRecords($_SESSION[ 'userId'], 'id_author'); 
			else{
				$all_records['operation'] = "";
				$all_records['description'] = "";
				$all_records['amount'] = "";
			}
		}	
	}


	public function addRecordAction(){
			$user = new User( ) ;
			$record = new Record( ) ;
			$this->layout = false;

			session_start( ) ;
			$amount = 0;
			$description = "";
			$operation = "";
			$msg = [];
			$add_ok = false ;

			try {
				$user->findOne( $_SESSION[ 'userId' ], 'id' ) ;
					 
			} catch( Exception $ex ) {echo $ex->getMessage( )  ;}

				
			if( ! empty( $_POST ) ) {
			
				
			
			if( empty( $_POST[ 'amount' ] ) ) {
				$msg[] = "Необходимо указать сумму" ;
			}
			if( empty( $_POST[ 'description' ] ) ) {
				$msg[] = "Необходимо указать описание" ;
			}
			if( empty( $_POST[ 'operation' ] ) ) {
				$msg[] = "Необходимо указать  операцию" ;
			}
			if( empty($_SESSION[ 'userId' ]) ) {
				$msg[] = "Необходимо указать автора" ;
			}

			if($_POST[ 'operation' ] === "spending")
			{

				if(($_POST['balance'] - $_POST['amount']) > 0){
					$user -> updateUserBalance(($_POST['balance'] - $_POST['amount']),  $_SESSION[ 'userId' ]);
				}
				else $msg[] = "Не хватает суммы для траты.";
			}
			else{
					try {
					$test = $user -> updateUserBalance(($_POST['balance'] + $_POST['amount']),  $_SESSION[ 'userId' ]);
					
				} catch( Exception $ex ) {
					$msg = $ex->getMessage( ) ;
					
				}
				
			}
			
			if( ! empty( $msg ) ) {
				$amount       = $_POST[ 'amount' ]      ;
				$description  = $_POST[ 'description' ] ;
				$operation    = $_POST[ 'operation' ]   ;

				
			} 	

			$sql = "INSERT INTO records( description, amount, operation, dt_create, id_author)
			 VALUES('".$_POST['description']."', '".$_POST['amount']."', '".$_POST['operation']."', CURRENT_TIMESTAMP , '". $_SESSION[ 'userId' ]."' )
				" ;
				try {
					$user->addToDb( $sql ) ;
				} catch( Exception $ex ) {
					$msg[] = "Ошибка добавления записи: " . $ex->getMessage( ) ;
					
				}
						
			}
			if(empty($msg))
				echo "Добавлено успешно";
			else echo "Проверьте правильность заполнения полей";

	}

	public function loadDataAction(){
		session_start();
		$this->layout = false;
		if(!empty($_SESSION[ 'userId' ])){
			$records = new Record;
			$all_records = $records->findAllRecords($_SESSION[ 'userId'], 'id_author');
		$ret = array('data' => array());
		$operation = '';
		$dateEdit = '';
		$amount  = '';
		$n=1;
		for($x = 0; $x < count($all_records); $x++) {
			
			if($all_records[$x]['operation'] == 'profit'){
				$operation = 'Доход';
			}else{
				$operation = 'Затраты';
			}

			if($all_records[$x]['dt_edit'] == null){
				$dateEdit = 'Не редактировалась';
			}else{
				$dateEdit = $all_records[$x]['dt_edit'];
			}
			$amount = $all_records[$x]['amount'];


			$actionButton = '<div class="btn-group">
								<button type="button" class="btn btn-outline-secondary dropdown-toggle" data-toggle="dropdown"
									aria-haspopup="true" aria-expanded="false">
									Действие
									 <span class="caret"></span>
									 </button>
								<ul class="dropdown-menu">
								<li><a type="button" data-toggle="modal" data-target="#editRecordModal" onclick="editRecord('.$all_records[$x]['id'].')"><span class="fa fa-pencil" aria-hidden="true">Изменить</span></a></li>
								<li><a type="button" data-toggle="modal" data-target="#deleteRecordModal" onclick="deleteRecord('.$all_records[$x]['id'].')"><span class="fa fa-trash" aria-hidden="true">Удалить</span></a></li>
								</ul>
								</div>';
			$ret['data'][] = array(
				$n,
				$all_records[$x]['description'],
				$operation,
				$amount,
				$all_records[$x]['dt_create'],
				$dateEdit,
				$actionButton

			);
			$n++;
			
		}
			echo json_encode($ret);
		}

	}

	public function deleteRecordAction(){
		$records = new Record( ) ;
		$users = new User();
		$this->layout = false;;
		$data = array('success' => false, 'messages' => array());
		if(!empty($_POST['recordId']))
		{
			$record = $records->findOne($_POST['recordId'], 'id');
			$user = $users->findOne($record[0]['id_author'], 'id');
		
		}
		$query = false;


		if($record[0]['operation'] === "spending")
		{
			
			$users->updateUserBalance(($user[0]['balance'] + $record[0]['amount']),  $record[0]['id_author']);
			$records->deleteRecordById($_POST['recordId']) ;
			$query = true;
			
			
		}
		else if($record[0]['operation'] === "profit"){
			if(($user[0]['balance'] - $record[0]['amount']) > 0)
			{
				$users -> updateUserBalance(($user[0]['balance'] - $record[0]['amount']),  $record[0]['id_author']);
				
				 $records->deleteRecordById($_POST['recordId']) ;
				$query = true;
				
			}else{
				$query = false;
			}
			
			
		}
		else{
				$query = false;

		} 

		if($query === true){
				$data['success'] = true;
				$data['messages'] = "Успешно удалена запись";
			}else{
				$data['success'] = false;
				$data['messages'] = "Ошибка удаления записи";
			}
			
	
	 
			print json_encode($data); 
		
	}

	public function updateRecordAction(){
		$this->layout = false;
		$users = new User();
		$records = new Record( ) ;

		$user = $users->findone($_POST['authorId'],'id');

		$data = array('success' => false, 'messages' => array());
		$query = '';

		if($_POST['editOperationOldValue'] === "spending")
		{
			if($_POST['editAmount'] > $_POST['oldAmount'])
			{
				if(($user[0]['balance'] - ($_POST['editAmount'] - $_POST['oldAmount'])) > 0){
					$users->updateUserBalance(($user[0]['balance'] - ($_POST['editAmount'] - $_POST['oldAmount'])),  $_POST['authorId']);
					$query = true;
				}
				else{
					$query = false;
				}
			}
			else
			{
				$users->updateUserBalance(($user[0]['balance'] + ($_POST['oldAmount'] - $_POST['editAmount'])  ),  $_POST['authorId']);
				$query = true;
			}
			
		}
		else if($_POST['editOperationOldValue'] === "profit"){
			if($_POST['editAmount'] > $_POST['oldAmount'])
			{
				$users->updateUserBalance(($user[0]['balance'] + ($_POST['editAmount'] - $_POST['oldAmount'])),  $_POST['authorId']);
				$query = true;
			}
			else{
				$users->updateUserBalance(($user[0]['balance'] - ($_POST['oldAmount'] - $_POST['editAmount'])  ),  $_POST['authorId']);
				$query = true;
			}
		}
			$records->updateRecord($_POST['editDescription'], $_POST['editAmount'], $_POST['editId']) ;
		
		 
		

		if($query === true){
				$data['success'] = true;
				$data['messages'] = "Запись успешно обновлена ";
			}else{
				$data['success'] = false;
				$data['messages'] = "Ошибка обновления записи";
			}
			
	 
		print json_encode($data);

	}

	public function selectRecordAction(){
		$this->layout = false;
		$recordId = $_POST['recordId'];
		$records = new Record( ) ;
		$record = $records->findOne($recordId);

 		echo json_encode($record[0]); 
	}		

}