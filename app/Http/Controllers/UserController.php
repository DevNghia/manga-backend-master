<?php

namespace App\Http\Controllers;

use App\Requests\User\ChangePasswordRequest;
use App\Requests\User\UpdateProfileRequest;
use App\Services\UserServiceInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{
    protected $userService;

    public function __construct(UserServiceInterface $userService)
    {
        parent::__construct();
        $this->userService = $userService;
    }

    public function info(): JsonResponse
    {
        $auth = Auth::user();

        return $this->success(__('general.success'), $auth);
    }

    public function updateProfile(UpdateProfileRequest $request): JsonResponse
    {
        $data = $request->only(['name']);
        $user = $this->userService->updateUser(Auth::id(), $data);
        if (empty($user)) {
            return $this->error(__('general.server_error'), '', 500);
        }

        return $this->success(__('general.success'), $user);
    }

    /**
     * @param ChangePasswordRequest $request
     * @return JsonResponse
     */
    public function changePassword(ChangePasswordRequest $request): JsonResponse
    {
        try {
            $user = Auth::user();
            $password = $request->input('old_password');
            $newPassword = $request->input('password');
            if (!Hash::check($password, $user->password)) {
                return $this->error(__('authentication.password_not_correct_error'),
                    ['password' => [
                        __('authentication.password_not_correct_error')
                    ]],
                    400);
            }
            if ($password === $newPassword) {
                return $this->error(__('password.airspace'));
            }
            $data['password'] = Hash::make($newPassword);
            $user = tap($user)->update($data);

            foreach ($user->tokens as $token) {
                $token->revoke();
                $cacheKey = (config('passport.cache.prefix') ?? 'passport_token_') . $token->id;
                Cache::forget($cacheKey);
            }

            return $this->success(__('general.success'), true);
        }
        catch (\Exception $error) {
            Log::error($error->getMessage());
            Log::error($error->getTraceAsString());

            return $this->error(__('general.server_error'), null, 500);
        }
    }
}
