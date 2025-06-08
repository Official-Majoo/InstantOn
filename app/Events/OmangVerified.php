<?php

namespace App\Events;

use App\Models\CustomerProfile;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class OmangVerified
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * The customer profile instance.
     *
     * @var \App\Models\CustomerProfile
     */
    public $customerProfile;

    /**
     * The Omang data.
     *
     * @var array
     */
    public $omangData;

    /**
     * Create a new event instance.
     *
     * @param  \App\Models\CustomerProfile  $customerProfile
     * @param  array  $omangData
     * @return void
     */
    public function __construct(CustomerProfile $customerProfile, array $omangData)
    {
        $this->customerProfile = $customerProfile;
        $this->omangData = $omangData;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('customer.'.$this->customerProfile->user_id);
    }
}