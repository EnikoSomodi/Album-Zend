<?php
namespace Blog\Factory;

// Out-sourced Libraries
use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;

// My Libraries
use Blog\Controller\ListController;
use Blog\Model\PostRepositoryInterface;

class ListControllerFactory implements FactoryInterface
{
  public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
  {
      return new ListController($container->get(PostRepositoryInterface::class));
  }
}
?>
