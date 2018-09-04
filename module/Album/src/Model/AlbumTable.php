<?php
namespace Album\Model;

use RuntimeException;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\Sql\Select;
use Zend\Db\TableGateway\TableGatewayInterface;
use Zend\Paginator\Adapter\DbSelect;
use Zend\Paginator\Paginator;

/**
 * Album Table.
 */
class AlbumTable
{
    private $tableGateway;

    /**
      * Constructor.
      *
      * @param TableGatewayInterface $tableGateway
      */
    public function __construct(TableGatewayInterface $tableGateway)
    {
        $this->tableGateway = $tableGateway;
    }

    /**
      * Fetch All Albums.
      *
      * @return
      */
    public function fetchAll($paginated = false)
    {
        if ($paginated) {
            return $this->fetchPaginatedResults();
        }

        return $this->tableGateway->select();
    }

    /**
      * Fetch Paginated Results.
      *
      * @return
      */
    private function fetchPaginatedResults()
    {
        $select = new Select($this->tableGateway->getTable());

        $resultSetPrototype = new ResultSet();
        $resultSetPrototype->setArrayObjectPrototype(new Album());

        $paginatorAdapter = new DbSelect(
            $select,
            $this->tableGateway->getAdapter(),
            $resultSetPrototype
        );

        $paginator = new Paginator($paginatorAdapter);

        return $paginator;
    }

    /**
      * Get Album.
      *
      * @return
      */
    public function getAlbum($id)
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

    public function saveAlbum(Album $album)
    {
        $data = [
            'artist'    => $album->artist,
            'title'     => $album->title,
            'imagepath' => $album->imagepath,
            'number_of_songs' => $album->number_of_songs,
        ];

        $id = (int) $album->id;

        if ($id === 0) {
            $this->tableGateway->insert($data);
            return;
        }

        if (!$this->getAlbum($id)) {
            throw new RuntimeException(sprintf(
                'Cannot update album with identifier %d; does not exist',
                $id
            ));
        }

        $this->tableGateway->update($data, ['id' => $id]);
    }

    public function deleteAlbum($id)
    {
        $this->tableGateway->delete(['id' => (int) $id]);
    }
}
?>
