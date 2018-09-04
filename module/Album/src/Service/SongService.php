<?php
namespace Album\Service;

use Album\Model\Album;
use Album\Model\AlbumTable;
use Album\Model\Song;
use Album\Model\SongTable;

/**
 * Song Service.
 */
class SongService
{
    protected $albumTable;
    protected $songTable;

    /**
      * Constructor.
      *
      * @param  AlbumTable        $albumTable
      * @param  SongTable         $songTable
      */
    public function __construct(AlbumTable $albumTable, SongTable $songTable)
    {
        $this->albumTable   = $albumTable;
        $this->songTable    = $songTable;
    }

    /**
      * Get album of current song.
      *
      * @param  Song  $song
      * @return Album
      */
    public function getAlbumOfSong(Song $song)
    {
        return $this->albumTable->getAlbum($song->album_id);
    }
}
?>
