<?php

namespace User;

use Zend\ModuleManager\ModuleManager,
    Zend\Authentication\AuthenticationService,
    Zend\Session\Storage\SessionStorage;


class Module
{

    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ . 'Admin' => __DIR__ . '/src/' . __NAMESPACE__ . 'Admin',
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }

    public function getServiceConfig()
    {
        return array(
            'factories' => array(
                // Serviços
                'User\Service\User' => function($sm) {
                    return new \User\Service\User($sm->get('Doctrine\ORM\EntityManager'));
                },
                'User\Service\UserDetail' => function($sm) {
                    return new \User\Service\UserDetail($sm->get('Doctrine\ORM\EntityManager'));
                },
                        
                //Adapters 
                'User\Auth\Adapter' => function($sm) {
                    return new \User\Auth\Adapter($sm->get('Doctrine\ORM\EntityManager'));
                },
                        
                // Forms
                'User\Form\Register' => function( $sm ) {
                    return new \User\Form\Register();
                },
                        
                //Session
                'SessionStorage' => function($sm) {
                    return new \Zend\Authentication\Storage\Session('User');
                }
            ),        
        );
    }

    public function getViewHelperConfig()
    {
        return array();
    }
    
}
