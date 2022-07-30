<?php

class Router
{
	private $routes;
	private $uri;

	function __construct()
	{
		$routesPath = ROOT . '/config/routes.php';
		$this->routes = include($routesPath);
		$this->uri = trim($_SERVER['REQUEST_URI'],'/');
	}

	private function getControllerName()
	{
		if(explode('/',$this->uri)[0] !== ''){
			return ucfirst(explode('/',$this->uri)[0]).'Controller';
		} else {
			return 'SiteController';
		}
	}

	private function getActionName()
	{
		if(explode('/',$this->uri)[1]){
			return 'action'.ucfirst(explode('/',$this->uri)[1]);
		} else {
			return 'actionIndex';
		}
	}

	private function getParam()
	{
		if(explode('/',$this->uri)[2]){
			return explode('/',$this->uri)[2];
		}
	}	

	public function run()
	{
		if ($this->routes[$this->uri]){
			$controllerName = ucfirst(explode('/',$this->routes[$this->uri])[0]).'Controller';
			$actionName = 'action'.ucfirst(explode('/',$this->routes[$this->uri])[1]);
		} else {
			$controllerName = $this->getControllerName();
			$actionName = $this->getActionName();		
		}
		$view = lcfirst(explode('Controller',$controllerName)[0]); //получаем название контекста из названия контроллера
		$model = (explode('Controller',$controllerName)[0]); //получаем название модели из названия контроллера
		$obj = new $controllerName; 
		$obj->view = $view;
		if(class_exists($model)){			
			$obj->model = new $model($view);
		}
		$obj->$actionName($param = $this->getParam());
	}
}