<?php 

class Prom extends Model
{
	private function getXML(){
        //$this->url = 'http://notes.pashechko.kh.ua/tmp/wget.xml';
		$xml = simplexml_load_file($this->url);

//echo '<pre>';var_dump($xml);die;
//if($xml===false){echo 'PromList is empty!';die;}
		$xml = json_decode (json_encode ($xml), 1);
		$list = $xml['shop']['offers']['offer'];
		return $list;
	}

	public function getPromList()
	{
		if (empty($_SESSION['xml'])){
        		$xml = $this->getXML(); //получаем массив из обЪекта xml по ссылке
        		$_SESSION['xml'] = $xml;
        	}
        	return $_SESSION['xml'];
        }

        public function getOrgList()
        {

          $result = $this->db->query('SELECT *, manufacturer.name AS manufacturer, product_description.name AS name, product.image AS image FROM product LEFT JOIN product_description ON product.product_id = product_description.product_id LEFT JOIN manufacturer ON product.manufacturer_id = manufacturer.manufacturer_id WHERE `language_id`=1');
			//var_dump($result); die;
          $result->setFetchMode(PDO::FETCH_ASSOC);
          $productList = array();
          $i = 0;
          while ($row = $result->fetch()) {
             $productList[$i]['product_id'] = $row['product_id'];
             $productList[$i]['model'] = $row['model'];
             $productList[$i]['sku'] = $row['sku'];
             $productList[$i]['image'] = $row['image'];
             $productList[$i]['manufacturer_id'] = $row['manufacturer_id'];
             $productList[$i]['price'] = $row['price'];
             $productList[$i]['name'] = $row['name'];
             $productList[$i]['description'] = $row['description'];
             $productList[$i]['manufacturer'] = $row['manufacturer'];
             $productList[$i]['status'] = $row['status'];
             $productList[$i]['quantity'] = $row['quantity'];
             $productList[$i]['stock_status_id'] = $row['stock_status_id'];
             $i++;
         }
            //echo'<pre>';var_dump($productList); die;    
         return $productList;
     }
     /*
     ** Возвращает товары с сайта ATN.ORG.UA, которые отключены или имеют статусы "Нет в наличии" и "Под заказ"  
     */
     public function getDisableList()
     {

        $result = $this->db->query('SELECT *, manufacturer.name AS manufacturer, product_description.name AS name, product.image AS image FROM product LEFT JOIN product_description ON product.product_id = product_description.product_id LEFT JOIN manufacturer ON product.manufacturer_id = manufacturer.manufacturer_id WHERE status = 0 || (quantity = 0 && stock_status_id = 5) || (quantity = 0 && stock_status_id = 8)');
            //var_dump($result); die;
        $result->setFetchMode(PDO::FETCH_ASSOC);
        $productList = array();
        $i = 0;
        while ($row = $result->fetch()) {
            $productList[$i]['product_id'] = $row['product_id'];
            $productList[$i]['model'] = $row['model'];
            $productList[$i]['sku'] = $row['sku'];
            $productList[$i]['image'] = $row['image'];
            $productList[$i]['manufacturer_id'] = $row['manufacturer_id'];
            $productList[$i]['price'] = $row['price'];
            $productList[$i]['name'] = $row['name'];
            $productList[$i]['description'] = $row['description'];
            $productList[$i]['manufacturer'] = $row['manufacturer'];
            $productList[$i]['status'] = $row['status'];
            $productList[$i]['quantity'] = $row['quantity'];
            $productList[$i]['stock_status_id'] = $row['stock_status_id'];
            $i++;
        }
                // echo '<pre>';
                // var_dump($productList); 
                // die;
        return $productList;
    }
}