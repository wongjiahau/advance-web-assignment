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
        if(!$user) {
            return response()->json([
                'error' => 404,
                'message' => 'Not found'
            ], 404);
        } else {
            return new UserResource($user);
        }
    }

    public function store(Request $request) 
    {
        $user = User::create($request->all());
        return response()->json([
            'id'         => $user->id,
            'created_at' => $user->created_at
        ]);
    }

    public function update(Request $request, $id)
    {
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
