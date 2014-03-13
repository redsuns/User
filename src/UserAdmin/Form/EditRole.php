<?php

namespace UserAdmin\Form;

use Zend\Form\Form;

class EditRole extends Form
{
    
    public function __construct()
    {
        parent::__construct('role_edit');
        $this->setAttribute('method', 'post');
        $this->setInputFilter(new NewRoleFilter());

        $this->add(array(
            'type' => 'Zend\Form\Element\Hidden',
            'name' => 'id',
            'options' => array(
                'type' => 'hidden',
                'label' => 'Id'
            )
        ));

        $this->add(array(
            'name' => 'name',
            'options' => array(
                'type' => 'text',
                'label' => 'Nome'
            ),
            'attributes' => array(
                'placeholder' => '',
                'class' => 'span4',
            )
        ));
        
        $this->add(array(
            'type' => 'Zend\Form\Element\Textarea',
            'name' => 'description',
            'options' => array(
                'type' => 'textarea',
                'label' => 'Descrição'
            ),
            'attributes' => array(
                'placeholder' => '',
                'class' => 'span4',
            )
        ));
        
        $this->add(array(
           'type' => 'Zend\Form\Element\Submit',
            'name' => 'submit',
            'options' => array(
                'type' => 'submit',
                'label' => 'Gravar Alterações'
            ),
            'attributes' => array(
                'class' => 'btn btn-primary'
            ),
        ));
    }
    
}
