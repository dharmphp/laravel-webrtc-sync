<?php

namespace App\Events;

use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Redis;

class SlideChangedEvent
{
    use SerializesModels;

    public $presentationId;
    public $slideIndex;
    public $slideData;

    public function __construct($presentationId, $slideIndex, $slideData)
    {
        $this->presentationId = $presentationId;
        $this->slideIndex = $slideIndex;
        $this->slideData = $slideData;

        // publish directly to Redis channel
        Redis::publish('presentation-events', json_encode([
            'presentation_id' => $presentationId,
            'event' => 'slide-updated',
            'data' => [
                'slideIndex' => $slideIndex,
                'slideData' => $slideData,
                'timestamp' => microtime(true)
            ]
        ]));
    }
}
