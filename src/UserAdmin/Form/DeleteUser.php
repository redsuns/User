<?php

namespace UserAdmin\Form;

use Zend\Form\Form;

class DeleteUser extends Form
{
    public function __construct()
    {
        parent::__construct('user_delete');
        $this->setAttribute('method', 'post');

        $this->add(array(
            'type' => 'Zend\Form\Element\Hidden',
            'name' => 'id',
            'options' => array(
                'type' => 'hidden',
                'label' => 'Remover'
            )
        ));

        $this->add(array(
            'type' => 'Zend\Form\Element\Submit',
            'name' => 'submit',
            'options' => array(
                'type' => 'submit',
                'label' => 'Remover'
            ),
            'attributes' => array(
                'class' => 'delete'
            )
        ));

    }
    public function setServiceLocator($serviceLocator)
    {
        $this->serviceLocator = $serviceLocator;
    }

} 