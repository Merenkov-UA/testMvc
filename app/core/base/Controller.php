<?php

namespace app\core\base;

abstract class Controller{
	public $route = [];
	public $view;
	public $layout;
	public $data = [];

	public function __construct($route){
		$this->route = $route;
		$this->view = $route['action'];
	}

	public function getView(){
		$vObj = new View($this->route, $this->layout, $this->view);
		$vObj->render($this->data);
	}

	public function set($data){
		$this->data = $data;

	}


	
}