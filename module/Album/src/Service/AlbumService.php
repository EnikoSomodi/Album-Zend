<?php
namespace Album\Service;

use Zend\Db\Adapter\AdapterInterface;
use Zend\Db\Adapter\Driver\ResultInterface;
use Zend\Db\Sql\Expression;
use Zend\Db\Sql\Select;
use Zend\Db\Sql\Sql;

use Album\Model\Album;
use Album\Model\AlbumTable;
use Album\Model\Song;
use Album\Model\SongTable;

/**
 * Album Service.
 */
class AlbumService
{
    protected $albumTable;
    protected $songTable;
    protected $songservice;
    protected $db;

    /**
      * Constructor.
      *
      * @param  AlbumTable        $albumTable
      * @param  SongTable         $songTable
      * @param  SongService       $songService
      * @param  AdapterInterface  $db
      */
    public function __construct(
        AlbumTable $albumTable,
        SongTable $songTable,
        SongService $songService,
        AdapterInterface $db
    ) {
        $this->albumTable   = $albumTable;
        $this->songTable    = $songTable;
        $this->songService = $songService;
        $this->db = $db;
    }

    /**
      * Get song number.
      *
      *@param  Song    $song
      *@return int
      */
    public function countSongs(Album $album)
    {
        $select = new Select('Song');
        $select->columns(['song_number' => new Expression("COUNT(album_id)")])
               ->where("album_id = $album->id");

        $sql       = new Sql($this->db);
        $statement = $sql->prepareStatementForSqlObject($select);
        $result    = $statement->execute();

        if (!$result instanceof ResultInterface) {
            throw new RuntimeException(
                'Database error'
            );
        }

        return (int) $result->current()['song_number'];
    }

    /**
      * Update song count.
      *
      *@param  Song    $song
      *@return void
      */
    public function updateSongCount(Album $album)
    {
        $album->number_of_songs = $this->countSongs($album);
        $this->albumTable->saveAlbum($album);
    }

    /**
      * Get songs by album.
      *
      * @param  Album $album
      * @return array
      */
    public function getSongs(Album $album)
    {
        return $this->songTable->fetchByAlbum($album);
    }
}
?>
