<?php

class Site
{
	public static function getTaskList()
	{
		$db = Db::Connection('site');
		$sql = 'SELECT * FROM rows';
		$result = $db->query($sql);
		$result->setFetchMode(PDO::FETCH_ASSOC);
		$list = array();
		$i = 0;
		while ($row = $result->fetch()) {
			$list[$i]['id'] = $row['id'];
			$list[$i]['section'] = $row['heading'];
			$list[$i]['task'] = $row['task'];
			$list[$i]['progress'] = $row['progress'];
			$i++;
		}
		return $list;
	}

	public static function updateTaskList($id, $coll, $value)
	{
		$db = Db::Connection('site');
		$sql = 'UPDATE rows SET '.$coll.' = "'.$value.'" WHERE id = '.$id;
		$result = $db->exec($sql);
		return true;
	}	

}