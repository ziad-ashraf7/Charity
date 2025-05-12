<?php

namespace App\models;

use Core\Database;

class UserModel extends BaseModel
{
    public function signUp($data)
    {
        $query = 'INSERT INTO users (first_name,last_name, email, phone, password, created_at) VALUES (:first_name, :last_name, :email, :phone, :password, :created_at)';
        $data['created_at'] = date('Y-m-d H:i:s');
        unset($data['confirm_password']);
        //inspectAndDie($data);
        $this->db->query($query, $data);
    }

    public function getLastUserId()
    {
        return $this->db->query('SELECT id FROM users ORDER BY created_at DESC LIMIT 1')->fetch()->id;
    }

    public function getUserByEmail($email)
    {
        $query = 'SELECT * FROM users WHERE email = :email';
        return $this->db->query($query, ['email' => $email])->fetch();
    }

    public function getUserById($id)
    {
        $query = 'SELECT * FROM users WHERE id = :id';
        return $this->db->query($query, ['id' => $id])->fetch();
    }

    public function getAllUsers()
    {
        $query = 'SELECT * FROM users ORDER BY created_at DESC';
        return $this->db->query($query)->fetchAll();
    }

    public function updatePersonalInfo($data)
    {
        $query = 'UPDATE users SET first_name = :first_name, last_name = :last_name, email = :email, phone = :phone, city = :city, address = :address, country = :country , created_at=:created_at WHERE id = :id';
        $data['created_at'] = date('Y-m-d H:i:s');
        $this->db->query($query, $data);
    }

    public function updateImage($data)
    {
        $query = 'UPDATE users SET img = :img, created_at=:created_at WHERE id = :id';
        $data['created_at'] = date('Y-m-d H:i:s');
        $this->db->query($query, $data);
    }

    public function updatePassword($data)
    {
        $query = 'UPDATE users SET password = :password, created_at = :created_at WHERE id = :id';
        $data['created_at'] = date('Y-m-d H:i:s');
        $this->db->query($query, $data);
    }
//    public function deleteUser($userId)
//    {
//        $this->db->query('DELETE FROM Cart WHERE user_id = :user_id', ['user_id' => $userId]);
//
//        $this->db->query('DELETE FROM Wishlist WHERE user_id = :user_id', ['user_id' => $userId]);
//
//        $query = 'DELETE FROM users WHERE user_id = :user_id';
//        return $this->db->query($query, ['user_id' => $userId])->rowCount() > 0;
//    }
}
