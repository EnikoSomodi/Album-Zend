<?php
namespace Blog\Factory;

// Out-sourced Libraries
use Interop\Container\ContainerInterface;
use Zend\Db\Adapter\AdapterInterface;
use Zend\ServiceManager\Factory\FactoryInterface;
use Zend\Hydrator\Reflection as ReflectionHydrator;

// My Libraries
use Blog\Model\ZendDbSqlRepository;
use Blog\Model\Post;

class ZendDbSqlRepositoryFactory implements FactoryInterface
{
  public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
  {
      //return new ZendDbSqlRepository($container->get(AdapterInterface::class));
      return new ZendDbSqlRepository(
            $container->get(AdapterInterface::class),
            new ReflectionHydrator(),
            new Post('', '')
        );
  }
}
?>
