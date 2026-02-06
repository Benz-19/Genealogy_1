<?php
namespace App\Services\Referral;

use App\Services\Referral\ReferralServiceInterface;
use Exception;
use App\Models\Database\DB;

class ReferralService implements ReferralServiceInterface{
    private $db;

    public function __construct()
    {
        $this->db = new DB();
    }

    public function generateUniqueCode(string $username): string
    {
        // Generate codes until we find one that isn't in the DB
        do {
            if(!empty($username)){
                $newCode = "REF-$username-" . strtoupper(bin2hex(random_bytes(3)));
                }else{
                $newCode = "REF-" . strtoupper(bin2hex(random_bytes(3)));
            }
        } while ($this->validateCode($newCode));

        return $newCode;
    }

    public function validateCode(string $code): bool
    {
        // fetchSingleData returns the code if found, or false if not.
        $query = "SELECT referral_code FROM genealogy_users WHERE referral_code = :code LIMIT 1";
        $result = $this->db->fetchSingleData($query, [':code' => $code]);

        return (bool)($result ?? false); 
    }
}