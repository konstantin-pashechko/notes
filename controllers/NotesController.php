<?php 

    class NotesController extends Controller
    {
        public function actionIndex(){
            if($_POST){
                $content = trim($_POST['content']);
                $this->model->updateNote($content);
            }

            $list = $this->model->getNote();
        	$this->render('notes', [
                'note' => $list,
            ]);
        }
    }