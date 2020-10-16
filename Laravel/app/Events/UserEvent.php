<?php

namespace App\Events;

use App\Http\Resources\UserResource;
use App\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class UserEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets;

    public $authUser, $user;

    private $operation;

    /**
     * Create a new event instance.
     *
     * @param $operation
     * @param $authUser
     * @param $user
     * @return void
     */
    public function __construct($operation, $authUser, $user)
    {
        $this->operation = $operation;
        $this->authUser = $authUser;
        $this->user = new UserResource($user);
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('users');
    }

    public function broadcastAs()
    {
        return $this->operation;
    }

}
