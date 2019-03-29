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
        $requestingUser = auth()->user();
        $userToBeViewed = User::find($id);
        // requestingUser is allowed to view the profile of userToBeViewed if they have belong to a common group
        if($userToBeViewed && $requestingUser->groups->some(function($g) use($userToBeViewed) {
            return $g->users->find($userToBeViewed->id);
        })) {
            return new UserResource($userToBeViewed);
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
            'name'             => 'required|alpha_num|max:150|unique:users,name',
            'email'            => 'required|email|unique:users,email',
            'password'         => 'required|min:8',
            'password_confirm' => 'required|same:password'
        ]);
        $user = User::create($request->all());
        return response()->json([
            'id'         => $user->id,
            'created_at' => $user->created_at
        ]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'email'            => 'required|email',
            'password'         => 'required|min:8',
            'password_confirm' => 'required|same:password'
        ]);
        $user = User::find($id);
        if(!$user) {
            return response()->json([
                'error'   => 404,
                'message' => 'Not found'
            ], 404);
        } else {
            $user->update($request->all());
            return response()->json(null, 204);
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
