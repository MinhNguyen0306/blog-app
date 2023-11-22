<?php

namespace App\Events;

use App\Models\Comment;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NewComment implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $comment;

    /**
     * Create a new event instance.
     */
    public function __construct(Comment $comment)
    {
        $this->comment = $comment;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new Channel('post.' . $this->comment->post->id),
        ];
    }

    public function broadcastWith()
    {
        return [
            'content' => $this->comment->content,
            'created_at' => $this->comment->created_at->toFormattedDateString(),
            'user' => [
                'id' => $this->comment->user->id,
                'name' => $this->comment->user->name,
                'email' => $this->comment->user->email,
                'avatar' => $this->comment->user->avatar
            ]
        ];
    }
}
