<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use App\Http\Resources\UserCollection;
use App\Http\Resources\UserResource;

class UserController extends Controller
{
    public function index()
    {
        return new UserCollection(UserResource::collection(User::all()));
    }

    public function show($id)
    {
        $user = User::find($id);
        if($user) {
            return new UserResource($user);
        } else {
            return response()->json([
                'error' => 404,
                'message' => 'Not found'
            ], 404);
        }
    }

    public function store(Request $request) 
    {
        $request->validate([
            'name'             => 'required|max:150|unique:users,name',
            'email'            => 'required|email|unique:users,email',
            'password'         => 'required|min:8',
            'password_confirm' => 'required|same:password'
        ]);
        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => bcrypt($request->password)
        ]);
        return response()->json([
            'id'         => $user->id,
            'created_at' => $user->created_at
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name'             => 'required',
            'password'         => 'required|min:8',
            'password_confirm' => 'required|same:password'
        ]);
        $user = User::find($id);
        if($user) {
            $user->update([
                'name'     => $request->name,
                'password' => bcrypt($request->password)
            ]);
            return response()->json(null, 204);
        } else {
            return response()->json([
                'error'   => 404,
                'message' => 'Not found'
            ], 404);
        }
    }

    public function destroy($id)
    {
        $user = User::find($id);
        if(!$user) {
            return response()->json([
                'error'   => 404,
                'message' => 'Not found'
            ], 404);
        } else {
            $user->delete();
            return response()->json(null, 204);
        }
    }
}
