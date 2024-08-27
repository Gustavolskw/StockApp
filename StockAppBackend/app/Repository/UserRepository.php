<?php

namespace App\Repository;

use App\Http\Resources\UserView;
use App\Interfaces\UserRepositoryInterface;
use App\Models\User;
use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\QueryException;
use Illuminate\Http\Resources\Json\JsonResource;
use PhpParser\Node\Expr\Throw_;

class UserRepository implements UserRepositoryInterface
{

    public function store(Model $user): array
    {
        return [];
    }
    public function destroy(int $id): array
    {
        return [];
    }
    public function index(): array
    {
        try {
            $user = User::paginate(5);

            if ($user) {
                return [
                    'success' => true,
                    'data' => UserView::collection($user),
                    'message' => null
                ];
            } else {
                return [
                    'success' => false,
                    'data' => null,
                    'message' => "User not found"
                ];
            }
        } catch (Exception $exception) {
            return [
                'success' => false,
                'data' => null,
                'message' => $exception->getMessage()
            ];
        }
    }
    public function showById(int $id): array
    {
        try {
            $user = User::where('id', $id)->first();

            if ($user) {
                return [
                    'success' => true,
                    'data' => new UserView($user),
                    'message' => null
                ];
            } else {
                return [
                    'success' => false,
                    'data' => null,
                    'message' => "User not found"
                ];
            }
        } catch (Exception $exception) {
            return [
                'success' => false,
                'data' => null,
                'message' => $exception->getMessage()
            ];
        }
    }
    public function showByEmail(string $email): array
    {
        try {
            $user = User::where('email', $email)->first();

            if ($user) {
                return [
                    'success' => true,
                    'data' => new UserView($user),
                    'message' => null
                ];
            } else {
                return [
                    'success' => false,
                    'data' => null,
                    'message' => 'User not found'
                ];
            }
        } catch (Exception $exception) {
            return [
                'success' => false,
                'data' => null,
                'message' => $exception->getMessage()
            ];
        }
    }
}
