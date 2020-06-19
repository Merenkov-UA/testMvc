<?php

namespace app\core\base;

abstract class Controller{
	public $route = [];
	public $view;
	public $layout;
	public $data = [];
	public $title;

	public function __construct($route){
		$this->route = $route;
		$this->view = $route['action'];

	}

	public function getView(){
		$vObj = new View($this->route, $this->layout, $this->view);
		self::getViewTest($this->title, $this->data);
		$vObj->render($this->title, $this->data);
	}
	
	public function getViewTest($title, $data = []){
		$this->title = $title;
		$this->data = $data;
	}

	public function set( $data){
		$this->data = $data;
		

	}


	
}