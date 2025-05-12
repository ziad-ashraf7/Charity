<?php

namespace App\models;

class DonationModel extends BaseModel
{
    protected $table = 'donations';

    public function addDonation($data)
    {
        $query = "INSERT INTO {$this->table} (user_id,campaign_id,payment_card_id,message,amount,donation_type,is_anonymous,created_at) VALUES(:user_id, :campaign_id, :payment_card_id, :message, :amount, :donation_type, :is_anonymous, :created_at)";
        $data['created_at'] = date('Y-m-d H:i:s');
        $this->db->query($query, $data);
    }

    public function getUserDonation($userId)
    {
        $query = "SELECT * FROM {$this->table} WHERE user_id = :user_id";
        return $this->db->query($query, ['user_id' => $userId])->fetchAll();
    }
    public function getTotalDonations($userId)
    {
        $query = "SELECT COUNT(*) as cnt FROM {$this->table} WHERE user_id = :user_id";
        return $this->db->query($query, ['user_id' => $userId])->fetch()->cnt;
    }

    public function getTotalDonationNumber($userId)
    {
        $query = "SELECT SUM(amount) as sum FROM {$this->table} WHERE user_id = :user_id";
        return $this->db->query($query, ['user_id' => $userId])->fetch()->sum;
    }
    public function getActiveRecurring($userId)
    {
        $query = "SELECT COUNT(*) as cnt FROM {$this->table} WHERE user_id = :user_id AND donation_type LIKE '%Recurring%'";
        return $this->db->query($query, ['user_id' => $userId])->fetch()->cnt;
    }
    public function getSupportedCampaigns($userId)
    {
        $query = "SELECT COUNT(DISTINCT campaign_id) as cnt FROM {$this->table} WHERE user_id = :user_id";
        return $this->db->query($query, ['user_id' => $userId])->fetch()->cnt;
    }
}