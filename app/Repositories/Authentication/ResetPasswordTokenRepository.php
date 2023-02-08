<?php

namespace App\Repositories\Authentication;

use App\Models\ResetPasswordToken;
use Illuminate\Database\Eloquent\Builder;

class ResetPasswordTokenRepository implements ResetPasswordTokenRepositoryInterface
{
    protected $model;

    public function __construct(ResetPasswordToken $model)
    {
        $this->model = $model;
    }

    /**
     * Create reset password token
     *
     * @param array $data
     * @return Builder|ResetPasswordToken
     */
    public function createResetPassword(array $data): ResetPasswordToken
    {
        return $this->model->create($data);
    }

    /**
     * Get reset password token
     *
     * @param string $token
     * @return ResetPasswordToken|object|null
     */
    public function findByToken(string $token): ?ResetPasswordToken
    {
        return ResetPasswordToken::query()
            ->where('token', $token)
            ->where('expired_at', '>', time())
            ->where('is_verified', false)
            ->first();
    }
}
