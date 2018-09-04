<?php
namespace Blog\Model;

//  Out-sourced Libraries
use InvalidArgumentException;
use RuntimeException;
use Zend\Hydrator\HydratorInterface;
use Zend\Db\Adapter\AdapterInterface;
use Zend\Db\Adapter\Driver\ResultInterface;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\ResultSet\HydratingResultSet;
use Zend\Db\Sql\Sql;
//use Zend\Hydrator\Reflection as ReflectionHydrator;

class ZendDbSqlRepository implements PostRepositoryInterface
{
    private $db;
    private $hydrator;
    private $postPrototype;

    /*
    public function __construct(AdapterInterface $db)
    {
        $this->db = $db;
    }
    */
    public function __construct(
      AdapterInterface $db,
      HydratorInterface $hydrator,
      Post $postPrototype
    ) {
      $this->db            = $db;
      $this->hydrator      = $hydrator;
      $this->postPrototype = $postPrototype;
    }

    // Implementation of methods from PostRepositoryInterface for working with DB
    public function findAllPosts()
    {
        $sql = new Sql($this->db);
        $select = $sql->select('posts');
        $stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();

        if (! $result instanceof ResultInterface || ! $result->isQueryResult()) {
            return [];
        }

        /*
        $resultSet = new HydratingResultSet(
            new ReflectionHydrator(),
              new Post('', '')
        );
        */
        $resultSet = new HydratingResultSet($this->hydrator, $this->postPrototype);


        $resultSet->initialize($result);
        return $resultSet;
    }

    public function findPost($id)
    {
        $sql = new Sql($this->db);
        $select = $sql->select('posts');
        $select->where(['id = ?' => $id]);

        $statement = $sql->prepareStatementForSqlObject($select);
        $result = $statement->execute();

        if (!$result instanceof ResultInterface || !$result->isQueryResult()) {
            throw new RuntimeException(sprintf(
              'Failed retrieving blog post with identifier "%s"; unknown database error.',
              $id
            ));
        }

        $resultSet = new HydratingResultSet($this->hydrator, $this->postPrototype);
        $resultSet->initialize($result);
        $post = $resultSet->current();

        if (!$post) {
            throw new InvalidArgumentException(sprintf(
              'Blog post with identifier "%s" not found',
              $id
            ));
        }

        return $post;
    }
}
?>
