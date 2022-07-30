<?php

	/**
	 * 
	 */
	class Dump extends Model
	{

		public function getOrderList()
		{
			$sql = 'SELECT email, customer_id, date_added, SUM(total) FROM `order` GROUP BY customer_id ORDER BY SUM(total) DESC LIMIT 100';
			return $this->getModel('org')->query($sql);
			 		
		}

		public function getOrdersByCustomerId($id)
		{
			$sql = 'SELECT order_id, email, firstname, date_added, total FROM `order` WHERE customer_id='.$id.' ORDER BY date_added DESC';
			return $this->getModel('org')->query($sql);
		}		

	}