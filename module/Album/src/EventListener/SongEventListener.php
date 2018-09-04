<?php
namespace Album\EventListener;

use Zend\EventManager\AbstractListenerAggregate;
use Zend\EventManager\EventManagerInterface;

use Album\Event\SongCreatedEvent;
use Album\Event\SongDeletedEvent;
use Album\Model\Song;
use Album\Service\AlbumService;
use Album\Service\SongService;

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
      * @param  SongService  $songService
      */
    public function __construct(
        AlbumService $albumService,
        SongService $songService
    ) {
        $this->albumService = $albumService;
        $this->songService = $songService;
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
        $album = $this->songService->getAlbum($song);
        $this->albumService->updateSongCount($album);
    }

    /**
      * On Song Deleted.
      *
      * @param SongDeletedEvent $events
      */
    public function onSongDeleted(SongDeletedEvent $event)
    {
        $song = $event->getParam('song');
        $album = $this->songService->getAlbum($song);
        $this->albumService->updateSongCount($album);
    }
}
?>
