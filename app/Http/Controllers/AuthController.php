<?php

namespace App\Http\Controllers;

use App\Helpers\Constant;
use App\Helpers\Traits\SocialTrait;
use App\Requests\Authentication\CheckEmailRequest;
use App\Requests\Authentication\LoginRequest;
use App\Requests\Authentication\SignUpRequest;
use App\Requests\Authentication\VerifyAccountRequest;
use App\Services\UserServiceInterface;
use Carbon\Carbon;
use Exception;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    use SocialTrait;
    protected $userService;

    public function __construct(UserServiceInterface $userService)
    {
        parent::__construct();
        $this->userService = $userService;
    }

    public function signUp(SignUpRequest $request): JsonResponse
    {
        try {
            $data = $request->only(['email', 'password', 'name']);
            $emailChecked = $this->userService->findUserByEmail($data['email']);
            if (!empty($emailChecked)) {
                return $this->error(__('auth.email_exist'), null, 500);
            }

            $user = $this->userService->createUser($data);

            return $this->success(__('general.success'), $user);
        }
        catch (\Exception $exception) {
            Log::error('signUp(): ' . json_encode($exception));
            Log::error($exception);

            return $this->error(__('general.server_error'), null, 500);
        }
    }

    public function verifyAccount(VerifyAccountRequest $request): RedirectResponse
    {
        $data = $request->only(['email', 'token']);
        $userEmail = $this->userService->findUserByEmail($data['email']);
        if (empty($userEmail) || $userEmail->provider !== Constant::EMAIL) {
            return redirect()->route('signup.fails');
        }

        $userToken = $this->userService->getTokenData($data['token'], $userEmail->id);
        if (empty($userToken)) {
            return redirect()->route('signup.fails');
        }

        $userVerifyToken = $this->userService->verifyEmail($userEmail, $userToken);
        if (empty($userVerifyToken)) {
            return redirect()->route('signup.fails');
        }

        return redirect()->route('signup.success');
    }

    /**
     * @param LoginRequest $request
     * @return JsonResponse
     */
    public function login(LoginRequest $request): JsonResponse
    {
        try {
            $email = $request->input('email');
            $password = $request->input('password');
//            $remember = $request->input('keepMeLogin', false);
            $credentials = [
                'email' => $email,
                'password' => $password
            ];

            if (!$token = JWTAuth::attempt($credentials)) {
                return $this->error(__('auth.wrong_username_password'), null, 400);
            }

            $user =  $this->guard()->user();
            if (!$user->is_active) {
                return $this->error(__('auth.unauthorized_access_error'), null, 403);
            }

            if (empty($user->email_verified_at)) {
                return $this->error(__('auth.email_not_verify'), null, 501);
            }

            $expiresIn = JWTAuth::factory()->getTTL() * 60;
            $result = $this->userService->makeToken($token, $expiresIn);
            DB::transaction(function () use ($user, $credentials) {
                // Update last login time
                $user =  $this->guard()->user();

                $user->last_login = Carbon::now()->timestamp;
            });

            return $this->success(__('auth.logged_in_successfully'), $result);
        }
        catch (\Exception $error) {
            Log::error($error);
            return $this->error(__('general.server_error'), null, 500);
        }
        catch (GuzzleException $e) {
            Log::error($e);
            return $this->error(__('general.server_error'), null, 500);
        }
    }

    public function refreshToken(Request $request): JsonResponse
    {
        try {
            $token = $this->guard()->refresh();
            $expiresIn = JWTAuth::factory()->getTTL() * 60;
            $result = $this->userService->makeToken($token, $expiresIn);

            return $this->success(__('authentication.refresh_token_successfully'), $result, 200);
        }
        catch (\Exception $error) {
            Log::error('Refresh token error message: ' . $error->getMessage());

            return $this->error(__('general.server_error'), null, 500);
        }
        catch (GuzzleException $e) {
            Log::error($e);
            return $this->error(__('general.server_error'), null, 500);
        }
    }

    public function logout(): JsonResponse
    {
        try {
            Auth::logout();

            return $this->success(__('auth.logged_out_successfully'));
        } catch (Exception $exception) {
            return $this->error(__('general.server_error'), null, 500);
        }
    }

    public function sendVerify(CheckEmailRequest $request): JsonResponse
    {
        $user = $this->userService->findUserByEmail($request->get('email'));
        if (empty($user) || !empty($user->email_verified_at)) {
            return $this->error(__('general.empty_email'));
        }

        $this->userService->dispatchSendVerifyEmail($user);

        return $this->success(__('general.success'), true);
    }

    public function signupSuccess(): View
    {
        return view('signup.success');
    }

    public function signupFails(): View
    {
        return view('signup.fails');
    }

    /**
     * Get the guard to be used during authentication.
     *
     * @return Guard
     */
    public function guard(): Guard
    {
        return Auth::guard();
    }
}
