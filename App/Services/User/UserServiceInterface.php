<?php
namespace App\Services\User;

interface UserServiceInterface{
    public function isUser(string $email):bool;
    public function userId(string $email);
    public function getUserByEmail(string $email);
    public function updateProfile(int $userId, array $data): bool;
    public function getTopPerformers(int $limit): array; //for hall of fame/league logic
}