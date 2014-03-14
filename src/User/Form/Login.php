<?php

namespace User\Form;

use Zend\Form\Form;

class Login extends Form
{

    protected $validator;

    public function __construct()
    {
        parent::__construct('register');

        $this->setAttribute('method', 'post');
        $this->setAttribute('role', 'form');
        $this->setInputFilter(new LoginFilter());

        
        $this->add(array(
            'name' => 'email',
            'required' => true,
            'options' => array(
                'type' => 'text',
                'label' => 'Seu email'
            ),
            'attributes' => array(
                'id' => 'email',
                'placeholder' => 'Informe seu email',
                'class' => 'form-control'
            )
        ));


        $this->add(array(
            'type' => 'Zend\Form\Element\Password',
            'name' => 'password',
            'required' => true,
            'options' => array(
                'type' => 'password',
                'label' => 'Defina sua senha'
            ),
            'attributes' => array(
                'class' => 'form-control',
                'placeholder' => 'Crie sua senha',
                'id' => 'password'
            )
                )
        );

        $this->add(array(
            'type' => 'Zend\Form\Element\Submit',
            'name' => 'submit',
            'options' => array(
                'type' => 'submit',
            ),
            'attributes' => array(
                'value' => 'Realizar login',
                'class' => 'btn btn-enviar-mensagem'
            )
        ));
    }
    
}
