<?php

namespace App\Http\Services;

use App\Core\Logic\Result;
use App\Models\User;
use App\Interfaces\UserServiceInterface;

class UserService implements UserServiceInterface
{
    public function __construct(
        private User $repository
    ) {
    }

    public function getAll(): Result
    {
        try {
            $users = $this->repository->paginate(10);

            return Result::success($users);
        } catch (\Exception $e) {
            return Result::fail([
                'fail' => 'Erro ao buscar usuários',
                'error' => $e->getMessage()
            ]);
        }
    }

    public function create($data): Result
    {
        try {
            $this->repository->create($data);

            return Result::success([
                'success' => 'Usuário cadastrado com sucesso'
            ]);
        } catch (\Exception $e) {
            return Result::fail([
                'fail' => 'Erro ao cadastrar usuário',
                'error' => $e->getMessage()
            ]);
        }
    }

    public function update($data, $id): Result
    {
        try {
            $user = $this->repository->find($id);

            if (!$user) {
                return Result::fail([
                    'fail' => 'Usuário não encontrado'
                ]);
            }

            $user->update($data);

            return Result::success([
                'success' => 'Usuário atualizado com sucesso'
            ]);
        } catch (\Exception $e) {
            return Result::fail([
                'fail' => 'Erro ao atualizar usuário',
                'error' => $e->getMessage()
            ]);
        }
    }

    public function getById($id): Result
    {
        try {
            $user = $this->repository->find($id);

            if (!$user) {
                return Result::fail([
                    'fail' => 'Usuário não encontrado'
                ]);
            }

            return Result::success($user);
        } catch (\Exception $e) {
            return Result::fail([
                'fail' => 'Erro ao buscar usuário'
            ]);
        }
    }

    public function delete($id): Result
    {
        try {
            $user = $this->repository->find($id);

            if (!$user) {
                return Result::fail([
                    'fail' => 'Usuário não encontrado'
                ]);
            }

            $user->delete();

            return Result::success([
                'success' => 'Usuário excluído com sucesso'
            ]);
        } catch (\Exception $e) {
            return Result::fail([
                'fail' => 'Erro ao excluir usuário',
                'error' => $e->getMessage()
            ]);
        }
    }
}
