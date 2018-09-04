<?php
namespace Blog;

// Out-sourced Libraries
use Zend\Router\Http\Literal;
use Zend\ServiceManager\Factory\InvokableFactory;
use Zend\Router\Http\Segment;

return [
    'service_manager' => [
        'aliases'   => [
            // Model\PostRepositoryInterface::class => Model\PostRepository::class,
            Model\PostRepositoryInterface::class => Model\ZendDbSqlRepository::class,
            // Model\PostCommandInterface::class => Model\PostCommand::class,
            Model\PostCommandInterface::class => Model\ZendDbSqlCommand::class,
        ],
        'factories' => [
            Model\PostRepository::class => InvokableFactory::class,
            Model\ZendDbSqlRepository::class => Factory\ZendDbSqlRepositoryFactory::class,
            Model\PostCommand::class => InvokableFactory::class,
            Model\ZendDbSqlCommand::class => Factory\ZendDbSqlCommandFactory::class,

        ],
    ],

    'controllers' => [
        'factories' => [
            Controller\ListController::class => Factory\ListControllerFactory::class,
            Controller\WriteController::class => Factory\WriteControllerFactory::class,
            Controller\DeleteController::class => Factory\DeleteControllerFactory::class,
        ],
    ],

    'router' => [
        'routes' => [
            // Define a NEW route called "blog"
            'blog' => [
                'type'    => Literal::class,

                // Configure the route itself
                'options' => [
                    // Listen to "/blog" as URI
                    'route'    => '/blog',

                    // Define default controller&action to be called when this route is called
                    'defaults' => [
                        'controller' => Controller\ListController::class,
                        'action'     => 'index',
                    ],
                ],

                'may_terminate' => true,

                'child_routes' => [
                    'detail' => [
                        'type' => Segment::class,
                        'options' => [
                            'route' => '/:id',
                            'defaults' => [
                                'action' => 'detail',
                            ],
                            'constraints' => [
                                'id' => '[1-9]\d*',
                            ],
                        ],
                    ],

                    'add' => [
                      'type' => Literal::class,
                      'options' => [
                          'route' => '/add',
                          'defaults' => [
                              'controller' => Controller\WriteController::class,
                              'action' => 'add',
                          ],
                        ],
                    ],

                    'edit' => [
                      'type' => Segment::class,
                      'options' => [
                          'route' => '/edit/:id',
                          'defaults' => [
                              'controller' => Controller\WriteController::class,
                              'action' => 'edit',
                          ],
                          'constraints' => [
                              'id' => '[1-9]\d*'
                          ],
                      ],
                    ],

                    'delete' => [
                        'type' => Segment::class,
                        'options' => [
                            'route' => '/delete/:id',
                            'defaults' => [
                                'controller' => Controller\DeleteController::class,
                                'action' => 'delete',
                            ],
                            'constraints' => [
                                'id' => '[1-9]\d*'
                            ],
                        ],
                    ],
                ],
            ],
        ],
    ],

    'view_manager' => [
        'template_path_stack' => [
            __DIR__ . '/../view',
        ],
    ],
];
?>
