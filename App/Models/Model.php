<?php

namespace App\Models;

abstract class Model
{
    protected $tableAbstraction = true; // if true, model represents a database table
    protected $table = '';
    protected $db = '';

    public function __construct()
    {
        if ($this->tableAbstraction == true) {
            $this->db = \Database::getDB();
            if ($this->table == '') {
                $this->table = strtolower(str_replace(__NAMESPACE__ . "\\", '', get_class($this)));
            }
        }
    }

    public function add($fields)
    {
        if ($this->tableAbstraction == false) {
            return;
        }
        $keys = array_keys($fields);
        $values = array_values($fields);
        $question_marks = array_map(function () {
            return '?';
        }, $keys);

        $sql = 'INSERT INTO ' . $this->table . ' (' . implode(',', $keys) . ') VALUES (' . implode(',', $question_marks) . ')';
        $stmt = $this->db->prepare($sql);
        $stmt->execute($values);
        return $stmt;
    }

    public function all()
    {
        if ($this->tableAbstraction == false) {
            return;
        }

        $sql = 'SELECT * FROM ' . $this->table;
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }
}
