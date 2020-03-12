<?php
namespace Models;


class Positions extends Model
{
    protected $table = 'positions';

    public function recent(){
        $sql = 'SELECT *
FROM positions
WHERE id IN (
    SELECT MAX(id)
    FROM positions
    GROUP BY user
) and time > :time;';
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['time'=>time()-300]);
        return $stmt->fetchAll();
    }
}