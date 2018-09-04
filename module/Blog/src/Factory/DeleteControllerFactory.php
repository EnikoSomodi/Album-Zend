<?php
namespace Blog\Factory;

// Out-sourced Libraries
use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;

// My Libraries
use Blog\Controller\DeleteController;
use Blog\Model\PostCommandInterface;
use Blog\Model\PostRepositoryInterface;

class DeleteControllerFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        return new DeleteController(
            $container->get(PostCommandInterface::class),
            $container->get(PostRepositoryInterface::class)
        );
    }
}
?>
