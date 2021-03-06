<?php

namespace User;

return array(
    'router' => array(
        'routes' => array(
            'home' => array(
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => array(
                    'route' => '/meu-perfil',
                    'defaults' => array(
                        'controller' => 'profile',
                        'action' => 'index'
                    ),
                ),
            ),
            'cadastre-se' => array(
                'type' => 'Literal',
                'options' => array(
                    'route' => '/cadastre-se',
                    'defaults' => array(
                        'controller' => 'register',
                        'action' => 'index'
                    )
                )
            ),
            'meu-perfil' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/meu-perfil/[:id]',
                    'constraints' => array(
                        'id' => '[0-9]+'
                    ),
                    'defaults' => array(
                        'controller' => 'profile',
                        'action' => 'index'
                    )
                )
            ),
            'login' => array(
                'type' => 'Literal',
                'options' => array(
                    'route' => '/login',
                    'defaults' => array(
                        'controller' => 'auth',
                        'action' => 'index'
                    )
                )
            ),
            'logout' => array(
                'type' => 'Literal',
                'options' => array(
                    'route' => '/logout',
                    'defaults' => array(
                        'controller' => 'auth',
                        'action' => 'logout'
                    )
                ),
            )
        ),
    ),
    'controllers' => array(
        'invokables' => array(
            'register' => 'User\Controller\RegisterController',
            'profile' => 'User\Controller\ProfileController',
            'auth' => 'User\Controller\AuthController',
        ),
    ),
    'service_manager' => array(
        'factories' => array(
            'translator' => 'Zend\I18n\Translator\TranslatorServiceFactory',
        ),
    ),
    'translator' => array(
        'locale' => 'en_US',
        'translation_patterns' => array(
            array(
                'type' => 'gettext',
                'base_dir' => __DIR__ . '/../language',
                'pattern' => '%s.mo',
            ),
        ),
    ),
    'view_manager' => array(
        'display_not_found_reason' => true,
        'display_exceptions'       => true,
        'doctype'                  => 'HTML5',
        'not_found_template'       => 'error/404',
        'exception_template'       => 'error/index',
        'template_map' => array(
            'user/index/index'    => __DIR__ . '/../view/user/index/index.phtml',
        ),
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
        'strategies' => array(
            'ViewJsonStrategy',
        )
    ),
    'doctrine' => array(
        'driver' => array(
            __NAMESPACE__ . '_driver' => array(
                'class' => 'Doctrine\ORM\Mapping\Driver\AnnotationDriver',
                'cache' => 'array',
                'paths' => array(__DIR__ . '/../src/' . __NAMESPACE__ . '/Entity')
            ),
            'orm_default' => array(
                'drivers' => array(
                    __NAMESPACE__ . '\Entity' => __NAMESPACE__ . '_driver'
                )
            )
        ),
    ),
);