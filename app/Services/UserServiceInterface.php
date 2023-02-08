<?php

namespace App\Services;

use App\Models\User;
use App\Models\UserVerifyToken;
use Exception;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Database\Eloquent\Collection;

interface UserServiceInterface
{
    /**
     * Make authenticate token
     *
     * @param string $token
     * @param int $expiresIn
     * @return array
     */
    public function makeToken(string $token, int $expiresIn): array;

    /**
     * Create new user
     * If $activated is false, system will send activation email to user email
     *
     * @param array $data
     * @return User|null
     */
    public function createUser(array $data): ?User;

    /**
     * @param string $provider
     * @param string $providerId
     * @return User|null
     */
    public function findUserByProvider(string $provider, string $providerId):? User;

    /**
     * Get verify token data
     *
     * @param string $token
     * @param int $userId
     * @return UserVerifyToken|null
     */
    public function getTokenData(string $token, int $userId): ?UserVerifyToken;

    /**
     * Update user
     *
     * @param int $userId
     * @param array $data
     * @return User|null
     */
    public function updateUser(int $userId, array $data): ?User;

    public function findUserByEmail(string $email):? User;

    public function getAvatarFromName($name): string;

    public function dispatchSendVerifyEmail($user):? User;
}
