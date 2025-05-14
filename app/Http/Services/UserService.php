<?php

namespace App\Http\Services;

use App\Models\User;
use Exception;
use Symfony\Component\HttpFoundation\Response;

class UserService
{
    public function __construct(protected User $user)
    {}

    public function showUser(string $id): array
    {
        try {
            $user = $this->user->find($id);

            if (!$user) {
                return [
                    'message' => 'User not found',
                    'data' => null,
                    'status' => Response::HTTP_NOT_FOUND
                ];
            }

            return [
                'message' => 'User found successfully',
                'data' => $user,
                'status' => Response::HTTP_OK
            ];
        } catch (Exception $e) {
            return [
                'message' => 'Error retrieving user: ' . $e->getMessage(),
                'data' => null,
                'status' => Response::HTTP_INTERNAL_SERVER_ERROR
            ];
        }
    }

    public function updateUser(string $id, array $data): array
    {
        if (!empty($data['password'])) {
            $data['password'] = bcrypt($data['password']);
        } else {
            unset($data['password']);
        }

        try {
            $user = $this->user->find($id);

            if (!$user) {
                return [
                    'message' => 'User not found',
                    'data' => null,
                    'status' => Response::HTTP_NOT_FOUND
                ];
            }

            $user->update($data);

            return [
                'message' => 'User updated successfully',
                'data' => $user,
                'status' => Response::HTTP_OK
            ];
        } catch (Exception $e) {
            return [
                'message' => 'Error updating user: ' . $e->getMessage(),
                'data' => null,
                'status' => Response::HTTP_INTERNAL_SERVER_ERROR
            ];
        }
    }

    public function destroyUser(string $id): array
    {
        try {
            $user = $this->user->find($id);

            if (!$user) {
                return [
                    'message' => 'User not found',
                    'status' => Response::HTTP_NOT_FOUND
                ];
            }

            $user->delete();

            return [
                'message' => 'User deleted successfully',
                'status' => Response::HTTP_OK
            ];
        } catch (Exception $e) {
            return [
                'message' => 'Error deleting user: ' . $e->getMessage(),
                'status' => Response::HTTP_INTERNAL_SERVER_ERROR
            ];
        }
    }
}
