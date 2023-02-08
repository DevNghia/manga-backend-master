<?php

namespace App\Repositories\Authentication;

use App\Models\ResetPasswordToken;

interface ResetPasswordTokenRepositoryInterface
{
    /**
     * Create reset password token
     *
     * @param array $data
     * @return ResetPasswordToken
     */
    public function createResetPassword(array $data): ResetPasswordToken;

    /**
     * Get token data
     *
     * @param string $token
     * @return ResetPasswordToken|null
     */
    public function findByToken(string $token): ?ResetPasswordToken;
}
