<?php
namespace Album\Model;

use RuntimeException;
use Zend\Db\Sql\Select;
use Zend\Db\TableGateway\TableGatewayInterface;

use Album\Model\Album;

/**
 * Song Table.
 */
class SongTable
{
    private $tableGateway;

    public function __construct(TableGatewayInterface $tableGateway)
    {
        $this->tableGateway = $tableGateway;
    }

    public function fetchByAlbum(Album $album)
    {
        return $this->tableGateway->select(['album_id' => $album->id]);
    }

    public function fetchSongById($id)
    {
        $id = (int) $id;

        $rowset = $this->tableGateway->select(['id' => $id]);
        $row    = $rowset->current();

        if (!$row) {
            throw new RuntimeException(sprintf(
                'Could not find row with identifier %d',
                $id
            ));
        }

        return $row;
    }

    public function saveSong(Song $song)
    {
        $data = [
            'album_id'   => $song->album_id,
            'song_title' => $song->song_title,
            'songpath'   => $song->songpath,
        ];

        $id = (int) $song->id;

        if ($id === 0) {
            $this->tableGateway->insert($data);
            return;
        }

        $this->tableGateway->update($data, ['id' => $id]);
    }

    public function deleteSong($song_id)
    {
        $this->tableGateway->delete(['id' => (int) $song_id]);
    }
}
?>
