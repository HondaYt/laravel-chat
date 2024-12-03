<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Auth;
use App\Models\Message;

class ChatEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $message;
    public $user;
    private $shouldSave;

    public function __construct($message = null, $shouldSave = true)
    {
        $this->message = $message;
        $this->user = Auth::user();
        $this->shouldSave = $shouldSave;
    }

    public function broadcastOn(): array
    {
        if ($this->shouldSave) {
            // メッセージをDBに保存
            $messages = new Message();
            $messages->message = $this->message;
            $messages->user_id = $this->user->id;
            $messages->save();
        }

        return [
            new Channel('channel-chat'),
        ];
    }
}
