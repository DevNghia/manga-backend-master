<?php

namespace App\Repositories\Authentication;

use App\Helpers\Repository\BaseRepositoryInterface;
use App\Models\User;

interface UserRepositoryInterface extends BaseRepositoryInterface
{
    public function findUserByEmail(string $email): ?User;

    public function verifyEmail(User $user):? User;

    public function findUserByProvider(string $provider, string $providerId):? User;
}
