<?php

namespace App\Http\Services;

use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class UserService
{
    public function __construct(protected User $user)
    {}

    public function storeUser(array $data): void
    {
        $userData = [
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password'])
        ];
        $this->user->create($userData);
    }

    public function showUser(string $id): ?User
    {
        return $this->user->find($id);
    }

    public function updateUser(string $id, array $data): ?User
    {
        if (!empty($data['password'])) {
            $data['password'] = bcrypt($data['password']);
        } else {
            unset($data['password']);
        }

        try {
            $user = $this->user->findOrFail($id);
            $user->update($data);
            return $user;
        } catch (ModelNotFoundException) {
            return null;
        }
    }

    public function destroyUser(string $id): void
    {
        $user = $this->user->findOrFail($id);
        $user->delete();
    }
}
