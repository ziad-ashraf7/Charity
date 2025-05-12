<?php

namespace App\models;

class UserPaymentCardModel extends BaseModel
{
    protected $table = 'user_payment_cards';

    public function getPaymentMethods($userId)
    {
        $query = "SELECT * FROM {$this->table} WHERE user_id = :user_id ORDER BY created_at DESC";
        return $this->db->query($query, [
            'user_id' => $userId,
        ])->fetchAll();
    }

    public function insert($data)
    {
        $query = "INSERT INTO {$this->table} (user_id,cardholder_name,card_number,expiration_date,cvv,is_default, created_at) VALUES ( :user_id, :cardholder_name, :card_number, :expiration_date, :cvv, :is_default, :created_at)";
        $data['created_at'] = date('Y-m-d H:i:s');
        $this->db->query($query, $data);
    }

    public function delete($id)
    {
        $query = "DELETE FROM {$this->table} WHERE id = :id";
        $this->db->query($query, ['id' => $id]);
    }

    public function setAsDefault($userId, $id)
    {
        $query = "UPDATE {$this->table} SET is_default = 0 WHERE user_id = :user_id";
        $this->db->query($query, ['user_id' => $userId]);
        $query = "UPDATE {$this->table} SET is_default = 1 WHERE id = :id";
        $this->db->query($query, ['id' => $id]);

    }
}