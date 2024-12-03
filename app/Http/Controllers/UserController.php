<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * ユーザー一覧を取得
     */
    public function index()
    {
        $users = User::select('id', 'name')
            ->withCount('messages')
            ->get()
            ->map(function ($user) {
                return [
                    'id' => $user->id,
                    'name' => $user->name,
                    'message_count' => $user->messages_count
                ];
            });
        return response()->json($users);
    }
}
