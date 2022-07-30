<?php

class TestController extends Controller
{
	function __construct(){
		//$this->htm = $this->model->get_htm();
	}

	public function actionIndex()
	{

	}	

	public function actionExist()
	{
		$htm = $this->model->get_htm();
		$org = $this->model->getOrgList();
		//$this->dump($org);
		//$this->dump($htm);
		foreach ($org as $value) {
			if(!$htm[$value]){
				$not_exist[] = $value;
			}
		}
		echo'<pre>';print_r($not_exist);die;
		echo $this->model->setNotExist($not_exist);
	}
}