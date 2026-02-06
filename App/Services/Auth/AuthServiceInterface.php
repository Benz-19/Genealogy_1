<?php
namespace App\Services\Auth;

interface AuthServiceInterface{
    public function register(array $data, string $referralCode): bool;
    public function login(string $email, string $password): bool;
    public function logout(): void;
}