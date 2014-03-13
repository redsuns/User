<?php

namespace UserAdmin\Form;

use Zend\Di\ServiceLocator;
use Zend\Form\Form;

class EditUser extends Form
{
    protected $serviceLocator;

    public function __construct()
    {
        parent::__construct('user_edit');
        $this->setAttribute('method', 'post');
        $this->setInputFilter(new EditUserFilter());

        $this->add(array(
            'name' => 'id'
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
            'name' => 'email',
            'options' => array(
                'type' => 'email',
                'label' => 'E-mail'
            ),
            'attributes' => array(
                'placeholder' => '',
                'class' => 'span4'
            )
        ));

        $this->add(array(
            'type' => 'Zend\Form\Element\Password',
            'name' => 'password',
            'options' => array(
                'type' => 'password',
                'label' => 'Senha'
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
                'label' => 'Gravar alterações'
            ),
            'attributes' => array(
                'class' => 'btn btn-primary'
            ),
        ));

        $this->add(array(
            'type' => 'Zend\Form\Element\Select',
            'name' => 'role_id',
            'options' => array(
                'type' => 'select',
                'label' => 'Função'
            ),
            'attributes' => array(
                'class' => 'span4'
            )
        ));

    }


    public function setServiceLocator($serviceLocator)
    {
        $this->serviceLocator = $serviceLocator;
    }


    public function getServiceLocator()
    {
        return $this->serviceLocator;
    }


    public function isValid()
    {
        $userService = $this->getServiceLocator()->get('UserAdmin\Service\User');

        $isValid = parent::isValid();

        $email = $this->get('email')->getValue();
        $id = $this->get('id')->getValue();

        if(!$userService->checkEmailUser($email, $id)) {
            $messagesCadastro = array(
                'emailCadastrado' => 'E-mail já cadastrado com outro usuário',
            );

            $this->get('email')->setMessages($messagesCadastro);
            $isValid = false;
        }

        return $isValid;
    }
} 