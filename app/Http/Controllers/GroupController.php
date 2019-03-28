<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Group;
use App\User;
use App\Http\Resources\GroupResource;
use App\Http\Resources\GroupCollection;

class GroupController extends Controller
{
    public function index()
    {
        return new GroupCollection(GroupResource::collection(Group::all()));
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
    //
}
