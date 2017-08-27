<?php
/**
 * module.config.php
 *
 * Author: leo <camworkster@gmail.com>
 * Date: 17/8/27
 * Version: 1.0
 */

namespace Admin;

use Zend\Router\Http\Segment;
use Zend\ServiceManager\Factory\InvokableFactory;

return [


    'controllers' => [
        'factories' => [
            Controller\IndexController::class => InvokableFactory::class,
        ],
    ],

    'view_manager' => [
        'template_map' => [
            'layout/admin_simple'  => __DIR__ . '/../view/layout/simple.phtml',
            'layout/admin_layout' => __DIR__ . '/../view/layout/layout.phtml',
        ],
        'template_path_stack' => [
            __DIR__ . '/../view',
        ],
    ],


    'router' => [
        'routes' => [
            'admin' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/admin[/]',
                    'defaults' => [
                        'controller' => Controller\IndexController::class,
                        'action'     => 'index',
                    ],
                ],
                'may_terminate' => true,
                'child_routes' => [

                    'index' => [
                        'type' => Segment::class,
                        'options' => [
                            'route' => 'index[/:action[/:key]][:suffix]',
                            'constraints' => [
                                'action' => '[a-zA-Z][a-zA-Z0-9_\-]+',
                                'key' => '[a-zA-Z0-9_\-]+',
                                'suffix' => '(/|.html)',
                            ],
                            'defaults' => [
                                'controller' => Controller\IndexController::class,
                                'action' => 'index',
                            ],
                        ],
                    ],
                    // TBM
                ],
            ],
        ],
    ],

];