<?php

namespace User;

use User\Service\User as ServiceUser;

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
                        
                // Forms
                'User\Form\Register' => function( $sm ) {
                    return new \User\Form\Register();
                },
            ),
        );
    }

    public function getViewHelperConfig()
    {
        return array();
    }

}
