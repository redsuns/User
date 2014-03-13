<?php

namespace UserAdmin\Form;

use Zend\Di\ServiceLocator;
use Zend\Form\Form;

class NewUser extends Form
{
    protected $serviceLocator;

    public function __construct()
    {
        parent::__construct('user_new');
        $this->setAttribute('method', 'post');
        $this->setInputFilter(new NewUserFilter());

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
            'type' => 'Zend\Form\Element\Password',
            'name' => 'password_confirm',
            'options' => array(
                'type' => 'password',
                'label' => 'Confirme a senha'
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
                'label' => 'Gravar'
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

        if(!$userService->checkEmailUser($email)) {
            $messagesCadastro = array(
                'emailCadastrado' => 'E-mail já cadastrado com outro usuário',
            );

            $this->get('email')->setMessages($messagesCadastro);
            $isValid = false;
        }


        $password     = $this->get('password')->getValue();
        $confirmPassword     = $this->get('password_confirm')->getValue();
        if($password !== $confirmPassword) {
            $confirmacaoSenha = array(
                'confirmacaoSenha' => 'A confirmação da senha não confere com a senha informada!',
            );
            $this->get('password_confirm')->setMessages($confirmacaoSenha);
            $isValid = false;
        }

        return $isValid;
    }
} 