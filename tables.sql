<?php
//Блок подключение к базе данных
	unset($db_type);
	$db = require 'config/config_db.php';
	if(empty($db)){
		echo "Config load error";
		exit;
	}
	
try{
	$DB= new PDO('mysql:host='.$db['host'].';dbname='.$db['name'].'', $db['user'], $db['password']);
	$DB ->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
}
catch(PDOException $ex){
	echo"CONNECTION ERROR:",$ex->getMessage();
	exit;
}
echo "Успешно вошли <br/><br/>"; //Вывод строки в слувае успешного подключения к базе.

//Блок создание таблицы записей при условии её отсутствия
//Строка запроса.
$query=<<<SQL
CREATE TABLE  IF NOT EXISTS records(

id            INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
description   VARCHAR(128),
amount        FLOAT(10),
operation 	  VARCHAR(16),
dt_create     DATETIME DEFAULT CURRENT_TIMESTAMP,
dt_edit       DATETIME,
id_author     INT


) engine=InnoDB default charset = utf8 collate=utf8_general_ci
SQL;

//Выполнение запроса.
try{
	$DB->query($query);
}catch(Exception $ex)
{
	echo  $ex->getMessage(),"<BR>",$query;
	exit;
}
echo "<br>Запрос выполнен"; //Выводит строку в случае успешного добавления таблицы.

//Блок создание таблицы пользователей при условии её отсутствия
//Строка запроса.
$query=<<<SQL
CREATE TABLE  IF NOT EXISTS Users(
id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
name VARCHAR(32),
email VARCHAR(32),
login VARCHAR(32),
pass_hash CHAR(64),
pass_salt CHAR(32),
balance FLOAT(32)
) engine=InnoDB default charset = utf8 collate=utf8_general_ci

SQL;


//Выполнение запроса.
try{ $DB->query($query);
}catch(Exception $ex)
{
	echo  $ex->getMessage(),"<br/>",$query;
	exit;
}
echo "<br>Запрос выполнен";  //Выводит строку в случае успешного добавления таблицы.