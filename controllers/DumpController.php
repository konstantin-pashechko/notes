<?php
	/**
	 * 
	 */
	class DumpController extends Controller
	{

		public function actionIndex()
		{
			$methods = get_class_methods($this);
			foreach ($methods as $key => $method) {
				if(substr($method, 0, 6)!=='action' || $method =='actionIndex'){
					unset($methods[$key]);
				} else {
					$methods[$key] = ltrim($method, 'action');
				}
			}

			$this->render('dump', [
				'actions' => $methods
			]);
		}

		private function getCustomerOrders($id)
		{
			return $this->model->getOrdersByCustomerId($id);
		}

		public function actionSumAllOrdersForEachCustomer()
		{
			if($_POST){
				$result = $this->getCustomerOrders($_POST['id']);

				foreach($result as $val){
					$str .= '<p><span class="white">'.$val['order_id'].'</span> __ <span class="total">'.$val['total'].'</span> __ <span class="name">'.$val['firstname'].'</span>__<span class="date">'.$val['date_added'].'</span></p>';
				}
				echo $str;
				die;
			}	

			$orderList = $this->model->getOrderList();
				
			foreach ($orderList as $value) {
				if($value['customer_id']){
					$result .= '<p class="white"><span class="white">'.$value['customer_id'].' </span><a value="'.$value['customer_id'].'" class="yellow">'.$value['email'].'</a> ( <span class="green">'.$value['SUM(total)'].'</span> )</p>';
				}	
			}
			$this->render('dump/order', [
				'orderList' => $result
			]);
		}		

	}