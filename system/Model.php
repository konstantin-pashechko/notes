<?php

class Model
{ 
    public $db; // (object) объект PDO для работы с базой данных
    public $url; // (string) ссылка на XML-файл с товарами на PROM.UA

    function __construct($config)
    {
        $params = include (ROOT.'/config/'.$config.'_db.php');
        if(!$params){
            $params = include (ROOT.'/config/site_db.php');
        }

        $dsn = "mysql:host={$params['host']};dbname={$params['dbname']}";
        $db = new PDO($dsn, $params['user'], $params['password']);
        $db->exec("set names utf8");
        $this->db = $db;
        $this->url = $params['url'];  
        // echo '<pre>';var_dump($this->url);die;
    }

    protected function getModel($model){
        return new Model($model);
    }    

    public function selectAll($table, $fields='*')
    {
        $sql = 'SELECT '.$fields.' FROM '.$table; 
        $result = $this->db->query($sql);
        $result->setFetchMode(PDO::FETCH_ASSOC);
        $result = $result->fetchAll();
        return $result;
    }

        public function selectItem($table, $fields='*')
    {
        $sql = 'SELECT '.$fields.' FROM '.$table; 
        $result = $this->db->query($sql);
        $result->setFetchMode(PDO::FETCH_ASSOC);
        $result = $result->fetch();
        return $result;
    }

    public function getFields($table)
    {
        $sql = 'DESCRIBE '.$table;
        $result = $this->db->query($sql);
        $result->setFetchMode(PDO::FETCH_ASSOC);
        $list = array();
        $i = 0;
        while ($row = $result->fetch()) {
            $list[$i] = $row['Field'];
            $i++;
        }        
        return $list;
    }   

    public function query($sql)
    {
        $result = $this->db->query($sql);
        $result->setFetchMode(PDO::FETCH_ASSOC);
        while ($row = $result->fetch()){
            $res[] = $row;
        }
        return $res;
    }

    public function exec($sql)
    {
        $result = $this->db->exec($sql);
        return $result;
    }
}