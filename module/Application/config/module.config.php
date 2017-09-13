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


use Application\Controller\Factories\ScheduleControllerFactory;
use Application\Controller\Factories\EmailControllerFactory;
use Application\Controller\Factories\LoginControllerFactory;
use Application\Controller\Factories\SignupControllerFactory;
use Application\Service\AuthManager;
use Application\Service\Factories\AuthManagerFactory;
use Application\Service\Factories\AuthenticationServiceFactory;
use Application\Service\AuthAdapter;
use Application\Service\Factories\AuthAdapterFactory;

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
                    'route'    => '/schedule/draft',
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
            'previewSchedule' => [
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
                    'route'    => '/login',
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
                        'controller' => Controller\SignupController::class,
                        'action'     => 'signup',
                    ],
                ],
            ],
            'saveSignup' => [
                'type' => Literal::class,
                'options' => [
                    'route'    => '/thanks',
                    'defaults' => [
                        'controller' => Controller\SignupController::class,
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
            Controller\SignupController::class => SignupControllerFactory::class,
        ],
    ],
    'session_containers' => [
        'SchedulerContainer'
    ],
    'service_manager' => [
        'factories' => [
            \Zend\Authentication\AuthenticationService::class => AuthenticationServiceFactory::class,
            AuthManager::class => AuthManagerFactory::class,
            AuthAdapter::class => AuthAdapterFactory::class,
        ],
    ],
    'access_filter' => [
        'options' => [
            // The access filter can work in 'restrictive' (recommended) or 'permissive'
            // mode. In restrictive mode all controller actions must be explicitly listed
            // under the 'access_filter' config key, and access is denied to any not listed
            // action for not logged in users. In permissive mode, if an action is not listed
            // under the 'access_filter' key, access to it is permitted to anyone (even for
            // not logged in users. Restrictive mode is more secure and recommended to use.
            'mode' => 'restrictive'
        ],
        'controllers' => [
            Controller\ScheduleController::class => [
                // Allow anyone to visit "index" and "about" actions
                //['actions' => ['index', 'about'], 'allow' => '*'],
                // Allow authenticated users to visit "settings" action
                ['actions' => ['create', 'draft', 'save', 'dates', 'preview'], 'allow' => '@']
            ],
            Controller\EmailController::class => [
                ['actions' => ['draft', 'email', 'confirm'], 'allow' => '@']
            ],
            Controller\PdfController::class => [
                ['actions' => ['download'], 'allow' => '@']
            ],
            Controller\SignupController::class => [
                ['actions' => ['signup', 'saveSignup'], 'allow' => '*']
            ],
        ]
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
