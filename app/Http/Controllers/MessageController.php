<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Resources\MessageCollection;
use App\Http\Resources\MessageResource;
use App\Message;
use App\Group;

class MessageController extends Controller
{
    
    public function index()
    {
        return new MessageCollection(MessageResource::collection(Message::all()));
    }

    public function retrieve(Request $request, $groupId)
    {
        $user =auth()->user();
        $group = Group::find($groupId);
        if($group && $group->users->find($user->id)) {
            return new MessageCollection(MessageResource::collection($group->messages));
        } else {
            return response()->json([
                'error' => 'You cannot view messages of this group.'
            ], 403);
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
            ], 201);
        } else {
            return response()->json([
                'error' => 'You cannot send message to this group.'
            ], 403);
        }
    }

    public function update(Request $request, $id)
    {
        $user =auth()->user();
        $message = $user->messages()->find($id);
        if($message) {
            // check if the user is still in the group
            if($message->group->users()->find($user->id)) {
                $message->update($request->all());
                return response()->json(null, 204);
            } else {
                return response()->json([
                    'message' => 'You cannot update this message anymore.'
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
        $user =auth()->user();
        $message = $user->messages()->find($id);
        if($message) {
            // check if the user is still in the group
            if($message->group->users()->find($user->id)) {
                $message->delete();
                return response()->json(null, 204);
            } else {
                return response()->json([
                    'message' => 'You cannot delete this message anymore.'
                ], 403);
            }
            
        } else {
            return response()->json([
                'error'   => 404,
                'message' => 'Not found'
            ], 404);
        }
    }

}
