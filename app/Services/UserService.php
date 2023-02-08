<?php

namespace App\Services;

use App\Jobs\SendMail;
use App\Mail\Authentication\UserVerifyEmail;
use App\Models\User;
use App\Models\UserVerifyToken;
use App\Repositories\Authentication\OAuthClientRepositoryInterface;
use App\Repositories\Authentication\ResetPasswordTokenRepositoryInterface;
use App\Repositories\Authentication\UserRepositoryInterface;
use App\Repositories\Authentication\UserVerifyTokenRepositoryInterface;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use GuzzleHttp\Client as HttpClient;
use Tymon\JWTAuth\Facades\JWTAuth;

class UserService implements UserServiceInterface
{
    protected $userRepository;


    protected $resetPasswordTokenRepository;

    protected $userVerifyTokenRepository;

    public function __construct(UserRepositoryInterface            $userRepository,
                                UserVerifyTokenRepositoryInterface $userVerifyTokenRepository,
                                ResetPasswordTokenRepositoryInterface $resetPasswordTokenRepository)
    {
        $this->userRepository = $userRepository;
        $this->userVerifyTokenRepository = $userVerifyTokenRepository;
        $this->resetPasswordTokenRepository = $resetPasswordTokenRepository;
    }

    /**
     * Make token
     *
     * @param string $token
     * @param int $expiresIn
     * @return array
     */
    public function makeToken(string $token, int $expiresIn): array
    {
        return [
            'access_token' => $token,
            'token_type' => 'Bearer',
            'expires_in' => $expiresIn
        ];
    }

    /**
     * Create user
     *
     * @param array $data
     * @return User|null
     */
    public function createUser(array $data): ?User
    {
        // Default avatar
        $data['avatar'] = $this->getAvatarFromName($data['email']);
        return DB::transaction(function () use ($data) {
            $data['password'] = Hash::make($data['password']);
            $user = $this->userRepository->create($data);
            $userToken = $this->userVerifyTokenRepository->create($user);

            $mail = (new UserVerifyEmail($user->email, $userToken->token));
            dispatch(new SendMail([$user->email], $mail));

            return $user;
        });
    }

    /**
     * Get token data
     *
     * @param string $token
     * @param int $userId
     * @return UserVerifyToken|null
     */
    public function getTokenData(string $token, int $userId): ?UserVerifyToken
    {
        return $this->userVerifyTokenRepository->findByToken($token, $userId);
    }

    /**
     * Verify email
     *
     * @param User $user
     * @param UserVerifyToken $userVerifyToken
     * @return UserVerifyToken|null
     */
    public function verifyEmail(User $user, UserVerifyToken $userVerifyToken): ?UserVerifyToken
    {
        return DB::transaction(function () use ($user, $userVerifyToken) {
            $this->userRepository->verifyEmail($user);
            return $this->userVerifyTokenRepository->verifyEmailSuccess($userVerifyToken);
        });
    }

    /**
     * UI avatar from name
     *
     * @param $name
     * @return string
     */
    public function getAvatarFromName($name): string
    {
        return 'https://ui-avatars.com/api/?name=' . trim($name) . '&size=512';
    }

    /**
     * Update user
     *
     * @param int $userId
     * @param array $data
     * @return User|null
     */
    public function updateUser(int $userId, array $data): ?User
    {
        return $this->userRepository->updateById($userId, $data);
    }

    /**
     * @param string $email
     * @return User|null
     */
    public function findUserByEmail(string $email):? User
    {
        return $this->userRepository->findUserByEmail($email);
    }

    /**
     * @param string $provider
     * @param string $providerId
     * @return User|null
     */
    public function findUserByProvider(string $provider, string $providerId):? User
    {
        return $this->userRepository->findUserByProvider($provider, $providerId);
    }

    public function dispatchSendVerifyEmail($user): ?User
    {
        return DB::transaction(function () use ($user) {
            $userToken = $this->userVerifyTokenRepository->create($user);

            $mail = (new UserVerifyEmail($user->email, $userToken->token));
            dispatch(new SendMail([$user->email], $mail));

            return $user;
        });
    }
}
