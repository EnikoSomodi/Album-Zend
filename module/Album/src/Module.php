<?php
namespace Album;

use Zend\Db\Adapter\AdapterInterface;
use Zend\ModuleManager\Feature\ConfigProviderInterface;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;

class Module implements ConfigProviderInterface
{
    public function getConfig()
    {
        return include __DIR__ . '/../config/module.config.php';
    }

    public function getServiceConfig()
    {
        return [
            'factories' => [
                Model\AlbumTable::class => function($container) {
                  $tableGateway = $container->get(Model\AlbumTableGateway::class);
                  return new Model\AlbumTable($tableGateway);
                },
                Model\SongTable::class => function($container) {
                  $tableGateway = $container->get(Model\SongTableGateway::class);
                  return new Model\SongTable($tableGateway);
                },
                Model\AlbumTableGateway::class => function ($container) {
                  $dbAdapter = $container->get(AdapterInterface::class);
                  $resultSetPrototype = new ResultSet();
                  $resultSetPrototype->setArrayObjectPrototype(new Model\Album());
                  return new TableGateway(
                      'album',
                      $dbAdapter,
                      null,
                      $resultSetPrototype
                  );
                },
                Model\SongTableGateway::class => function($container) {
                  $dbAdapter = $container->get(AdapterInterface::class);
                  $resultSetPrototype = new ResultSet();
                  $resultSetPrototype->setArrayObjectPrototype(new Model\Song());
                  return new TableGateway(
                      'song',
                      $dbAdapter,
                      null,
                      $resultSetPrototype
                  );
                },
                Service\AlbumService::class => function($container) {
                    return new Service\AlbumService(
                        $container->get(Model\AlbumTable::class),
                        $container->get(Model\SongTable::class),
                        $container->get(Service\SongService::class),
                        $container->get(AdapterInterface::class)
                    );
                },
                Service\SongService::class => function($container) {
                    return new Service\SongService(
                        $container->get(Model\AlbumTable::class),
                        $container->get(Model\SongTable::class)
                    );
                }
            ],
        ];
    }

    public function getControllerConfig()
    {
        return [
            'factories' => [
                Controller\AlbumController::class => function($container) {
                    return new Controller\AlbumController(
                        $container->get(Model\AlbumTable::class),
                        $container->get(Model\SongTable::class)
                    );
                },
                Controller\SongController::class => function($container) {
                    return new Controller\SongController(
                        $container->get(Model\SongTable::class),
                        $container->get(Service\SongService::class),
                        $container->get('song_event_manager')
                    );
                }
            ],
        ];
    }
}

?>
