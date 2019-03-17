<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Resources\MessageCollection;
use App\Http\Resources\MessageResource;

class MessageController extends Controller
{
    
    public function index()
    {
        return new MessageCollection(MessageResource::collection(Message::all()));
    }

    public function show($id)
    {
        $group = Message::find($id);
        if(!$group) {
            return response()->json([
                'error' => 404,
                'message' => 'Not found'
            ], 404);
        } else {
            return new MessageResource($group);
        }
    }

    public function store(Request $request) 
    {
        $request->validate([
            'name'    => 'required|alpha_num|max:150|unique:groups,name',
            'creator' => 'required|exists:users,id',
        ]);
        $group = Message::create($request->all());
        $group->users()->attach([$request->creator], ["is_admin" => true]);
        return response()->json([
            'id'         => $group->id,
            'created_at' => $group->created_at
        ]);
    }

    public function update(Request $request, $id)
    {
        $group = Message::find($id);
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
        $group = Message::find($id);
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

}
