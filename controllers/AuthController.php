<?php 

class AuthController
{
	public function actionIndex(){
		$_SESSION['auth'] = true;
		header('Location: /');
	}
}