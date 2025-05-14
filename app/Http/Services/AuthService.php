<?php

namespace App\Http\Services;

use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class AuthService
{
    public function __construct(protected User $user) {}

    public function userLogin(array $data): array
    {
        $user = $this->user->where('email', $data['email'])->first();

        if (!$user || !Hash::check($data['password'], $user->password)) {
            return ['success' => false, 'error' => 'Invalid credentials'];
        }

        if (!$user->hasVerifiedEmail()) {
            return ['success' => false, 'error' => 'E-mail not verified'];
        }

        $token = $user->createToken('api_token')->plainTextToken;

        return [
            'success' => true,
            'user' => $user,
            'token' => $token
        ];
    }

    public function storeUser(array $data): User
    {
        $userData = [
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'email_verified_at' => null
        ];

        $user = $this->user->create($userData);
        $user->sendEmailVerificationNotification();

        return $user;
    }

    public function verifyEmail(string $id): array
    {
        $user = $this->user->find($id);

        if (!$user) {
            return [
                'success' => false,
                'message' => 'User not found',
                'status' => Response::HTTP_NOT_FOUND
            ];
        }

        if ($user->hasVerifiedEmail()) {
            return [
                'success' => false,
                'message' => 'E-mail already verified',
                'status' => Response::HTTP_BAD_REQUEST
            ];
        }

        try {
            $user->markEmailAsVerified();
            return [
                'success' => true,
                'message' => 'E-mail verificado com sucesso',
                'status' => Response::HTTP_OK
            ];
        } catch (Exception $e) {
            Log::error('Failed verifying E-mail:' . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Error verifying email',
                'status' => Response::HTTP_INTERNAL_SERVER_ERROR
            ];
        }
    }

    public function resendVerificationEmail(string $email): array
    {
        $user = $this->user->where('email', $email)->first();

        if (!$user) {
            return [
                'success' => false,
                'message' => 'User not found',
                'status' => 404
            ];
        }

        if ($user->hasVerifiedEmail()) {
            return [
                'success' => false,
                'message' => 'E-mail already verified',
                'status' => 400
            ];
        }

        try {
            $user->sendEmailVerificationNotification();
            return [
                'success' => true,
                'message' => 'Email verification has been sent',
                'status' => 200
            ];
        } catch (Exception $e) {
            Log::error('Failed verifying E-mail:' . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Error sending verification email',
                'status' => 500
            ];
        }
    }

    public function logoutUser(User $user): array {
        try{
            $user->tokens()->delete();

            return
                [
                'sucess' => true,
                'message' => 'logout succesfully',
                'status' => Response::HTTP_OK
                ];
        }catch (Exception $e){
            return
                [
                'sucess' => false,
                'message' => 'Error during logout' . $e->getMessage(),
                'status' => Response::HTTP_INTERNAL_SERVER_ERROR
                ];
        }
    }
}
