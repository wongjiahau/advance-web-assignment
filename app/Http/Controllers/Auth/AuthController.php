<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Controller\UserController;
use App\User;

class AuthController extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login', 'register']]);
    }

    public function register(Request $request) 
    {
        $request->validate([
            'name'             => 'required|alpha_num|max:150|unique:users,name',
            'email'            => 'required|email|unique:users,email',
            'password'         => 'required|min:8',
            'password_confirm' => 'required|same:password'
        ]);
        $user = User::create([
            'name' => $request->name,
            'password' => bcrypt($request->password),
            'email' => $request->email,
        ]);
        return response()->json([
            'id'         => $user->id,
            'created_at' => $user->created_at
        ]);
    }

    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        $credentials = ['email' => $request->email, 'password' => $request->password];
        $token = auth()->attempt($credentials);
        if ($token) {
            return $this->respondWithToken($token);
        } else {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
    }

    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me()
    {
        return response()->json(auth()->user());
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        auth()->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        return $this->respondWithToken(auth()->refresh());
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60
        ]);
    }
}
