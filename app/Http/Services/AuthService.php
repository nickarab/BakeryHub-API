<?php

namespace App\Http\Services;

use App\Models\User;
use Illuminate\Support\Facades\Hash;


class AuthService{

    public function __construct(protected User $user)
    {}

    public function Userlogin(array $data)
    {
        $user = $this->user->where('email', $data['email'])->first();

        if (!$user || !Hash::check($data['password'], $user->password)) {
            return null;
        }

        $token = $user->createToken('api_token')->plainTextToken;

        return [
            'user' => $user,
            'token' => $token
        ];
    }

}


