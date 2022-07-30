<?php

    class SiteController extends Controller
    {
        public function actionIndex(){
            if($_POST){
                $id = trim($_POST['id']);
                $coll = trim($_POST['coll']);
                $content = trim($_POST['content']);
                Site::updateTaskList($_POST['id'], $_POST['coll'], $_POST['content']);
            };
        	$list = Site::getTaskList();
        	$this->render('site', [
        		'list' => $list
        	]);
        }
    }