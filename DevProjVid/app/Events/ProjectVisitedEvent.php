<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use App\User as User;
use App\Project as Project;

class ProjectVisitedEvent {

    use InteractsWithSockets,
        SerializesModels;

    public $user;
    public $project;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(User $user, Project $project) {
        
        $this->user = $user;
        
        $this->project = $project;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return Channel|array
     */
    public function broadcastOn() {
        return new PrivateChannel('channel-name');
    }

}
