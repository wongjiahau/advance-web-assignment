<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Resources\MessageCollection;
use App\Http\Resources\MessageResource;
use App\Message;

class MessageController extends Controller
{
    
    public function index()
    {
        return new MessageCollection(MessageResource::collection(Message::all()));
    }

    public function show($id)
    {
        $message = Message::find($id);
        if(!$message) {
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
        $user =auth()->user();
        $request->validate([
            'group_id' => 'required|exists:groups,id',
            'content'  => 'required|max:200'
        ]);
        if($user->groups->find($request->group_id)) {
            $message = Message::create([
                'group_id' => $request->group_id,
                'user_id'  => $user->id,
                'content'  => $request->content
            ]);
            return response()->json([
                'id'         => $message->id,
                'created_at' => $message->created_at
            ]);
        } else {
            return response()->json([
                'error' => 'You cannot send message to this group.'
            ], 403);
        }
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
