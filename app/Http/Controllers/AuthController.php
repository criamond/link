<?php
declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\LoginUserRequest;
use App\Http\Requests\RegisterUserRequest;
use App\Http\Requests\VerifyEmailRequest;
use App\Mail\EmailVerificationMail;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    public function register(RegisterUserRequest $request): JsonResponse
    {

        $role = $request->role ?? false;

        if (($role) && User::where('role', 1)->first()) {

            throw new AccessDeniedHttpException('Can not create another admin', null, 403);

        }

        $verificationToken = Str::random(64);

        $user = User::create([
            'name'               => $request->name,
            'email'              => $request->email,
            'password'           => Hash::make($request->password),
            'verification_token' => $verificationToken,
            'role'               => $role ? 1 : 0,
        ]);

        Mail::to($user->email)->send(new EmailVerificationMail($user));

        return response()->json(['message' => 'User registered successfully. Please check your email to verify your account.'], 201);
    }

    public function verifyEmail(VerifyEmailRequest $request): JsonResponse
    {
        $user = User::where('verification_token', $request->token)->first();

        if (!$user) {
            throw new NotFoundHttpException('Invalid or expired verification token.');
        }

        $user->update([
            'email_verified_at'  => now(),
            'verification_token' => null,
        ]);

        return response()->json(['message' => 'Email verified successfully'], 200);
    }


    public function login(LoginUserRequest $request): JsonResponse
    {
        $credentials = $request->only('email', 'password');

        if (!$token = Auth::attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $user = Auth::user();

        if (!$user->email_verified_at) {
            return response()->json(['message' => 'Please verify your email before logging in.'], 403);
        }

        if ($user->role == 1) {
            return $this->respondWithToken((string)$token, 1);
        } else {
            return $this->respondWithToken((string)$token);
        }
    }


    protected function respondWithToken(string $token, $is_admin = null): JsonResponse
    {
        $result = [
            'access_token'  => $token,
            'refresh_token' => JWTAuth::fromUser(Auth::user()),
            'token_type'    => 'bearer',
            'expires_in'    => Auth::factory()->getTTL() * 60,
            'refresh_ttl'   => config('jwt.refresh_ttl'),
        ];
        if ($is_admin) {
            $result['is_admin'] = 1;
        }

        return response()->json($result);
    }


    public function refresh(): JsonResponse
    {
        try {

            $refreshToken = Auth::refresh();

            $newAccessToken = JWTAuth::setToken($refreshToken)->refresh();

            return $this->respondWithToken($newAccessToken);
        } catch (TokenInvalidException $e) {
            return response()->json(['error' => 'Invalid refresh token'], 401);
        }
    }
}
