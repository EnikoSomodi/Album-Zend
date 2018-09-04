<?php
namespace Album\Event;

use Album\Event\BaseSongEvent;

class SongDeletedEvent extends BaseSongEvent
{
    const NAME = 'song.deleted';

    /**
     * {@inheritdoc}
     */
    public function getEventName()
    {
        return self::NAME;
    }
}
?>
