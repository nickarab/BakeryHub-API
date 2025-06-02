<?php

namespace App\Http\Services;

use App\Models\User;
use App\Models\Product;
use Exception;
use Symfony\Component\HttpFoundation\Response;

class ProductService
{
    public function __construct(
        protected Product $product,
        protected User $user
    ) {}

    public function listUserProducts(string $userId): array
    {
        try {
            $user = $this->user->find($userId);

            if (!$user) {
                return [
                    'message' => 'Usuário não encontrado',
                    'data' => null,
                    'status' => Response::HTTP_NOT_FOUND
                ];
            }

            $products = $user->product()->get();

            return [
                'message' => 'Produtos encontrados com sucesso',
                'data' => $products,
                'status' => Response::HTTP_OK
            ];
        } catch (Exception $e) {
            return [
                'message' => 'Erro ao listar produtos: ' . $e->getMessage(),
                'data' => null,
                'status' => Response::HTTP_INTERNAL_SERVER_ERROR
            ];
        }
    }

    public function showUserProduct(string $userId, string $productId): array
    {
        try {
            $product = $this->product->where('user_id', $userId)->find($productId);

            if (!$product) {
                return [
                    'message' => 'Produto não encontrado para este usuário',
                    'data' => null,
                    'status' => Response::HTTP_NOT_FOUND
                ];
            }

            return [
                'message' => 'Produto encontrado com sucesso',
                'data' => $product,
                'status' => Response::HTTP_OK
            ];
        } catch (Exception $e) {
            return [
                'message' => 'Erro ao buscar produto: ' . $e->getMessage(),
                'data' => null,
                'status' => Response::HTTP_INTERNAL_SERVER_ERROR
            ];
        }
    }

    public function createUserProduct(string $userId, array $data): array
    {
        try {
            $user = $this->user->find($userId);

            if (!$user) {
                return [
                    'message' => 'Usuário não encontrado',
                    'data' => null,
                    'status' => Response::HTTP_NOT_FOUND
                ];
            }

            $product = $user->product()->create($data);

            return [
                'message' => 'Produto criado com sucesso',
                'data' => $product,
                'status' => Response::HTTP_CREATED
            ];
        } catch (Exception $e) {
            return [
                'message' => 'Erro ao criar produto: ' . $e->getMessage(),
                'data' => null,
                'status' => Response::HTTP_INTERNAL_SERVER_ERROR
            ];
        }
    }

    public function updateUserProduct(string $userId, string $productId, array $data): array
    {
        try {
            $product = $this->product->where('user_id', $userId)->find($productId);

            if (!$product) {
                return [
                    'message' => 'Produto não encontrado para este usuário',
                    'data' => null,
                    'status' => Response::HTTP_NOT_FOUND
                ];
            }

            $product->update($data);

            return [
                'message' => 'Produto atualizado com sucesso',
                'data' => $product,
                'status' => Response::HTTP_OK
            ];
        } catch (Exception $e) {
            return [
                'message' => 'Erro ao atualizar produto: ' . $e->getMessage(),
                'data' => null,
                'status' => Response::HTTP_INTERNAL_SERVER_ERROR
            ];
        }
    }

    public function destroyUserProduct(string $userId, string $productId): array
    {
        try {
            $product = $this->product->where('user_id', $userId)->find($productId);

            if (!$product) {
                return [
                    'message' => 'Produto não encontrado para este usuário',
                    'status' => Response::HTTP_NOT_FOUND
                ];
            }

            $product->delete();

            return [
                'message' => 'Produto deletado com sucesso',
                'status' => Response::HTTP_OK
            ];
        } catch (Exception $e) {
            return [
                'message' => 'Erro ao deletar produto: ' . $e->getMessage(),
                'status' => Response::HTTP_INTERNAL_SERVER_ERROR
            ];
        }
    }
}
