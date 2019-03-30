<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use App\Http\Resources\UserCollection;
use App\Http\Resources\UserResource;

class ProfileController extends Controller
{
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

    public function update(Request $request, $id)
    {
        $user = auth()->user();
        $request->validate([
            'name'             => 'required',
            'password'         => 'required|min:8',
            'password_confirm' => 'required|same:password'
        ]);
        if($user->id == $id) {
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
}
