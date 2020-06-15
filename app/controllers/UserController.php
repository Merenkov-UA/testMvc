<?php
namespace app\controllers;
use app\core\base\Controller;
use app\models\User;

class UserController extends Controller {
	
	

	public function indexAction(){

	}

	public function registrationAction(){
		$user = new User;
		$this->layout = 'registration';

		$msg = '';

		session_start( ) ;
		if( ! empty( $_POST ) ) {	
			$msg = "" ;
			if( empty( $_POST[ 'login' ] ) ) {
				$msg = "Логин не может быть пустым" ;
				
			} else $_SESSION[ 'login' ] = $_POST[ 'login' ] ;
					
			if( empty( $_POST[ 'name' ] ) ) {
				$msg = "Имя не может быть пустым" ;
				
			} else $_SESSION[ 'name' ]  = $_POST[ 'name' ]  ;

			if( empty( $_POST[ 'balance' ] ) ) {
				$msg = "Баланс денежных средств не может быть пустым" ;
				
			} else $_SESSION[ 'balance' ]  = $_POST[ 'balance' ]  ;
			
			
			if( empty( $_POST[ 'pass' ] ) ) {
				$msg = "Пароль не может быть пустым" ;
				
			} else if( strlen( $_POST[ 'pass' ] ) < 5 ) {
				$msg = "Пароль слишком короткий (5 символов как минимум)" ;
				
			} else if( ! preg_match( "~\d~", $_POST[ 'pass' ] ) ) {
				$msg = "Пароль должен содержать цифру" ;
				
			} else if( ! preg_match( "~\D~", $_POST[ 'pass' ] ) ) {
				$msg = "Пароль не должен состоять только из цифр" ;
				
			} else if( ! preg_match( "~^.*\W.*$~", $_POST[ 'pass' ] ) ) {
				$msg = "Пароль должен содержать спецсимвол (!\"№;%:)" ;
				
			}
			
			if( $_POST[ 'pass' ] !== $_POST[ 'pass2' ] ) {
				$msg = "Пароли не совпадают" ;
				
			}
			
			if( empty( $_POST[ 'email' ] ) ) {
				$msg = "Укажите эл. почту" ;
				
			} else {
				$_SESSION[ 'email' ] = $_POST[ 'email' ] ;
				if( ! preg_match( "~^\w+([-+.']\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*$~", $_POST[ 'email' ] ) ) {
					$msg = "Укажите валидную эл. почту" ;
					
				}
			}

			try {
				
				$login_free = $user->findOne($_POST[ 'login' ], "login");
			} catch( Exception $ex ) {
				$msg = $ex->getMessage( ) ;
				view( ) ;
			}
			
			if( $login_free ) {
				$msg = "Логин уже используется другим пользователем" ;
				
			}
			
			if( empty( $msg ) ) {
				$salt = md5( rand( ) ) ;
				$pass = hash( 
					'SHA256', 
					$_POST[ 'pass' ] . $salt 
				) ;
				
				$sql = "INSERT INTO users (name, email, login, pass_hash, pass_salt, balance )
				 VALUES ('".$_POST['name']."','".$_POST['email']."','".$_POST['login']."','".$pass."','".$salt."','".$_POST['balance']."')";
				
				try {
					$user->addToDb( $sql ) ;
				} catch( Exception $ex ) {
					$msg = $ex->getMessage( ) ;
					
				}
				
				echo "<script>setInterval(
									()=>{
								var v=countdown.innerText-1;
								if(v<0)window.location='/test1';
								else countdown.innerText=v
							},
							1000
						)</script>
						<div class='container' style='text-align:center;'><h1>Данные приняты, через несколько секунд вы окажетесь на главной странице</h1>
						<p id='countdown'>3</p></div>" ;
				
				
				session_unset( ) ;
				exit ;
			} 
		}else {
			session_unset( ) ;
			
		}
		$this->set(compact('msg'));
	}
	public function authorizationAction(){
		$this->layout = 'authorization';
		$user = new User( ) ;

		$msg = "" ;
	
		if( ! empty( $_POST ) ) {
			$login = $_POST[ 'login' ] ;
			$pass  = $_POST[ 'password'  ] ;
				
			try {
				
				session_start( ) ;
				$data =  $user->isAuthorized( $login, $pass );
				if($data)  {
					$this->layout = false;
					$_SESSION[ 'userId' ] = $user->id ;
					echo "<script>setInterval(
							()=>{
						var v=countdown.innerText-1;
						if(v<0)window.location='/test1';
						else countdown.innerText=v
					},
					1000
				)</script>
				<div class='container' style='text-align:center;'><h1>Данные приняты, через несколько секунд вы окажетесь на главной странице</h1>
				<p id='countdown'>3</p></div>" ;

				exit;
				} else {
					unset( $_SESSION[ 'userid' ] ) ;
					$msg = "Incorrect auth data" ;
				}
			} catch( Exception $ex ) {
				$msg = $ex->getMessage( ) ;
			}
		}
	}
	public function checkLoginAction(){
		$user = new User;

		function send_ans( $ans ) {
			$ret[ 'status' ] = $ans[ 0 ] ;
			$ret[ 'descr'  ] = $ans[ 1 ] ;
			echo json_encode( $ret ) ;
			exit ;
		}

		if( empty( $_GET[ 'login' ] ) ) {
			send_ans( [ -1, "Нет такого логина" ] ) ;
		}
		$reg_pattern = "~\W~i" ;  
		if( preg_match (          
				$reg_pattern ,   
				$_GET[ 'login' ]  
			)
		) {
			send_ans( [ -2, "Логин не может быть пустым" ] ) ; 
		}
		
		try {
			
			if(!$user->findOne($_GET['login'], "login") ) {
				send_ans( [ 1, "Логин свободен" ] ) ;
			} else {
				send_ans( [ -4, "Логин занят" ] ) ;
			}
		} catch( Exception $ex ) {
			send_ans( [ -5, $ex->getMessage( ) ] ) ;
		}


		
		$this->layout = false;
		
	}
	public function getUserBalanceAction(){

		session_start();
		$this->layout = false;

		if(!empty($_SESSION[ 'userId' ]))
		{
			
				$users= new User();


				$user = $users->findOne($_SESSION[ 'userId' ] , 'id');
				if($user!==true)
				{
					$users=null;
				}
				
				echo json_encode($user[0]);
		}
	}

	public function logOutAction(){
		$this->layout = 'default';
		if( isset( $_GET[ 'logout' ] ) ) {
		unset( $_SESSION[ 'userId' ] ) ;
		header( "Location: /test1" ) ;
		exit ;
		}
	}

}