@extends('layouts.layout')

@section('content')
    <div class="chat-container">
        <div class="message-list" id="message-list">
            @foreach ($messages as $message)
                <div class="message-wrapper {{ $message->user->id === auth()->id() ? 'message-mine' : 'message-others' }}">
                    <div class="message-info">
                        <span class="user-name">{{ $message->user->name }}</span>
                    </div>
                    <div class="message-bubble">
                        @php
                            try {
                                echo e($message->message);
                            } catch (\Exception $e) {
                                echo '***メッセージを読み込めません***';
                            }
                        @endphp
                        <span class="message-time">{{ $message->created_at->format('H:i') }}</span>
                    </div>
                </div>
            @endforeach
        </div>
        <div class="message-input-container">
            <input type="text" id="message" name="message" placeholder="メッセージを入力">
            <button id="send-button">
                <i class="fas fa-paper-plane"></i>
            </button>
        </div>
    </div>
@endsection
