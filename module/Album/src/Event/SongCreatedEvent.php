<?php
namespace Album\Event;

use Album\Event\BaseSongEvent;

class SongCreatedEvent extends BaseSongEvent
{
    const NAME = 'song.created';

    /**
     * {@inheritdoc}
     */
    public function getEventName()
    {
        return self::NAME;
    }
}
?>
