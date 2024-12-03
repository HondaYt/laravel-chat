<?php

namespace App\Http\Controllers;

use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;

class MessageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Message::with('user');

        if ($request->has('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        $messages = $query->get()
            ->map(function ($message) {
                try {
                    return [
                        'id' => $message->id,
                        'user_id' => $message->user_id,
                        'user_name' => $message->user->name,
                        'message' => $message->message,
                        'created_at' => $message->created_at,
                        'updated_at' => $message->updated_at,
                    ];
                } catch (\Exception $e) {
                    // 復号化に失敗した場合は、エラーメッセージを返す
                    return [
                        'id' => $message->id,
                        'user_id' => $message->user_id,
                        'user_name' => $message->user->name,
                        'message' => '***メッセージを読み込めません***',
                        'created_at' => $message->created_at,
                        'updated_at' => $message->updated_at,
                    ];
                }
            });
        return response()->json($messages);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Message $message)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Message $message)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Message $message)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Message $message)
    {
        //
    }
}
