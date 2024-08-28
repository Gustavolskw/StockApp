<?php

namespace App\Repository;

use App\Http\Resources\UserView;
use App\Interfaces\UserRepositoryInterface;
use App\Models\User;
use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\QueryException;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Hash;
use PhpParser\Node\Expr\Throw_;

class UserRepository implements UserRepositoryInterface
{

    public function store(Model $user): array
    {
        try {
            $user->save();
            return [
                'success' => true,
                'message' => "User Saved with success!"
            ];
        } catch (Exception $exception) {
            return [
                'success' => false,
                'data' => null,
                'message' => $exception->getMessage()
            ];
        }
    }
    public function delete(int $id): array
    {

        try {
            $user = User::find($id);

            if ($user) {
                if ($user->ativo == false) {
                    return [
                        'success' => false,
                        'data' => null,
                        'message' => "User aleady Inactivated!"
                    ];
                }
                $user->ativo = false;
                $user->save();

                return [
                    'success' => true,
                    'data' => null,
                    'message' => "User inactivated successfully!"
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

    public function active(int $id): array
    {
        try {
            $user = User::find($id);

            if ($user) {

                if ($user->ativo == true) {
                    return [
                        'success' => false,
                        'data' => null,
                        'message' => "User aleady activated!"
                    ];
                }
                $user->ativo = true;
                $user->save();

                return [
                    'success' => true,
                    'data' => null,
                    'message' => "User Activated successfully!"
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
    public function index(): array
    {
        try {
            $user = User::paginate(5);

            if ($user) {
                return [
                    'success' => true,
                    'data' => UserView::collection($user),
                    'lastPage' => $user->lastPage(),
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

    public function update(array $userUpdate, int $id): array
    {
        try {
            $user = User::find($id);
            if ($user) {
                $passwordComparator = password_verify($userUpdate['oldPassword'], $user->password);
                if (!$passwordComparator) {
                    return [
                        'success' => false,
                        'data' => null,
                        'message' => "Credentials are inavlid!"
                    ];
                }
                $user->update([
                    'name' => $userUpdate['name'],
                    'email' => $userUpdate['email'],
                    'password' => $userUpdate['newPassword'],
                ]);

                $user->accessType()->associate($userUpdate['role']);
                $user->save();
                return [
                    'success' => true,
                    'data' => null,
                    'message' => "User updated successfully!"
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
}