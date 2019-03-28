<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Group;
use App\User;
use Bouncer;
use App\Http\Resources\GroupResource;
use App\Http\Resources\GroupCollection;

class GroupController extends Controller
{
    public function index()
    {
        $user =auth()->user();
        return new GroupCollection(GroupResource::collection($user->groups));
    }

    public function show($id)
    {
        $group = Group::find($id);
        if(!$group) {
            return response()->json([
                'error' => 404,
                'message' => 'Not found'
            ], 404);
        } else {
            return new GroupResource($group);
        }
    }

    public function store(Request $request) 
    {
        $creator = auth()->user();
        if($creator) {
            $request->validate([
                'name'    => 'required|alpha_num|max:150|unique:groups,name',
            ]);
            $group = Group::create($request->all());
            $group->users()->attach([$creator->id], ["is_admin" => true]);
            return response()->json([
                'id'         => $group->id,
                'created_at' => $group->created_at
            ]);
        } else {
            return response()->json([
                "error" => 404,
                "message" => "Not found"
            ]);
        }
    }

    public function update(Request $request, $id)
    {
        $group = Group::find($id);
        if(!$group) {
            return response()->json([
                'error'   => 404,
                'message' => 'Not found'
            ], 404);
        } else {
            $group->update($request->all());
            return response()->json(null, 204);
        }
    }

    public function destroy($id)
    {
        $group = Group::find($id);
        if(!$group) {
            return response()->json([
                'error'   => 404,
                'message' => 'Not found'
            ], 404);
        } else {
            $group->delete();
            return response()->json(null, 204);
        }
    }
    
    // For adding new user into this group
    public function add(Request $request) 
    {
        $user =auth()->user();
        $request->validate([
            'group_id'   => 'required|exists:groups,id',
            'user_email' => 'required|exists:users,email'
        ]);
        $group = $user->groups->find($request->group_id);
        if($group && $group->pivot->is_admin) {
            $userToBeAdded = User::where('email', $request->user_email)->first();
            $group->users()->attach([$userToBeAdded->id], ["is_admin" => false]);
            return response()->json(null, 204);
        } else {
            return response()->json([
                'error' => "
                    No group have the id of $request->group_id
                    or
                    $user->name does not have authority over this group.
                "
            ]);
        }
    }
}
