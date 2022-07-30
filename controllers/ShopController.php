<?php 

    class ShopController extends Controller
    {

    	private function getModel($model){
    		return new Model($model);
    	}

        public function actionIndex()
        {
            $this->render('shop');
        }

        public function actionExist()
        {
            $orgList = $this->model->getOrgList();
//$this->dump($orgList);            

            $shopList = $this->model->getShopList();
//$this->dump($shopList); 

	    	$result = $this->exist($orgList, $shopList, false);

	    	sort($result);

    	    $this->render('shop', [
                'shopList' => $result,
                'exist' => true,
                'created' => $_SESSION['created']
            ]);	 		
        }

        public function actionAttributes()
        {
            $result = $this->model->getShopNotAttr();
            $count = count($result);

            $this->render('shop', [
                'list' => $result,
                'count' => $count,
                'attr' => true
            ]);         
        }

        public function actionMatch()
        {
            
            $shopList = $this->model->getShopList();

            $orgList = $this->model->getOrgList();

            $result = $this->match($orgList, $shopList);

            sort($result);

            $count = count($result);

            //$result = array_slice($result, 0, 10);

            $this->render('shop', [
                'shopList' => $result,
                'count' => $count,
                'match' => true
            ]);         
        }
                
        public function actionCreate($param)
        {
            $result = $this->model->createProductById($param);
            // $this->dump($result);die;
            $this->writeLogs($result['product_id']);
            $_SESSION['created'][] = $result['product_id'];
            
            header('Location: /shop/exist/');
        }   

        public function actionUnset($param)
        {
            if($param!=='all'){
                $key = array_search($param, $_SESSION['created']);
                unset($_SESSION['created'][$key]);
            } else {
                unset($_SESSION['created']);
            }    
            header('Location: /shop/exist/');
        }   
        //Синхронизация одного выбранного товара
        public function actionSync($param)
        {
            //echo $param;die;
            if($param){
                $this->model->syncProductById($param);
            }    

            header('Location: /shop/match/');
        }                  
    //Синхронизация всех товаров по выбранному полю:
        public function actionSyncAll()
        {
            if($_POST['submit']){
                //$this->dump($_POST);
                $result = $this->model->setOption($_POST['option']);
            }   

            $this->render('shop', [
                'result' => $result,
                'sync' => true
            ]);
            //header('Location: /shop/');
        } 
/*
    метод для импорта опций фильтра товаров из ORG в SHOP
*/
        public function actionFilter()
        {
           $options = $this->model->getOptionProductsOrg(61);
           
           $result = $this->model->putOptionsProductsShop($options);
           //$this->dump($options);
        }  

        public function actionUpdateProductOption($options)
        {
            $orgList = $this->model->getOrgList();
            $models = [];
            foreach($orgList as $product){
                $models[$product['product_id']] = $product['model'];
            }
            $this->model->setProductOption($models);
            // $this->dump($models);
        }         

    }