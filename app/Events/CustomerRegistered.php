<?php

namespace App\Events;

use App\Models\User;
use App\Models\CustomerProfile;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class CustomerRegistered
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * The user instance.
     *
     * @var \App\Models\User
     */
    public $user;

    /**
     * The customer profile instance.
     *
     * @var \App\Models\CustomerProfile
     */
    public $customerProfile;

    /**
     * Create a new event instance.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\CustomerProfile  $customerProfile
     * @return void
     */
    public function __construct(User $user, CustomerProfile $customerProfile)
    {
        $this->user = $user;
        $this->customerProfile = $customerProfile;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('customer.'.$this->user->id);
    }
}