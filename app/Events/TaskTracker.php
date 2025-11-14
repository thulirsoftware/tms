<?php

namespace App\Events;

use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class TaskTracker implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $username;

    public $message;

    public $to;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($username,$message='added New Task',$to='')
    {
        $this->username = $username;
        $this->message  = $message;
        $this->to  = $to;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return Channel|array
     */
    public function broadcastOn()
    {
        return ['task-tracker'];
    }
}