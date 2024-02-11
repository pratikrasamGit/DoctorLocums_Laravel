<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class OfferReceived implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $fullname;
    public $job;
    public $jobLink;
    public $facilityName;
    public $jobSpeciality;
    public $message;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($fullname)
    {
        $this->fullname = $fullname;
        //$this->jobLink  = $job->id;
        //$this->facilityName = $job->facility->name;
        $this->message = $fullname;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return ['offer-received'];
        //return new PrivateChannel('offer-received');
    }
}
