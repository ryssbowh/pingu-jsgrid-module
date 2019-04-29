<?php

namespace Modules\Jsgrid\Events;

use Illuminate\Queue\SerializesModels;

class JsGridOptionsBuilt
{
    use SerializesModels;

    public $options;
    public $name;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(string $name, array &$options)
    {
        $this->options = $options;
        $this->name = $name;
    }

    /**
     * Get the channels the event should be broadcast on.
     *
     * @return array
     */
    public function broadcastOn()
    {
        return [];
    }
}
