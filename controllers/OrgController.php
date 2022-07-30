<?php

/**
 * Контроллер CartController
 */
class OrgController extends Controller
{
    public $schneider;
    function __construct(){
        $schneiderPath = ROOT . '/config/schneider.php';
        $this->schneider = include($schneiderPath);
    }
    private function getPrice($item)
    {
        if($item['manufacturer_id'] == '37'){ // E.Next
            return $item['price'] * 1.3;
        } elseif($item['manufacturer_id'] == '7'){ // GeneralElectric
            return $item['price'] * 1.25;
        }elseif($item['manufacturer_id'] == '80'){ // ElectoHouse
            return $item['price'] * 1.6;
        }elseif($item['manufacturer_id'] == '95'){ // ZUBR
            return $item['price'] * 1.35;
        } elseif($item['manufacturer_id'] == '44'){ // НоватекЭлектро
            return $item['price'] * 1.16;
        } elseif($item['manufacturer_id'] == '85'){ // Lebron
            return $item['price'] * 1.1;
        } elseif($item['manufacturer_id'] == '92'){ // Velmax
            return $item['price'] * 1.1;
        } elseif($item['manufacturer_id'] == '84'){ // TechnoSystem TNSY
            return $item['price'] * 1.25;
        } elseif($item['manufacturer_id'] == '142'){ // DigiTop
            return $item['price'] * 1.25;    
        } elseif($item['manufacturer_id'] == '121'){ // VIDEX
            return $item['price'] * 1.2;    
        } elseif ($item['manufacturer_id'] == '6') { // SchneiderElectric
            return $item['price'] * $this->schneider[$item['category_id']];
        } else {
            return false;
        }
    }

    public function actionIndex()
    {
        $_SESSION['org'] = array();
        if(file_exists(ROOT.'/content/file.csv')){
            unlink(ROOT.'/content/file.csv');
        }
    //$this->model->func(); //Вспомагательная функция
        // Подключаем вид
        $this->render('org');
        return true;
    }
///////////////////////////////////////////////////////////////////////////////////////////

    public function actionUpload()
    {
        if(isset($_POST['submit'])){
            if (is_uploaded_file($_FILES["file"]["tmp_name"])) {
              move_uploaded_file($_FILES["file"]["tmp_name"], ROOT . "/tmp/file.csv");
          } else {
            header('Location: /org/'); exit;
        }
    }

    if (file_exists(ROOT.'/tmp/file.csv')){
        $file = ROOT.'/tmp/file.csv';
                //получаем массив из файла CSV
        if (($handle = fopen($file, "r")) !== FALSE) {
            while (($data = fgetcsv($handle, 1000, ";")) !== FALSE) {
                if($data[0]){
                    $list[]=$data;
                }
            }
        }
        fclose($handle);

            // echo '<pre>';
            // var_dump($list);die;
            // echo '</pre>';
    }
    $_SESSION['org']['upload'] = true;
        // Подключаем вид
    $this->render('org',[
        'list' => $list,
    ]);
    return true;
}

/////////////////////////////////////////////////////////////////////////////////////////////

public function actionUpdate()
{
    if (file_exists(ROOT.'/tmp/file.csv')){
        $file = ROOT.'/tmp/file.csv';
                //получаем массив из файла CSV
        if (($handle = fopen($file, "r")) !== FALSE) {
            while (($data = fgetcsv($handle, 1000, ";")) !== FALSE) {
                $list[]=$data;
            }
        }
//$this->dump($list);
        fclose($handle);
        $this->model->updatePricesBySkus($list);
    }
        // Получаем массив только что обновленных позиций
    $return_list = $this->model->getProductsBySkus($list);
    foreach($return_list as $key => $item){
        if ($price = $this->getPrice($item)){
            $item['price'] = $price;
            $result_list[] = array_values($item);
        } else {
            unset($return_list[$key]);
        }
    }

// echo '<pre>';var_dump($result_list); die;
        //Обновляем данные из полученного массива
$this->model->updatePricesBySkus($result_list);

        //$this->dump($return_list);

    unset($_SESSION['org']['upload']);
    $_SESSION['org']['update'] = true;
//$this->dump($list);
    $list = $this->model->getProductsBySkus($list);

//$this->dump($list);
        // Подключаем вид
    $this->render('org', [
        'list' => $list,
    ]);
    //require_once(ROOT . '/views/site/index.php');
    return true;
}
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
public function actionPriceup()
{
    //Получаем дополнительные опции
    $options = $this->getOption();

//Извлекаем из БД все товары, требуемых производителей
    $manufacturers = include(ROOT . '/config/manufacturers.php');
    $keys = array();
    foreach ($manufacturers as $key => $value) {
        $keys[] .= $key;
    }
    $productList = Product::getProductsByManufacturers($keys);
//Умножаем их цену на соответствующий коэффициент
    $ids = array();
    foreach($productList as $key => &$product){
        $product['price'] = $product['price']*$manufacturers[$product['manufacturer_id']];
        if($product['manufacturer_id']==$options[0]){
            $ids[] .= $product['product_id'];
        }
    }
//получаем массив с id, товаров, которые соответствуют опциям (из таблицы "product_to_category") | передаем массив с id по производителю и id категории
    $additionList = Product::getProductsByOptions($ids, $options[1]);
//умножаем price на нужный коэффициент элементов массива productList, которые есть в additionList и удаляем ненужные элементы, которые есть в ids, но нет в additionList
    foreach($productList as $key => &$product){
        if($product['manufacturer_id']==$options[0]){
            if(in_array($product['product_id'], $additionList)){
                $product['price'] = $product['price']*$options[2];
            } else {
                unset($productList[$key]);
            }
        } else {
            continue;
        }
    }
        //Обновляем данные из массива productList
    $updated = Product::updatePricesByIds($productList);
    echo '<pre>';

    $list = Product::getProductsByIds($updated);
    $_SESSION['org']['update'] = true;

        // Подключаем вид
    require_once(ROOT . '/views/site/index.php');
    return true;
}

private function getOption()
{
//Извлекаем из БД все товары, соответствующие требованиям $options;
    $options = include(ROOT . '/config/options.php');
    $params = array(); $i=1;
    foreach ($options as $key => $value) {
        $params[$i] = explode('/', $key);
        $params[$i][] = $value;
    }

    return $params[1];
}
 public function actionAsfora()
    {
$options = $this->getOption();
$keys = array(); $keys[] = $options['0'];
$productList = Product::getProductsByManufacturers($keys);
$ids = array();
foreach ($productList as $product){
    $ids[] .= $product['product_id'];
}
$asforaList = Product::getProductsByOptions($ids, $options[1]);
 foreach($productList as $key => &$product){
        if($product['manufacturer_id']==$options[0]){
            if(in_array($product['product_id'], $asforaList)){
                $product['price'] = $product['price']*$options[2];
            } else {
                unset($productList[$key]);
            }
        } else {
            continue;
        }
    }
    $updated = Product::updatePricesByIds($productList);
    $list = Product::getProductsByIds($updated);
$_SESSION['org']['update'] = true;
        // Подключаем вид
        require_once(ROOT . '/views/site/index.php');
        return true;
    }
/*
**
*/
    public function actionTranslate()
    {
        $sql = "SELECT `product_id`,`name` FROM `product_description` WHERE `language_id`=2 ORDER BY `product_id` ASC";
        $res = $this->model->query($sql);
                foreach($res as $key => $item){
            if($key>-1 && $key<9000){
                $result[$item['product_id']] = $item['name'];
            }
        }

echo '<pre>'; print_r($result);die;

        $data = include ROOT.'/tmp/translate.php';

        foreach ($data as $key => $item){

            $str .= " WHEN `product_id`=".$key." THEN '".htmlentities($data[$key])."'";
            $str2 .= $key.',';
        }
        $str2 = trim($str2,',');

        /*РАБОТАЕТ(для одного)!!!*/
        //$sql = "UPDATE `product_description` SET `description` ='".htmlentities($data['225'])."' WHERE `product_id` = '225' AND `language_id`=2";

        $sql = "UPDATE `product_description` SET `name` = CASE".$str." END WHERE `product_id` IN (".$str2.") AND `language_id`=2";

//echo $sql;die;
        $resu = $this->model->exec($sql);
        var_dump($resu);
        echo '<pre>'; print_r($sql);die;

        //echo '<pre>'; echo htmlentities($data['544']);


    }
}
