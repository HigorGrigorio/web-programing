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

            $result = Result::success($users);
        } catch (\Exception $e) {
            $result = Result::fail([
                'fail' => 'Erro ao buscar usuários',
                'error' => $e->getMessage()
            ]);
        }

        return $result;
    }

    public function create($data): Result
    {
        try {
            $this->repository->create($data);

            $result = Result::success([
                'success' => 'Usuário cadastrado com sucesso'
            ]);
        } catch (\Exception $e) {
            $result = Result::fail([
                'fail' => 'Erro ao cadastrar usuário',
                'error' => $e->getMessage()
            ]);
        }

        return $result;
    }

    public function update($data, $id): Result
    {
        try {
            $user = $this->repository->find($id);

            if (!$user) {
                $result = Result::fail([
                    'fail' => 'Usuário não encontrado'
                ]);
            }

            $user->update($data);

            $result = Result::success([
                'success' => 'Usuário atualizado com sucesso'
            ]);
        } catch (\Exception $e) {
            $result = Result::fail([
                'fail' => 'Erro ao atualizar usuário',
                'error' => $e->getMessage()
            ]);
        }

        return $result;
    }

    public function getById($id): Result
    {
        try {
            $user = $this->repository->find($id);

            if (!$user) {
                $result = Result::fail([
                    'fail' => 'Usuário não encontrado'
                ]);
            }

            $result = Result::success($user);
        } catch (\Exception $e) {
            $result = Result::fail([
                'fail' => 'Erro ao buscar usuário'
            ]);
        }

        return $result;
    }

    public function delete($id): Result
    {
        try {
            $user = $this->repository->find($id);

            if (!$user) {
                $result = Result::fail([
                    'fail' => 'Usuário não encontrado'
                ]);
            }

            $user->delete();

            $result = Result::success([
                'success' => 'Usuário excluído com sucesso'
            ]);
        } catch (\Exception $e) {
            $result = Result::fail([
                'fail' => 'Erro ao excluir usuário',
                'error' => $e->getMessage()
            ]);
        }

        return $result;
    }
}
