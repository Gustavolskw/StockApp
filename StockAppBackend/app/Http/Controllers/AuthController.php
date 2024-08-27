<?php

namespace App\Http\Controllers;

use App\ExceptionHandler;
use App\Http\Requests\UserLoginRequest;
use App\Http\Requests\UserRegisterRequest;
use App\Http\Resources\UserView;
use App\Models\User;
use App\Traits\HttpResponses;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    use HttpResponses;

    public static function middleware()
    {
        return [
            new Middleware('auth:sanctum', except: ['index', 'show'])
        ];
    }
    public function store(UserRegisterRequest $request)
    {

        $newUser = new User([
            'name' => $request->get('name'),
            'email' => $request->get('email'),
            'password' => Hash::make($request->get('password')),
        ]);
        $newUser->accessType()->associate($request->get('role'));

        $newUser->save();
        if ($newUser->accessType->access_level == 0) {
            $abilities = [0];
        } else {

            for ($i = 0; $i <= $newUser->accessType->access_level; $i++) {
                $abilities[] = $i;
            }
        }
        $token = $newUser->createToken($newUser->name . uniqid(), $abilities, now()->addHours(2));

        return $this->successAuthResponse("User Created with success!", 201, new UserView($newUser), $token->plainTextToken);
    }

    public function login(UserLoginRequest $request)
    {
        try {
            $user = User::where('email', $request->get('email'))->first();
        } catch (QueryException $e) {
            return $this->exceptionResponse("Erro during search for user!", 422, $e);
        }
        if (!$user || !Hash::check($request->password, $user->password)) {
            return response(json_encode([
                'Error' => "The Provided Credential are Incorrect.."
            ]), 400);
        }
        $abilities = [];
        $user->tokens()->delete();
        if ($user->accessType->access_level == 0) {
            $abilities = [0];
        } else {

            for ($i = 0; $i <= $user->accessType->access_level; $i++) {
                $abilities[] = $i;
            }
        }
        $token = $user->createToken($user->name . uniqid(), $abilities, now()->addHours(2));

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
        return response(json_encode(UserView::collection(User::all())), 200);
    }
    public function show(int $id)
    {
        $validator = Validator::make(['id' => $id], [
            'id' => ['required', 'numeric']
        ]);


        if ($id === null) {
            return $this->errorResponse("Invalid ID provided!", 422, $validator->errors());
        }

        try {
            $user = User::where('id', $id)->first();
            if ($user === null) {
                return $this->errorResponse("User not Found!", 404, ["error" => "User Id is not registered!"]);
            }

            // Return a single resource instance instead of a collection
            return response()->json(new UserView($user), 200);
        } catch (QueryException $q) {
            return $this->exceptionResponse("Erro while executing a search for the User!", 400, $q);
        }
    }
}
