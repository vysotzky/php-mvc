<?php
namespace Models;

abstract class Model{
    protected $table = '';
    protected $db = '';

    public function  __construct()
    {
        $this->db = \DB::getDB();
    }

    public function add($fields){
        $keys = array_keys($fields);
        $values = array_values($fields);
        $question_marks = array_map(function () { return '?'; }, $keys);

        $sql = 'INSERT INTO '.$this->table.' ('.implode(',',$keys).') VALUES ('.implode(',',$question_marks).')';
        $stmt = $this->db->prepare($sql);
        $stmt->execute($values);
        return $stmt;
    }

    public function all(){
        $sql = 'SELECT * FROM '.$this->table;
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }
}
