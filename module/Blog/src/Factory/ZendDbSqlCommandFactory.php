<?php
namespace Blog\Factory;

// Out-sourced Libraries
use Interop\Container\ContainerInterface;
use Zend\Db\Adapter\AdapterInterface;
use Zend\ServiceManager\Factory\FactoryInterface;

// My Libraries
use Blog\Model\ZendDbSqlCommand;

class ZendDbSqlCommandFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        return new ZendDbSqlCommand($container->get(AdapterInterface::class));
    }
}
?>
