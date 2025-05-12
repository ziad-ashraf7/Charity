<?php

namespace App\models;

class CampaignModel extends BaseModel
{
    public function insert($data)
    {
        $query = 'INSERT INTO campaigns (name,img, admin_id, created_at) VALUES (:name, :img, :admin_id, :created_at)';
        $data['created_at'] = date('Y-m-d H:i:s');
        //inspectAndDie($data);
        $this->db->query($query, $data);
    }

    public function getByAdminId($data)
    {
        $query = 'SELECT * FROM campaigns WHERE admin_id = :admin_id';
        return $this->db->query($query, $data)->fetchAll();
    }
    public function getAll()
    {
        $query = 'SELECT * FROM campaigns';
        return $this->db->query($query)->fetchAll();
    }

    public function getById($id)
    {
        $query = 'SELECT * FROM campaigns WHERE id = :id';
        return $this->db->query($query, [
            'id' => $id
        ])->fetch();
    }

    public function update($data)
    {
        $query = 'update campaigns SET name = :name ,img = :img, admin_id= :admin_id , created_at = :created_at';
        $data['created_at'] = date('Y-m-d H:i:s');
        //inspectAndDie($data);
        $this->db->query($query, $data);
    }
}