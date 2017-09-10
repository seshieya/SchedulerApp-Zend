<?php
/**
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application;

use Zend\Router\Http\Literal;
use Zend\Router\Http\Segment;
use Zend\ServiceManager\Factory\InvokableFactory;


use Application\Factories\ScheduleControllerFactory;
use Application\Factories\EmailControllerFactory;
use Application\Factories\LoginControllerFactory;

return [
    'router' => [
        'routes' => [
            'home' => [
                'type' => Literal::class,
                'options' => [
                    'route'    => '/',
                    'defaults' => [
                        'controller' => Controller\IndexController::class,
                        'action'     => 'index',
                    ],
                ],
            ],
            'create' => [
                'type' => Literal::class,
                'options' => [
                    'route'    => '/schedule/create',
                    'defaults' => [
                        'controller' => Controller\ScheduleController::class,
                        'action'     => 'create',
                    ],
                ],
            ],
            'draft' => [
                'type' => Literal::class,
                'options' => [
                    'route'    => '/draft',
                    'defaults' => [
                        'controller' => Controller\ScheduleController::class,
                        'action'     => 'draft',
                    ],
                ],
            ],
            'save' => [
                'type' => Literal::class,
                'options' => [
                    'route'    => '/save',
                    'defaults' => [
                        'controller' => Controller\ScheduleController::class,
                        'action'     => 'save',
                    ],
                ],
            ],
            'generateDates' => [
                'type' => Literal::class,
                'options' => [
                    'route'    => '/schedule/dates',
                    'defaults' => [
                        'controller' => Controller\ScheduleController::class,
                        'action'     => 'dates',
                    ],
                ],
            ],
            'previewBeforeDownload' => [
                'type' => Literal::class,
                'options' => [
                    'route'    => '/schedule/preview',
                    'defaults' => [
                        'controller' => Controller\ScheduleController::class,
                        'action'     => 'preview',
                    ],
                ],
            ],
            'login' => [
                'type' => Literal::class,
                'options' => [
                    'route'    => '/',
                    'defaults' => [
                        'controller' => Controller\LoginController::class,
                        'action'     => 'login',
                    ],
                ],
            ],
            'loginVerify' => [
                'type' => Literal::class,
                'options' => [
                    'route'    => '/logincheck',
                    'defaults' => [
                        'controller' => Controller\LoginController::class,
                        'action'     => 'loginVerify',
                    ],
                ],
            ],
            'logout' => [
                'type' => Literal::class,
                'options' => [
                    'route'    => '/logout',
                    'defaults' => [
                        'controller' => Controller\LoginController::class,
                        'action'     => 'logout',
                    ],
                ],
            ],
            'signup' => [
                'type' => Literal::class,
                'options' => [
                    'route'    => '/signup',
                    'defaults' => [
                        'controller' => Controller\LoginController::class,
                        'action'     => 'signup',
                    ],
                ],
            ],
            'saveSignup' => [
                'type' => Literal::class,
                'options' => [
                    'route'    => '/thanks',
                    'defaults' => [
                        'controller' => Controller\LoginController::class,
                        'action'     => 'saveSignup',
                    ],
                ],
            ],
            'downloadPdf' => [
                'type' => Literal::class,
                'options' => [
                    'route'    => '/schedule/download',
                    'defaults' => [
                        'controller' => Controller\PdfController::class,
                        'action'     => 'download',
                    ],
                ],
            ],
            'emailDraft' => [
                'type' => Literal::class,
                'options' => [
                    'route'    => '/schedule/emaildraft',
                    'defaults' => [
                        'controller' => Controller\EmailController::class,
                        'action'     => 'draft',
                    ],
                ],
            ],
            'emailSchedule' => [
                'type' => Literal::class,
                'options' => [
                    'route'    => '/schedule/email',
                    'defaults' => [
                        'controller' => Controller\EmailController::class,
                        'action'     => 'email',
                    ],
                ],
            ],
            'emailTradeTemp' => [
                'type' => Literal::class,
                'options' => [
                    'route'    => '/schedule/trade',
                    'defaults' => [
                        'controller' => Controller\EmailController::class,
                        'action'     => 'trade',
                    ],
                ],
            ],
//            'application' => [
//                'type'    => Segment::class,
//                'options' => [
//                    'route'    => '/application[/:action]',
//                    'defaults' => [
//                        'controller' => Controller\IndexController::class,
//                        'action'     => 'index',
//                    ],
//                ],
//            ],
        ],
    ],
    'controllers' => [
        'factories' => [
            Controller\IndexController::class => InvokableFactory::class,
            Controller\ScheduleController::class => ScheduleControllerFactory::class,
            Controller\LoginController::class => LoginControllerFactory::class,
            Controller\PdfController::class => InvokableFactory::class,
            Controller\EmailController::class => EmailControllerFactory::class,
        ],
    ],
    'session_containers' => [
        'SchedulerContainer'
    ],
    'view_manager' => [
        'display_not_found_reason' => true,
        'display_exceptions'       => true,
        'strategies'               => ['ViewJsonStrategy'],
        'doctype'                  => 'HTML5',
        'not_found_template'       => 'error/404',
        'exception_template'       => 'error/index',
        'template_map' => [
            'layout/layout'           => __DIR__ . '/../view/layout/layout.phtml',
            'application/index/index' => __DIR__ . '/../view/application/index/index.phtml',
            'error/404'               => __DIR__ . '/../view/error/404.phtml',
            'error/index'             => __DIR__ . '/../view/error/index.phtml',
        ],
        'template_path_stack' => [
            __DIR__ . '/../view',
        ],
    ],

];
