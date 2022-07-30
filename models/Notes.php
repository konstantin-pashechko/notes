<?php

class Notes extends Model
{
    public function getNote()
    {
        $list = $this->selectItem('notes');
        return $list;
    }
    public function updateNote($content)
    {
        // var_dump($content); die;
        $this->exec('UPDATE notes SET content ="'.$content.'"');
        return true;
    }   
}