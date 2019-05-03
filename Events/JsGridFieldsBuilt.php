<?php

namespace Modules\Jsgrid\Events;

use Illuminate\Queue\SerializesModels;

class JsGridFieldsBuilt
{
    use SerializesModels;

    public $fields;
    public $name;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(string $name, array &$fields)
    {
        $this->fields = $fields;
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
