<?php
namespace Album\EventListener;

use Zend\EventManager\AbstractListenerAggregate;
use Zend\EventManager\EventManagerInterface;

use Album\Event\SongCreatedEvent;
use Album\Event\SongDeletedEvent;
use Album\Model\Song;
use Album\Service\AlbumService;

/**
 * Song Event Listener.
 *
 * @see AbstractListenerAggregate
 */
class SongEventListener extends AbstractListenerAggregate
{
    /**
      * Constructor.
      *
      * @param  AlbumService $albumService
      */
    public function __construct(AlbumService $albumService)
    {
        $this->albumService = $albumService;
    }

    /**
      * Attach.
      *
      * @param EventManagerInterface $events
      * @param int                   $priority
      */
    public function attach(EventManagerInterface $events, $priority = 1)
    {
        $this->listeners[] = $events->attach(
            SongCreatedEvent::NAME,
            [$this, 'onSongCreated'],
            $priority
        );

        $this->listeners[] = $events->attach(
            SongDeletedEvent::NAME,
            [$this, 'onSongDeleted'],
            $priority
        );
    }

    /**
      * On Song Created.
      *
      * @param SongCreatedEvent $events
      */
    public function onSongCreated(SongCreatedEvent $event)
    {
        $song = $event->getParam('song');

        $this->albumService->updateSongCount($song);
    }

    /**
      * On Song Deleted.
      *
      * @param SongDeletedEvent $events
      */
    public function onSongDeleted(SongDeletedEvent $event)
    {
        $song = $event->getParam('song');

        $this->albumService->updateSongCount($song);
    }
}
?>
