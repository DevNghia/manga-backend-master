<?php

namespace App\Repositories\Authentication;

use App\Helpers\Helper;
use App\Models\User;
use App\Models\UserVerifyToken;
use Carbon\Carbon;

class UserVerifyTokenRepository implements UserVerifyTokenRepositoryInterface
{
    protected $model;

    public function __construct(UserVerifyToken $model)
    {
        $this->model = $model;
    }

    /**
     * Create verify email token
     *
     * @param User $user
     * @return UserVerifyToken|null
     */
    public function create(User $user): ?UserVerifyToken
    {
        $data = [
            'user_id' => $user->id,
            'token' => Helper::randomNumber(6),
            'expired_at' => Carbon::now()->addDays(config('auth.email_verify_token_ttl', 1))->timestamp
        ];

        return $this->model->create($data);
    }

    /**
     * Find verify info by token
     * @param string $token
     * @param int $userId
     * @return UserVerifyToken|null
     */
    public function findByToken(string $token, int $userId): ?UserVerifyToken
    {
        return UserVerifyToken::query()
            ->where('user_id', $userId)
            ->where('token', $token)
            ->where('expired_at', '>', time())
            ->where('is_verified', false)
            ->latest('id')->first();
    }

    /**
     * Update verified status
     * @param UserVerifyToken $userVerifyToken
     * @return UserVerifyToken|null
     */
    public function verifyEmailSuccess(UserVerifyToken $userVerifyToken): ?UserVerifyToken
    {
        return tap($userVerifyToken)->update([
            'is_verified' => true,
            'updated_at' => Carbon::now()->timestamp
        ]);
    }

    /**
     * Disable old token by User id
     * @param int $userId
     * @return void
     */
    public function disableTokenByUserId(int $userId): void
    {
        $nowTs = Carbon::now()->timestamp;
        UserVerifyToken::query()
            ->where('user_id', $userId)
            ->where('expired_at', '>', $nowTs)
            ->where('is_verified', false)
            ->update(['expired_at' => $nowTs]);
    }
}
