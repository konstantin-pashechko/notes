<?php

class Controller
{
	//public $view; // string
	//public $model; // object

	protected function render($view, $params = null)
	{
		if(count($params)>1){
			foreach ($params as $key => $param){
				${$key} = $param;
			}
		} else {
			${key($params)} = $params[key($params)];
		}
		if(explode('/', $view)[1]){
			$filename = ROOT.'/views/'.explode('/', $view)[0].'/'.explode('/', $view)[1].'.php';
		} else {
			$filename = ROOT.'/views/'.$view.'/index.php';			
		}
//$this->dump($filename);
		$content = file_get_contents($filename);
		require_once(ROOT.'/views/layouts/main.php');
	}

	protected function map($items, $func){
		$results = [];

		foreach ($items as $item) {
			$results[] = $func($item);
		}
		return $results;
	}
	
	protected static function filter($items, $func){
		$results = [];

		foreach ($items as $item) {
			if($func($item)){
				$results[] = $item;
			}    
		}
		return $results;
	}

	protected function exist($arr, $mass, $status = 1){
		
		foreach ($arr as $key => $arritem) {

			if($mass[$key]){
				unset($arr[$key]);
			}
		}
//$this->dump($arr);
			if(!$status){ //если передан параметр $status = 0, то удаляем отключенные позиции
					$arr = $this->filter($arr, function($item){
					return $item['status'];
				});		
			}
//$this->dump($arr);
		$res = $this->map($arr, function($item){
			return $item['product_id'];
		});
//$this->dump($res);
		return $res;
	}


	protected function match($arr, $mass){
		foreach ($arr as $key => $arritem) {
//$this->dump($arr);
			if(!$mass[$key]){
				unset($arr[$key]);
			}  else {
				if($arritem['sku'] == $mass[$key]['sku'] && 
				   $arritem['name']['rus'] == $mass[$key]['name']['rus'] &&
				   trim($arritem['name']['ukr']) == trim($mass[$key]['name']['ukr'])
				  	){
					unset($arr[$key]);
				}	
			}  	
		}

		$res = $this->map($arr, function($item){
			return $item['product_id'];
		});

		return $res;
	}

	protected function dump($arr)
	{
		echo '<pre style="font-size:120%;">';
		var_dump($arr);
		die;
	}

	protected function writeLogs($product_id)
	{
    	$filename = ROOT.'/tmp/product.log';
    	$data = $product_id.' ('.date("F j, Y").')'."\n";
    	file_put_contents($filename, $data, FILE_APPEND);
	}

	// protected function isAuth()
	// {
 //        if (!empty($_SESSION['auth'])) {
 //            die;
 //        }		
	// }	

}