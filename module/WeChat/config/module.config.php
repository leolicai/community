<?php
/**
 * module.config.php
 *
 * @author: Leo <camworkster@gmail.com>
 * @version: 1.0
 */

namespace WeChat;


use Zend\Router\Http\Segment;
use Zend\ServiceManager\Factory\InvokableFactory;


return [

    'router' => [
        'routes' => [
            'wechat' => [
                'type' => Segment::class,
                'options' => [
                    'route' => '/wechat[/]',
                    'defaults' => [
                        'controller' => Controller\IndexController::class,
                        'action' => 'index',
                    ],
                ],
                'may_terminate' => true,
                'child_routes' => [
                    'index' => [
                        'type' => Segment::class,
                        'options' => [
                            'route'    => 'index[/:action[/:key]][:suffix]',
                            'constraints' => [
                                'action' => '[a-zA-Z][a-zA-Z0-9_\-]+',
                                'key' => '[a-zA-Z0-9_\-]+',
                                'suffix' => '(/|.html)',
                            ],
                            'defaults' => [
                                'controller' => Controller\IndexController::class,
                                'action'     => 'index',
                            ],
                        ],
                    ],

                    'message' => [
                        'type' => Segment::class,
                        'options' => [
                            'route'    => 'message[/:action[/:key]][:suffix]',
                            'constraints' => [
                                'action' => '[a-zA-Z][a-zA-Z0-9_\-]+',
                                'key' => '[a-zA-Z0-9_\-]+',
                                'suffix' => '(/|.html)',
                            ],
                            'defaults' => [
                                'controller' => Controller\MessageController::class,
                                'action'     => 'index',
                            ],
                        ],
                    ],
                    // TBC
                ],
            ],
        ],
    ],


    'controllers' => [
        'factories' => [
            Controller\IndexController::class => InvokableFactory::class,
            Controller\MessageController::class => InvokableFactory::class,
        ],
    ],


    'view_manager' => [
        'template_path_stack' => [
            __DIR__ . '/../view',
        ],
    ],

];