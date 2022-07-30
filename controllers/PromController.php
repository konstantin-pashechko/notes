<?php 

class PromController extends Controller
{
	private function getAvailable($quantity, $stock_status_id, $status)
	{ 
		if($status == "0"){
			return '';
		}
		if($quantity == '0' && $stock_status_id == '5'){
			return '';
		}
		if($quantity == '0' && $stock_status_id == '8') {
			return "false";
		}

		return "true";
	}
	public function actionIndex(){
		$this->render('prom');
	}

	public function actionUnset(){
		unset($_SESSION['xml']);
		unset($_SESSION['orgList']);
		$this->render('prom');
	}

	public function actionCreate()
	{
		$promList = $this->model->getPromList();

		$orgList = $this->model->getOrgList();
		//$this->dump($orgList);
		$orgList = $this->filter($orgList, function($item){ // только включенные
			return $item['status'];
		});
		$orgList = $this->filter($orgList, function($item){ // только, с количеством, более 0
			return $item['quantity'];
		});		
	
//$this->dump($promList);
		foreach ($promList as $value) {                      // переписываем поле id
			if (is_array($value['param'])){
				foreach ($value['param'] as $item) {
					if(intval($item > 3000000000 && $item < 4000000000)){
						$promIds[] = $item - 3000000000;
					}    
				}
			} else {
				$promIds[] = $value['param']-3000000000;
			}
		}
//$this->dump($promIds);		
//$this->dump($promList);
//$this->dump($orgList);

		foreach ($orgList as $key => $value) {
			if(in_array($value['product_id'], $promIds)){
				unset($orgList[$key]);
			}
		}
//$this->dump($orgList);
		$i = 0;
		foreach ($orgList as $value) {
			$import[$i]['product_id'] = $value['product_id'];
			$import[$i]['name'] = $value['name'];
			$import[$i]['description'] = $value['description'];
			$import[$i]['vendor_code'] = $value['sku'];
			$import[$i]['price'] = $value['price'];
			$import[$i]['image'] = 'https://atn.org.ua/image/'.$value['image'];
			$import[$i]['vendor'] = $value['manufacturer'];
			if($i == 99){break;}
			$i++;
		}		
	//$this->dump($import);

		$import = System::createXML($import);
		System::write($import);
		header('Location: /prom/');
	}     

	public function actionUpdate(){
		$orgList = $this->model->getOrgList();
		$promList = $this->model->getPromList();
//$this->dump($orgList);
		foreach ($orgList as $value) {
			//if($value['product_id']=='234'){$this->dump($value['status']);}
			$org[$value['product_id']] = trim($value['price']);
			$available[$value['product_id']] = $this->getAvailable($value['quantity'], $value['stock_status_id'], $value['status']);
		}
//$this->dump($org);
		foreach ($promList as &$value) {                      // переписываем поле id
			if (is_array($value['param'])){
				foreach ($value['param'] as $item) {
					if(intval($item > 3000000000 && $item < 4000000000)){
						$id = $item - 3000000000;
					}    
				}
			} else {
				$id = $value['param']-3000000000;
			}
			$value['@attributes']['id'] = $id;
			$value['@attributes']['available'] = $available[$id];
			$value['price'] = $org[$id];			
		}
//$this->dump($promList);
		$import = System::createUpdate($promList);
		//$this->dump($import);
		System::write($import);
		header('Location: /prom/');
	}

	public function actionDisable(){
		$disableList = $this->model->getDisableList();
		$disable = $this->map($disableList, function($item){
			return $item['product_id'];
		});
//echo'<pre>';var_dump($disable);die;
		$promList = $this->model->getPromList();
		foreach ($promList as &$value) {
			if (is_array($value['param'])){
				foreach ($value['param'] as $item) {
					if(intval($item > 3000000000) && $item < 4000000000){
						$id = $item - 3000000000;
					}    
				}
			} else {
				$id = $value['param'] - 3000000000;
			}
			$value['@attributes']['id'] = $id;
		}
//echo'<pre>';var_dump($promList);die;
		foreach ($promList as $value) {
			if(in_array($value['@attributes']['id'], $disable)){
				$result[] = $value; 
			}
		}
//echo'<pre>';var_dump($result);die;
		$import = System::createDisable($result);
		System::write($import);	
		header('Location: /prom/'); 
	}

	public function actionUpdateDescription(){
		$orgList = $this->model->getOrgList();
		//$this->dump($orgList); die;
		$promList = $this->model->getPromList();
		//$this->dump($promList); die;

		foreach ($orgList as $value) {
			$org[$value['product_id']] = $value['description'];
			$available[$value['product_id']] = $this->getAvailable($value['quantity'], $value['stock_status_id']);
		}
		foreach ($promList as &$value) {                      // переписываем поле id
			if (is_array($value['param'])){
				foreach ($value['param'] as $item) {
					if(intval($item > 3000000000 && $item < 4000000000)){
						$id = $item - 3000000000;
					}    
				}
			} else {
				$id = $value['param']-3000000000;
			}
			$value['@attributes']['id'] = $id;
			$value['@attributes']['available'] = $available[$id];
			$value['description'] = $org[$id];			
		}
		//$this->dump($promList); die;
		$import = System::createUpdateDescription($promList);
		System::write($import);
		header('Location: /prom/');
	}	
	public function actionFullupdate(){
		$products = $this->model->getOrgList();
		$promList = include ROOT . '/tmp/products_prom.php';
		//$this->dump($promList);
		$xml = new SimpleXMLElement('<yml_catalog/>');
    	foreach ($products as $product) {
    		if($product['status']!=='1'){ continue; }
    		if($product['stock_status_id']!=='7'){ continue; }
    		if(!in_array($product['product_id'], $promList)){ continue; }
   //  		switch ($product['stock_status_id']) {
   //  			case 7:
   //      			$stock_status = 'true';
   //      			break;
   //  			case 8:
   //      			$stock_status = 'false';
   //      			break;
   //  			default:
   //     				$stock_status = '';			
			// }	
			//$this->dump($product);
        	$offer = $xml->addChild('offer');
        	$offer->addAttribute('available', $stock_status);
        	$offer->addAttribute('id', $product['product_id']);
        	$offer->addChild('price', $product['price']);
        	$offer->addChild('name', $product['name']);
        	$offer->addChild('vendorCode', $product['sku']);
        	//break;
    	}    
    	Header('Content-type: text/xml');
    	$result = $xml->asXML();
    	System::write($result);
		//$this->dump($result);
	}
}