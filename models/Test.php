<?php

class Test extends Model
{
	public function get_htm()
	{
		$path = 'tmp/file.htm';
		$cont = file_get_contents($path);
      //echo '<pre>';var_dump($cont);die;
		$res = preg_match_all(
			'~[0-9]{5}</TD>\r?\n<TD CLASS="[A-Z0-9]*">[0-9]+( )?,?[0-9]+,?[0-9]+~',
			$cont, $out);
      //echo '<pre>';var_dump($out[0]);die;
		$result = [];
		foreach ($out[0] as $value) {

			$item = preg_split('~</TD>\r?\n?<TD CLASS="[A-Z0-9]*">~',$value);

			if(array_key_exists($item[0], $result)){ $item[0]= '_'.$item[0];}

			$result[$item[0]] = intval(preg_replace('~,~','.',preg_replace('~ ~', '',$item[1])));

		}
      //echo '<pre>';var_dump($result);die;
		return $result;
	}

	public function getOrgList()
	{
		$sql = "SELECT `sku` FROM `product` WHERE `status` = '1'";
		$list = $this->getModel('org')->query($sql);
		foreach ($list as $key => $value) {
			$result[] = $value['sku'];
		}
		return $result;
	}	

	public function setNotExist($not_exist)
	{
		foreach ($not_exist as $key => $value) {
			$str .= $value.',';
		}
		$str = trim($str,',');
		$sql = "UPDATE `product` SET `stock_status_id` = 8, `quantity` = 0 WHERE `sku` IN ($str)";
		print_r($sql);die;
		return $this->getModel('org')->exec($sql);
	}	

}