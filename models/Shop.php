<?php 

 class Shop extends Model
 {

    public function getShopList()
    {
    	$sql = 'SELECT *, oc_manufacturer.name AS manufacturer, oc_product_description.name AS name, oc_product.image AS image FROM oc_product LEFT JOIN oc_product_description ON oc_product.product_id = oc_product_description.product_id LEFT JOIN oc_manufacturer ON oc_product.manufacturer_id = oc_manufacturer.manufacturer_id';

        $list = $this->query($sql);
//echo '<pre>';var_dump($list);die;        
        $result = [];
        foreach($list as $item){
            $result[$item['product_id']][$item['language_id']] = $item;
        }
        foreach($result as $key => $value){
            $res[$key] = $value[1];
            $res[$key]['name'] = [ 'rus' => $value[1]['name'], 'ukr' => $value[2]['name'] ];
            $res[$key]['description'] = [ 'rus' => $value[1]['description'], 'ukr' => $value[2]['description'] ];
        }
        return $res;
    }

    public function getShopNotAttr()
    {
        $sql = 'SELECT product_id FROM oc_product_attribute';
        $list = $this->query($sql);
        $attr = [];
        foreach($list as $item){
            $attr[$item['product_id']] = $item['product_id'];
        }

        $sql = 'SELECT product_id, model FROM oc_product';
        $list = $this->query($sql);
        $products = [];
        foreach($list as $item){
            $products[$item['product_id']] = $item['product_id'].' | '.$item['model'];/*дабавлена МОДЕОЛЬ к айдишнику*/
        }       

        $result = [];
        foreach ($attr as $value) {
            unset($products[$value]);
        } 
        $result = $products;

        return $result;
    }

    public function getOrgList($option = false)
    {
    	$sql = 'SELECT *, manufacturer.name AS manufacturer, product_description.name AS name, product.image AS image FROM product LEFT JOIN product_description ON product.product_id = product_description.product_id LEFT JOIN manufacturer ON product.manufacturer_id = manufacturer.manufacturer_id';

        $list = $this->getModel('org')->query($sql);
//echo'<pre>'; var_dump($list); die;
        $result = [];
        foreach($list as $item){
            if($option){ // если указана конкретное поле, то записываем в массив только его
                $result[$item['product_id']] = $item[$option];
            } else {
                $result[$item['product_id']][$item['language_id']] = $item;
            }
        }
        if($option){ return $result; }
        foreach($result as $key => $value){
            $res[$key] = $value[1];
            $res[$key]['name'] = [ 'rus' => $value[1]['name'], 'ukr' => $value[2]['name'] ];
            $res[$key]['description'] = [ 'rus' => $value[1]['description'], 'ukr' => $value[2]['description'] ];
        }
//echo'<pre>'; var_dump($res); die;       
        return $res;        
    }

    public function getProductById($product_id)
    {
        $sql = 'SELECT *, manufacturer.name AS manufacturer, product_description.name AS name, product.image AS image FROM product LEFT JOIN product_description ON product.product_id = product_description.product_id LEFT JOIN manufacturer ON product.manufacturer_id = manufacturer.manufacturer_id WHERE product.product_id='.$product_id;

        $product = $this->getModel('org')->query($sql);

//echo '<pre>'; var_dump($product); die;   

        $result = $product[0];
        $result['name'] = [
            'rus' => $product[0]['name'],
            'ukr' => str_replace("'", "`", $product[1]['name'])
        ];


        $result['description'] = [
            'rus' => $product[0]['description'],
            'ukr' => str_replace("'", "`", $product[1]['description'])
        ];     
// echo '<pre>'; var_dump($result); die;
        return $result;
    }

    public function createProductById($id)
    {
        $product = $this->getProductById($id);
        // echo '<pre>'; var_dump($product['manufacturer_id']); die;

        $res = explode('/',$product['image']);
        $res[0] = 'catalog';
        $product['image'] = implode('/', $res);

        $product['manufacturer_id'] = (include 'config/manufacturer_relation.php')[$product['manufacturer_id']]; 
        
        $str = "(".$product['product_id'].", '".$product['model']."', ".$product['sku'].", 1000, 7, '".$product['image']."', ".$product['manufacturer_id'].", ".$product['price'].", ".$product['status'].")";

        $str2 = "(".$product['product_id'].", 1, '".$product['name']['rus']."', '".$product['description']['rus']."')";

        $str3 = "(".$product['product_id'].", 2, '".$product['name']['ukr']."', '".$product['description']['ukr']."')";

        $sql = 'INSERT INTO oc_product (product_id, model, sku, quantity, stock_status_id, image, manufacturer_id, price, status) VALUES '.$str;
//echo $sql; die;
        $sql2 = 'INSERT INTO oc_product_description (product_id, language_id,  name, description) VALUES '.$str2;    

        $sql3 = 'INSERT INTO oc_product_description (product_id, language_id,  name, description) VALUES '.$str3; 

        $this->db->prepare($sql)->execute();
        $this->db->prepare($sql2)->execute();
        $this->db->prepare($sql3)->execute();

        return $this->query("SELECT * FROM oc_product WHERE product_id=".$id)[0];  
    }

    public function syncProductById($id)
    {
        $product = $this->getProductById($id);
// echo '<pre>'; var_dump($product); die;        
        $product['manufacturer_id'] = (include 'config/manufacturer_relation.php')[$product['manufacturer_id']]; 

        $sql = "UPDATE `oc_product` SET `model`='".$product['model']."',`sku`=".$product['sku'].",`quantity`=".$product['quantity'].",`stock_status_id`=".$product['stock_status_id'].",`manufacturer_id`=".$product['manufacturer_id'].",`price`=".$product['price'].",`status`=".$product['status']." WHERE `product_id`=".$product['product_id'];
        
        $sql2 = "UPDATE `oc_product_description` SET `name`='".$product['name']['rus']."',`description`='".$product['description']['rus']."' WHERE `language_id`=1 AND `product_id`=".$product['product_id'];

        // $product['name']['ukr'] = str_replace("'", "`", $product['name']['ukr']);
        // $product['description']['ukr'] = str_replace("'", "`", $product['description']['ukr']);
        $sql3 = "UPDATE `oc_product_description` SET `name`='".$product['name']['ukr']."',`description`='".$product['description']['ukr']."' WHERE `language_id`=2 AND `product_id`=".$product['product_id'];
         
        $res = $this->db->prepare($sql)->execute();
        $res2 = $this->db->prepare($sql2)->execute();
        $res3 = $this->db->prepare($sql3)->execute();
        // echo '<pre>'; echo($sql3); die;

        return $this->query("SELECT * FROM oc_product WHERE product_id=".$id)[0];
    }    
    /*
    выбираем значения фильтров товаров на atn.org.ua
    */
    public function getOptionProductsOrg($category_id)
    {

        $sql = "SELECT * FROM `product_to_category` LEFT JOIN `product_to_value` ON product_to_category.product_id = product_to_value.product_id WHERE `category_id` = $category_id";       

        $list = $this->getModel('org')->query($sql);

        $options = [];

        foreach ($list as $key => $value) {
            if(!$value['value_id']){
                $value['value_id'] = '';
            }
            $options[$value['product_id']][$value['option_id']] = $value['value_id'];
        }

        return $options;

    }
    /*
        вставляем значения в атрибуты товаров на atnshop.com.ua
    */

    public function putOptionsProductsShop($options)
    {
        $params = include ROOT.'/config/filter_nastenno_potolochnye.php';
//echo '<pre>'; var_dump($options); die;
        $mass = [];
        // foreach($options as $key => $value){
        //     $mass[$key][$params['option']['195']] = $params['value'][$value['195']];
        //     $mass[$key][$params['option']['196']] = $params['value'][$value['196']];
        //     $mass[$key][$params['option']['197']] = $params['value'][$value['197']];
        //     $mass[$key][$params['option']['429']] = $params['value'][$value['429']]; 
        //     $mass[$key][$params['option']['425']] = $params['value'][$value['425']];
   
        //     $mass[$key][$params['option']['432']] = $params['value'][$value['432']];        
        //     $mass[$key][$params['option']['427']] = $params['value'][$value['427']];
        //     $mass[$key][$params['option']['434']] = $params['value'][$value['434']];
        //     $mass[$key][$params['option']['428']] = $params['value'][$value['428']];
        //     $mass[$key][$params['option']['426']] = $params['value'][$value['426']];
        //     $mass[$key][$params['option']['430']] = $params['value'][$value['430']];    
        // }

        foreach($options as $key => $value){
            foreach($value as $k => $val){
                $mass[$key][$params['option'][$k]] = $params['value'][$value[$k]];
            }
        }        

        //echo'<pre>';var_dump($mass);die;


        foreach($mass as $key => $value){
            foreach($value as $k => $val){
                if(!empty($val)){
                    $str .= "(".$key.",".$k.",1,'".$val."'),";
                }
            }
        }
        $str = trim($str,',');

        $sql = "REPLACE INTO `oc_product_attribute`(`product_id`,`attribute_id`,`language_id`,`text`) VALUES $str";
        //echo $sql; die;
        //$res = $this->exec($sql);
        Header('Location: /shop/');

    }
    public function setProductOption($options)
    {
        $str = '';
        $str2 = array();
        foreach($options as $id => $model){
            $str .=" WHEN `product_id` = ".$id." THEN '".$model."'";
            $str2[] = $id; 
        }
        $str2 = implode(',', $str2);
        $sql = "UPDATE `oc_product` SET `model` = CASE $str END WHERE `product_id` IN (".$str2.")";
        $result = $this->db->exec($sql);
        return $rtesult;
    }   
    //Синхронизация по выбранному полю
    public function setOption($option){
        //echo $option; die;
        $list = $this->getOrgList($option);
        //echo '<pre>';var_dump($list);die;
        $str = ''; $str2 = '';
        foreach($list as $key => $value){
            $str .= " WHEN product_id = ".$key." THEN '".addslashes($value)."'";
            $str2 .= $key.','; 
        }
        $str2 = trim($str2,',');

        // switch ($option) {
        //     case 'price':
        //         $table = 'oc_product';
        //         break;
        //     case 'name':
        //         $table = 'oc_product_description';
        //         break;
        //     case 'sku':
        //         $table = 'oc_product';
        //         break;
        //     case 'description':
        //         $table = 'oc_product_description';
        //         break;  
        //     case 'status':
        //         $table = 'oc_product';
        //         break;                               
        // }
        $table = 'oc_product';

        $sql = "UPDATE $table SET $option = CASE $str END WHERE product_id IN ($str2)";
        //echo '<pre>';var_dump($sql);die;
        $result = $this->db->exec($sql);
        return $result;

    } 

    public function upd($arr){

        foreach ($arr as $key => $value) {
            $str .= " WHEN `product_id` =".$key." THEN '".$value."'";
            $str2 .= $key.",";
        }
        $str2 = trim($str2,',');

        $sql = "UPDATE `oc_product_description` SET `description` = CASE $str END WHERE `product_id` IN ($str2) AND `language_id`=2";
        //echo $sql;
        $this->db->exec($sql);
    }
 }

