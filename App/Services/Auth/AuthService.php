<?php
namespace App\Services\Auth;

use App\Services\Auth\AuthServiceInterface;
use App\Models\Database\DB;
use App\Services\Genealogy\GenealogyService;
use App\Services\Referral\ReferralService;
use App\Services\User\UserService;
use Exception;

class AuthService implements AuthServiceInterface {
    private $db;
    private $genealogy_service;

    public function __construct() {
        $this->db = new DB(); //establish db connection
        $this->genealogy_service = new GenealogyService();
    }

    // REGISTER
    public function register(array $data, ?string $providedReferralCode): bool {
        if (empty($data['username'])) {
                throw new Exception("Username is required.");
            }
        if (empty($data['email'])) {
            throw new Exception("Email address cannot be blank.");
        }

       try {
           $this->db->beginTransaction();

            //Check if the user already exists
            $user = new UserService();
            $is_user = $user->isUser($data['email']);

            if($is_user === true){
                throw new Exception("User already exists.");                
            }
            // Validate if the code exists
            $referral_service = new ReferralService();
            $code_exists = $referral_service->validateCode($providedReferralCode);

            if(!$code_exists && !empty($providedReferralCode)){
                throw new Exception("Invalid code.");               
            }

            //Find REFERRER user (old/existing user) based on the code provided
            $referrerId = null;
            if (!empty($providedReferralCode)) {
                $referrer = $this->db->fetchSingleData("SELECT id FROM genealogy_users WHERE referral_code = :code", [
                    ':code' => $providedReferralCode
                ]);
                $referrerId = $referrer ? $referrer['id'] : null;
            }

            //Generate a NEW unique code for the new User
            $myNewCode = $referral_service->generateUniqueCode($data['username']);

            $query = "INSERT INTO genealogy_users (username, email, referral_code, referrer_id) 
                    VALUES (:u, :e, :my_code, :ref_id)";
            
            $this->db->execute($query, [
                ':u' => $data['username'],
                ':e' => $data['email'],
                ':my_code' => $myNewCode,
                ':ref_id' => $referrerId
            ]);

            $db_id = $this->db->fetchSingleData("SELECT id FROM genealogy_users WHERE email=:e", [":e"=>$data['email']]);
            $new_user_id = $db_id['id'];
            // Create a link
            if(!empty($referrerId) || $referrerId !== null){
                // Self-relationship (depth 0) and Inherit ancestors from the upline
                
                $this->genealogy_service->linkMember($referrerId, $new_user_id);
                }else{
                    throw new Exception("Failed to create new upline - downlint link.");
            }

            $this->db->commit();
            return true;
        } catch (Exception $e) {
            $this->db->rollBack();
            throw $e;
        }
    }

    // LOGIN
    public function login(string $email, string $password): bool
    {
        // IMPORTANT NOTE, $password does nothing
        if(empty($email) || empty($password)){
            return false;
        }
        try{
            $this->db->beginTransaction();
            $query = "SELECT * FROM genealogy_users WHERE email=:e";
            $params = [':e' => $email]; 
            $result = $this->db->fetchSingleData($query, $params);

            if(!$result){
                throw new Exception("User does not exists.");
            }

            // commit
            $this->db->commit();
            return true; 
        }catch(Exception $error){
            $this->db->rollBack();
            error_log("Login Error: " . $error->getMessage());
            return false;
        }
    }

    // LOGOUT
    public function logout(): void
    {
        throw new \Exception('Not implemented yet');
    }
}