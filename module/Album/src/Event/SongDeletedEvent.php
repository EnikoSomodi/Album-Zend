<?php
namespace Album\Event;

use Album\Event\BaseSongEvent;

class SongDeletedEvent extends BaseSongEvent
{
    const NAME = 'song.deleted';

    /**
     * Get Event Name.
     *
     * @return const
     */
    public function getEventName()
    {
        return self::NAME;
    }
}
?>
