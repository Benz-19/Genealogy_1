<?php
namespace App\Services\Referral;

interface ReferralServiceInterface{
    public function generateUniqueCode(string $username): string;
    public function validateCode(string $code): ?bool;
}