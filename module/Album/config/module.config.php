<?php
namespace Album;

use Zend\Db\Adapter\AdapterInterface;
use Zend\EventManager\EventManager;
use Zend\ServiceManager\Factory\InvokableFactory;
use Zend\Router\Http\Segment;

use Album\EventListener\SongEventListener;

return [
    'router' => [
        'routes' => [
            'album' => [
                'type' => Segment::class,
                'options' => [
                    'route' => '/album[/:action[/:id]]',
                    'constraints' => [
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id' => '[0-9]+',
                    ],
                    'defaults' => [
                        'controller' => Controller\AlbumController::class,
                        'action' => 'index',
                    ],
                ],

                'may_terminate' => true,

                'child_routes' => [
                    'addsong' => [
                        'type' => Segment::class,
                        'options' => [
                            'route' => '/addsong[/:id]',
                            'constraints' => [
                                'id' => '[0-9]+',
                            ],
                            'defaults' => [
                                'controller' => Controller\SongController::class,
                                'action' => 'addsong',
                            ],
                        ],
                    ],
                    'editsong' => [
                        'type' => Segment::class,
                        'options' => [
                            'route' => '/editsong[/:song_id]',
                            'constraints' => [
                                'song_id' => '[0-9]+',
                            ],
                            'defaults' => [
                                'controller' => Controller\SongController::class,
                                'action' => 'editsong',
                            ],
                        ],
                    ],
                    'deletesong' => [
                        'type' => Segment::class,
                        'options' => [
                            'route' => '/deletesong[/:song_id]',
                            'constraints' =>  [
                                'song_id' => '[0-9]+',
                            ],
                            'defaults' => [
                                'controller' => Controller\SongController::class,
                                'action' => 'deletesong',
                            ],
                        ],
                    ],
                ],
            ],
        ],
    ],

    'view_manager' => [
        'template_path_stack' => [
            'album' => __DIR__ . '/../view',
        ],
    ],

    'service_manager' => [
        'factories' => [
            'song_event_manager' => function ($container) {
                $eventManager = new EventManager();
                $songEventListener = new SongEventListener(
                    $container->get(\Album\Service\AlbumService::class)
                );

                $songEventListener->attach($eventManager);

                return $eventManager;
            },
        ],
    ],
];
?>
