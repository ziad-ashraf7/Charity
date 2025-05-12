<?php

namespace App\models;

class AdminModel extends BaseModel
{
    public function getAdminByEmail($email)
    {
        $query = 'SELECT * FROM admins WHERE email = :email';
        return $this->db->query($query, ['email' => $email])->fetch();
    }
    public function addNewAdmin($data){
        $query = 'INSERT INTO admins (name, email, password,created_at) VALUES (:name, :email, :password,:created_at)';
        $data['created_at'] = date('Y-m-d H:i:s');
        $this->db->query($query, $data);
    }
}