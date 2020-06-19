<?php

use app\core\Router;

 $query = rtrim($_SERVER['QUERY_STRING'], '/');
 

 define('WWW', __DIR__);
 define('CORE', dirname(__DIR__) . '/vendors/core');
 define('ROOT', dirname(__DIR__));
 define('APP', dirname(__DIR__) . '/app');
 define('LAYOUT', 'default');


@require_once '../lib/function.php';

spl_autoload_register(function($class){
	$file = ROOT. '/' . str_replace('\\', '/', $class) . '.php';
	
	if(is_file($file)){
		require_once $file;
	}
});

Router::add('user/registration', ['controller'=> 'user', 'action'=>'registration']);
Router::add('user/authorization', ['controller'=> 'user', 'action'=>'authorization']); 
Router::add('user/logout', ['controller'=> 'user', 'action'=>'logOut']);
Router::add('user/getUserBalance', ['controller'=> 'user', 'action'=>'getUserBalance']);
Router::add('main/loadData', ['controller'=> 'main', 'action'=>'loadData']);
Router::add('main/deleterecord', ['controller'=> 'main', 'action'=>'deleteRecord']);
Router::add('main/selectRecord', ['controller'=> 'main', 'action'=>'selectRecord']);
Router::add('^user/(?P<alias>[a-z-]+)$', ['controller'=> 'user', 'action'=>'checkLogin']);
Router::add('^$', ['controller'=> 'Main', 'action' => 'index']);  // Для пустой строки.
Router::add('^(?P<controller>[a-z-]+)/?(?P<action>[a-z-]+)?$');



Router::dispatch($query);



