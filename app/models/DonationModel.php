<?php

namespace App\models;

class DonationModel extends BaseModel
{
    protected $table = 'donations';

    private function getDonationsNumber()
    {
        $query = "SELECT COUNT(*) as cnt FROM {$this->table}";
        return $this->db->query($query)->fetch()->cnt;
    }

    public function addDonation($data)
    {
        $donationId = '#' . ($this->getDonationsNumber() + 168483);
        $data['donation_id'] = $donationId;
        $query = "INSERT INTO {$this->table} (user_id,campaign_id,payment_card_id,message,amount,donation_type,is_anonymous,donation_id,created_at) VALUES(:user_id, :campaign_id, :payment_card_id, :message, :amount, :donation_type, :is_anonymous,:donation_id, :created_at)";
        $data['created_at'] = date('Y-m-d H:i:s');
        $this->db->query($query, $data);
    }

    public function getbyId($id)
    {
        $query = "SELECT * FROM {$this->table} WHERE id = :id";
        return $this->db->query($query, ['id' => $id])->fetchAll();
    }

    public function getByIdWithJoin($id)
    {
        $query = "SELECT * FROM {$this->table} 
        JOIN campaigns ON {$this->table}.campaign_id = campaigns.id
        JOIN user_payment_cards ON {$this->table}.payment_card_id = user_payment_cards.id
        WHERE {$this->table}.id = :id";
        return $this->db->query($query, ['id' => $id])->fetch();
    }

    public function getAllDonations()
    {
        $query = "SELECT   
    {$this->table}.id AS donationId,
    campaigns.id AS campaignId,
    {$this->table}.*, 
    campaigns.*
    FROM  {$this->table} JOIN campaigns ON {$this->table}.campaign_id = campaigns.id";
        return $this->db->query($query)->fetchAll();
    }

    public function getUserDonation($userId)
    {
        $query = "SELECT    
    {$this->table}.id AS donationId,
    campaigns.id AS campaignId,
    {$this->table}.*, 
    campaigns.* FROM {$this->table}
         JOIN campaigns ON {$this->table}.campaign_id = campaigns.id
         WHERE user_id = :user_id";
        return $this->db->query($query, ['user_id' => $userId])->fetchAll();
    }

    public function getTotalDonations($userId = null)
    {
        if ($userId) {
            $query = "SELECT COUNT(*) as cnt FROM {$this->table} WHERE user_id = :user_id";
            return $this->db->query($query, ['user_id' => $userId])->fetch()->cnt;
        }
        $query = "SELECT COUNT(*) as cnt FROM {$this->table}";
        return $this->db->query($query)->fetch()->cnt;
    }

    public function getTotalDonationNumber($userId = null)
    {
        if ($userId) {
            $query = "SELECT SUM(amount) as sum FROM {$this->table} WHERE user_id = :user_id";
            return $this->db->query($query, ['user_id' => $userId])->fetch()->sum;
        }
        $query = "SELECT SUM(amount) as sum FROM {$this->table}";
        return $this->db->query($query)->fetch()->sum;
    }

    public function getActiveRecurring($userId = null)
    {
        if ($userId) {
            $query = "SELECT COUNT(*) as cnt FROM {$this->table} WHERE user_id = :user_id AND donation_type LIKE '%Recurring%'";
            return $this->db->query($query, ['user_id' => $userId])->fetch()->cnt;
        }
        $query = "SELECT COUNT(*) as cnt FROM {$this->table} WHERE donation_type LIKE '%Recurring%'";
        return $this->db->query($query)->fetch()->cnt;
    }

    public function getSupportedCampaigns($userId = null)
    {
        if ($userId) {
            $query = "SELECT COUNT(DISTINCT campaign_id) as cnt FROM {$this->table} WHERE user_id = :user_id";
            return $this->db->query($query, ['user_id' => $userId])->fetch()->cnt;
        }
        $query = "SELECT COUNT(DISTINCT campaign_id) as cnt FROM {$this->table}";
        return $this->db->query($query)->fetch()->cnt;
    }

    public function markInActive($id)
    {
        $query = "UPDATE {$this->table} SET is_active = 0 WHERE id = :id";
        $this->db->query($query, ['id' => $id]);
    }
}