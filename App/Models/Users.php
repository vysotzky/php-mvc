<?php

namespace App\Models;

class Users extends Model
{
    public function lastFive()
    {
        $sql = 'SELECT * FROM users ORDER BY id DESC LIMIT 5';
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }
}
