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
        $user = auth()->user();
        return new GroupCollection(GroupResource::collection($user->groups));
    }

    public function show($id)
    {
        $user = auth()->user();
        $group = $user->groups->find($id);
        if ($group) {
            return new GroupResource($group);
        } else {
            return response()->json([
                'error' => "No group have the id of $id or $user->name does not have authority over this group."
            ], 404);
        }
    }

    public function store(Request $request)
    {
        $creator = auth()->user();
        if ($creator) {
            $request->validate([
                'name'    => 'required|max:150|unique:groups,name',
            ]);
            $group = Group::create($request->all());
            $group->users()->attach([$creator->id], ["is_admin" => true]);
            return response()->json([
                'id'         => $group->id,
                'created_at' => $group->created_at
            ], 201);
        } else {
            return response()->json([
                "error" => 404,
                "message" => "Not found"
            ]);
        }
    }

    public function update(Request $request, $id)
    {
        $user = auth()->user();
        $request->validate([
            'name'    => 'required|max:150',
        ]);
        $group = Group::find($id);
        if ($group) {
            $correspondingUser = $group->users->find($user->id);
            // check if the user is admin of this group
            if ($group && $correspondingUser && $correspondingUser->pivot->is_admin) {
                $group->update($request->all());
                return response()->json(null, 204);
            } else {
                return response()->json([
                    'message' => 'You are not authorized to update the data of this group.'
                ], 403);
            }
        } else {
            return response()->json([
                'error'   => 404,
                'message' => 'Not found'
            ], 404);
        }
    }

    public function destroy($id)
    {
        $group = Group::find($id);
        if (!$group) {
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
        $user = auth()->user();
        $request->validate([
            'group_id'   => 'required|exists:groups,id',
            'user_email' => 'required|exists:users,email'
        ]);
        $group = $user->groups->find($request->group_id);
        if ($group && $group->pivot->is_admin) {
            $userToBeAdded = User::where('email', $request->user_email)->first();
            $group->users()->detach([$userToBeAdded->id]); // in case the user had been added before
            $group->users()->attach([$userToBeAdded->id], ["is_admin" => false]); // by default, new group member is not admin
            return response()->json(null, 204);
        } else {
            return response()->json([
                'error' => "No group have the id of $request->group_id or $user->name does not have authority over this group."
            ]);
        }
    }

    // For user to exit a group
    public function exit($id)
    {
        $user = auth()->user();
        $group = Group::find($id);
        // Make sure the requesting user is already in the target group
        if ($group && $group->users->find($user->id)) {
            $group->users()->detach([$user->id]);
            return response()->json(null, 204);
        } else {
            return response()->json([
                'error' => 'Not found'
            ], 404);
        }
    }

    // For promoting another user from the same group to become an admin
    public function promote(Request $request)
    {
        $user = auth()->user();
        $request->validate([
            'group_id'   => 'required|exists:groups,id',
            'user_email' => 'required|exists:users,email'
        ]);
        $group = $user->groups->find($request->group_id);
        if ($group && $group->pivot->is_admin) {
            $userToBePromoted = User::where('email', $request->user_email)->first();
            $group->users()->detach([$userToBePromoted->id]);
            $group->users()->attach([$userToBePromoted->id], ["is_admin" => true]);
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

    // For group admin to kick a group member
    public function kick(Request $request)
    {
        $user = auth()->user();
        $request->validate([
            'group_id'   => 'required|exists:groups,id',
            'user_email' => 'required|exists:users,email'
        ]);
        $group = $user->groups->find($request->group_id);
        if ($group && $group->pivot->is_admin) {
            $userToBeKicked = User::where('email', $request->user_email)->first();
            $group->users()->detach([$userToBeKicked->id]);
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
