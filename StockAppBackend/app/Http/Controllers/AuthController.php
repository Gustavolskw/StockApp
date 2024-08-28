<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserView;
use App\Interfaces\UserRepositoryInterface;
use App\Models\User;
use App\Traits\HttpResponses;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller implements HasMiddleware
{
    use HttpResponses;

    private UserRepositoryInterface $userRepository;
    public function __construct(UserRepositoryInterface $usersInterface)
    {
        $this->userRepository = $usersInterface;
    }

    public static function middleware()
    {
        return [
            new Middleware('auth:sanctum', except: ['login', 'store'])
        ];
    }
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'min:4', 'max:70'],
            'email' => ['required', 'email', 'unique:users,email'],
            'role' => ['required', 'numeric', 'exists:access_type,id_access_type'],
            'password' => ['required', 'min:5', 'confirmed']
        ]);

        if ($validator->fails()) {
            return $this->errorResponse("Validation Error", 422, $validator->errors());
        }

        $newUser = new User([
            'name' => $request->get('name'),
            'email' => $request->get('email'),
            'password' => Hash::make($request->get('password')),
        ]);
        $newUser->accessType()->associate($request->get('role'));

        $userSave = $this->userRepository->store($newUser);

        if ($userSave['success'] == false) {
            $this->exceptionResponse("Erro ao Registrar Usuario!", 422, $userSave['message']);
        }

        if ($newUser->accessType->access_level == 0) {
            $abilities = [0];
        } else {

            for ($i = 0; $i <= $newUser->accessType->access_level; $i++) {
                $abilities[] = $i;
            }
        }
        $token = $newUser->createToken($newUser->name . uniqid(), $abilities, now()->addHours(2));

        return $this->successAuthResponse($userSave['message'], 201, new UserView($newUser), $token->plainTextToken);
    }

    public function login(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'email' => ['required', 'email',],
            'password' => ['required', 'min:5']
        ]);

        if ($validator->fails()) {
            return $this->errorResponse("Validation Error", 422, $validator->errors());
        }

        $user = $this->userRepository->showByEmail($request->get('email'));

        if ($user['data']->ativo == false) {
            return $this->badCredentialsResponse(200);
        }
        if (!$user['data'] || !Hash::check($request->get('password'), $user['data']->password)) {
            return response(json_encode([
                'Error' => "The Provided Credential are Incorrect.."
            ]), 400);
        }

        $abilities = [];
        $user['data']->tokens()->delete();
        if ($user['data']->accessType->access_level == 0) {
            $abilities = [0];
        } else {

            for ($i = 0; $i <= $user['data']->accessType->access_level; $i++) {
                $abilities[] = $i;
            }
        }
        $token = $user['data']->createToken($user['data']->name . uniqid(), $abilities, now()->addHours(2));

        return $this->successAuthResponse("User Logged In with success!", 200, [], $token->plainTextToken);
    }

    public function destroy(Request $request)
    {

        if (!$request->user()) {
            return response(json_encode([
                'Error' => 'Sem Token de autenticação!'
            ]), 400);
        }

        $request->user()->tokens()->delete();
        return response(json_encode([
            'message' => "Logout Form The application"
        ]), 200);
    }

    public function index()
    {
        $response = $this->userRepository->index();

        if ($response['success']) {
            return response()->json([
                'users' => $response['data'],
                'lastPage' => $response['lastPage']
            ], 200);
        } else {
            $this->errorResponse("Erro while searching for users", 400, $response['message']);
        }
    }
    public function show(int $id)
    {
        $validator = Validator::make(['id' => $id], [
            'id' => ['required', 'numeric']
        ]);


        if ($id === null) {
            return $this->errorResponse("Invalid ID provided!", 422, $validator->errors());
        }


        $user = $this->userRepository->showById($id);
        if (!$user['success']) {
            return $this->exceptionResponse('Erro ao Executar busca de Usuario!', 400, $user['message']);
        }
        return response()->json(new UserView($user['data']), 200);
    }

    public function delete(int $id, Request $request)
    {
        $validator = Validator::make(['id' => $id], [
            'id' => ['required', 'numeric']
        ]);
        if ($validator->fails()) {
            return $this->errorResponse("Invalid ID provided!", 422, $validator->errors());
        }

        dd($request->user()->accessToken());



        $delete = $this->userRepository->delete($id);

        if ($delete['success'] == false) {
            return $this->exceptionResponse("Erro ao Desativar o Usuario", 422, $delete['message']);
        } else {
            return $this->successMessageResponse($delete['message'], 200);
        }
    }

    public function active(int $id)
    {

        $validator = Validator::make(['id' => $id], [
            'id' => ['required', 'numeric']
        ]);

        if ($validator->fails()) {
            return $this->errorResponse("Invalid ID provided!", 422, $validator->errors());
        }

        $active = $this->userRepository->active($id);

        if ($active['success'] == false) {
            return $this->exceptionResponse("Erro ao Reativar o Usuario", 422, $active['message']);
        } else {
            return $this->successMessageResponse($active['message'], 200);
        }
    }

    public function update(int $id, Request $request)
    {

        $validatorId = Validator::make(['id' => $id], [
            'id' => ['required', 'numeric']
        ]);

        if ($validatorId->fails()) {
            return $this->errorResponse("Invalid ID provided!", 422, $validatorId->errors());
        }

        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'min:4', 'max:70'],
            'email' => ['required', 'email', 'unique:users,email'],
            'role' => ['required', 'numeric', 'exists:access_type,id_access_type'],
            'oldPassword' => ['required', 'min:5'],
            'newPassword' => ['required', 'min:5', 'confirmed']
        ]);

        if ($validator->fails()) {
            return $this->errorResponse("Validation Error", 422, $validator->errors());
        }

        $updateUser = $validator->validated();

        $update = $this->userRepository->update($updateUser, $id);

        if ($update['success'] == false) {
            return $this->exceptionResponse("Error While Editing User!", 422, $update['message']);
        } else {
            return $this->successMessageResponse($update['message'], 200);
        }
    }
}
