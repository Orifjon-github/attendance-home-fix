<?php

namespace App\Http\Controllers;

use App\Helpers\Response;
use App\Http\Requests\LoginRequest;
use App\Models\User;
use App\Models\UserFcmToken;
use App\Services\SmsService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    use Response;

    private SmsService $smsService;

    public function __construct(SmsService $smsService)
    {
        $this->smsService = $smsService;
    }

    public function login(LoginRequest $request): JsonResponse
    {
        $username = $request->username;
        $password = $request->password;
        $fcm_token = $request->fcm_token;

        $user = User::where('username', $username)->first();

        if (!$user) {
            return $this->error('You have not account, Please register', 404);
        }

        if (!Hash::check($password, $user->password)) {
            return $this->error('Invalid Password', 403);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        if ($fcm_token) {
            $user->fcm_tokens()->create([
                'token' => $request->fcm_token,
            ]);

        }

        return $this->success(['token' => $token]);
    }

    public function logout(Request $request): JsonResponse
    {
        $fcm_token = $request->input('fcm_token');
        $request->user()->tokens()->delete();
        if ($fcm_token) {
            UserFcmToken::where('token', $fcm_token)->delete();
        }

        return $this->success(['message' => 'Logged out successfully']);
    }

    public function forgotPassword(Request $request): JsonResponse
    {
        $username = $request->input('username');
        $user = User::where('username', $username)->first();
        if (!$user) {
            return $this->error('You have not account, Please register', 404);
        }

        $code = mt_rand(100000, 999999);

        $sms = $this->smsService->sms($username, $code);
        if ($sms) {
            $user->sms_code()->updateOrCreate(['user_id' => $user->id], ['code' => $code]);
        } else {
            return $this->error('Send Sms Error');
        }

        return $this->success(['message' => "Sms code sent to $username"]);
    }

    public function forgotPasswordConfirm(Request $request): JsonResponse
    {
        $username = $request->input('username');
        $code = $request->input('code');
        $user = User::where('username', $username)->first();

        $check = self::checkCode($user, $code);
        if ($check instanceof JsonResponse) {
            return $check;
        }

        return $this->success(['message' => 'Sms code confirm, You can change password']);
    }

    public function forgotPasswordNewPassword(Request $request): JsonResponse
    {
        $password = $request->input('password');
        $username = $request->input('username');
        $user = User::where('username', $username)->first();
        if (!$user) {
            return $this->error('You have not account, Please register', 404);
        }

        $user->update(['password' => Hash::make($password)]);

        return $this->success(['message' => 'Password reset successfully']);
    }
}
