<?php
class Org extends Model
{
  public function updatePricesBySkus($options)
  {
    $str = '';
    $str2 = array();
    foreach($options as $item){
        if($item[0]){
            $str .= " WHEN sku = ".$item[0]." THEN ".$item[1];
            $str2[] = $item[0];
        }
    }
        // echo '<pre/>';
        // var_dump($str2); die;
    $str2 = implode(',', $str2);

        // Текст запроса к БД
    $sql = "UPDATE product SET price = CASE".$str." END WHERE sku IN (".$str2.")";
        //echo $sql; die;
        // Получение и возврат результатов. Используется подготовленный запрос
    $result = $this->db->exec($sql);

        // echo "<pre>";
        // print_r($result);
        // print_r($db->errorInfo());
        // echo '</pre>';exit;
}

public function getProductsBySkus($list)
{ 
    foreach($list as $item){
        if($item[0]){$skusArray[] = $item[0];}
    }
        // Соединение с БД

        // Превращаем массив в строку для формирования условия в запросе
    $skusString = implode(' ,', $skusArray);
        // Текст запроса к БД
    $sql = "SELECT * FROM product LEFT JOIN product_to_category ON product.product_id = product_to_category.product_id WHERE sku IN ($skusString)";
//echo $sql; die;
    $result = $this->db->query($sql);
        // echo "<pre>";
        // print_r($result);
        // print_r($db->errorInfo());
        // echo '</pre>';exit;

        // Указываем, что хотим получить данные в виде массива
    $result->setFetchMode(PDO::FETCH_ASSOC);

        // Получение и возврат результатов

    $list = array();
    while ($row = $result->fetch()) {
        $list[$row['product_id']]['sku'] = $row['sku'];
        $list[$row['product_id']]['price'] = $row['price'];
        $list[$row['product_id']]['manufacturer_id'] = $row['manufacturer_id'];
        $list[$row['product_id']]['category_id'] = $row['category_id'];
        $i++;
    }
//echo '<pre>';var_dump($list); die;
    return $list;
}
/*
Вспомагательная функция
*/
public function func()
{
    $sql = "SELECT `product_id` FROM `product_to_category` WHERE category_id = 73";
    $result = $this->db->query($sql);

    $result->setFetchMode(PDO::FETCH_ASSOC);
    $result = $result->fetchAll(); 

    foreach ($result as $value) {
        $str.= '('.$value['product_id'].',163,21),';
    }
    $str = trim($str,',');
    $sql = "REPLACE INTO `product_to_value` (`product_id`, `value_id`, `option_id`) VALUES ".$str; 
    $result = $this->db->exec($sql);
    return $result;

}

private function parseHTM(){

    $file = DIR_TMP . 'quantity.htm';

    $cont = file_get_contents($file);

    preg_match_all('~>[0-9]{5}</TD>\r?\n<TD CLASS="[A-Z0-9]*">[0-9]+( )?,?[0-9]+,?[0-9]+~',$cont, $out);

    foreach ($out[0] as $key => $value) {

        $items = preg_split('~</TD>\r?\n?<TD CLASS="[A-Z0-9]*">~',$value);

        $result[trim($items[0],'>')] = preg_replace('~,~','.',preg_replace('~ ~', '',$items[1]));

    }

    return $result;   
} 

}