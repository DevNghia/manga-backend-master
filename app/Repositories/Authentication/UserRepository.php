<?php

namespace App\Repositories\Authentication;

use App\Helpers\Repository\BaseRepository;
use App\Models\User;
use Carbon\Carbon;

class UserRepository extends BaseRepository implements UserRepositoryInterface
{
    protected $model;

    public function __construct(User $model)
    {
        $this->model = $model;
    }

    /**
     * @param string $email
     * @return User|null
     */
    public function findUserByEmail(string $email): ?User
    {
        return $this->model
            ->where('email', $email)
            ->first();
    }

    /**
     * @param User $user
     * @return User|null
     */
    public function verifyEmail(User $user):? User
    {
        $nowTs = Carbon::now()->timestamp;
        $user->update([
            'email_verified_at' => $nowTs,
            'activated_at' => $nowTs
        ]);

        return $user;
    }

    public function findUserByProvider(string $provider, string $providerId): ?User
    {
        return $this->model
            ->where('provider', $provider)
            ->where('provider_id', $providerId)
            ->first();
    }
}
