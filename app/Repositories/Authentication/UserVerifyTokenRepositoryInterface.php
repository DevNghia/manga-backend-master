<?php

namespace App\Repositories\Authentication;

use App\Models\User;
use App\Models\UserVerifyToken;

interface UserVerifyTokenRepositoryInterface
{
    /**
     * Create verify email token
     * @param User $user
     * @return UserVerifyToken|null
     */
    public function create(User $user): ?UserVerifyToken;

    /**
     * Find verify info by token
     * @param string $token
     * @param int $userId
     * @return UserVerifyToken|null
     */
    public function findByToken(string $token, int $userId): ?UserVerifyToken;

    /**
     * Update verified status
     * @param UserVerifyToken $userVerifyToken
     * @return UserVerifyToken|null
     */
    public function verifyEmailSuccess(UserVerifyToken $userVerifyToken): ?UserVerifyToken;

    /**
     * Disable old token by User id
     *
     * @param int $userId
     * @return void
     */
    public function disableTokenByUserId(int $userId): void;
}
