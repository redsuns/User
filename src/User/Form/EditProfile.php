<?php

namespace User\Form;

use Zend\Form\Form;

class EditProfile extends Form
{
    
    public function __construct() 
    {
        parent::__construct('my-profile');
        
        $this->setAttribute('method', 'post');
        $this->setInputFilter(new EditProfileFilter());
        
        
        $this->add(array(
            'type' => 'Zend\Form\Element\Hidden',
            'name' => 'id',
        ));

        $this->add(array(
            'name' => 'name',
            'required' => true,
            'options' => array(
                'type' => 'text',
                'label' => 'Nome Completo'
            ),
            'attributes' => array(
                'id' => 'nome',
                'placeholder' => 'Informe seu nome completo',
                'class' => 'form-control'
            )
        ));

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
                'label' => 'Redefinição de senha'
            ),
            'attributes' => array(
                'class' => 'form-control',
                'placeholder' => 'Se desejar, crie uma nova senha',
                'id' => 'password'
            )
                )
        );

        $this->add(array(
            'name' => 'phone',
            'required' => true,
            'options' => array(
                'type' => 'text',
                'label' => 'Telefone'
            ),
            'attributes' => array(
                'id' => 'telefone',
                'class' => 'telefone form-control',
                'placeholder' => 'Seu telefone principal'
            ),
        ));

        $this->add(array(
            'name' => 'cpf',
            'required' => true,
            'options' => array(
                'type' => 'text',
                'label' => 'CPF'
            ),
            'attributes' => array(
                'id' => 'cpf',
                'class' => 'cpf form-control',
                'placeholder' => 'Informe seu CPF'
            ),
        ));
        
        $this->add(array(
            'name' => 'address',
            'required' => true,
            'options' => array(
                'type' => 'text',
                'label' => 'Endereço'
            ),
            'attributes' => array(
                'id' => 'address',
                'class' => 'address form-control',
                'placeholder' => 'Rua Nome da Rua'
            ),
        ));
        
        $this->add(array(
            'name' => 'address_number',
            'required' => true,
            'options' => array(
                'type' => 'number',
                'label' => 'Número'
            ),
            'attributes' => array(
                'id' => 'address_number',
                'class' => 'form-control',
                'placeholder' => 'Informe o número'
            ),
        ));
        
        $this->add(array(
            'name' => 'address_complement',
            'options' => array(
                'type' => 'text',
                'label' => 'Complemento'
            ),
            'attributes' => array(
                'id' => 'address_complement',
                'class' => 'form-control',
                'placeholder' => 'Casa, Apartamento ... (opcional)'
            ),
        ));
        
        $this->add(array(
            'name' => 'district',
            'required' => true,
            'options' => array(
                'type' => 'text',
                'label' => 'Bairro'
            ),
            'attributes' => array(
                'id' => 'district',
                'class' => 'form-control',
                'placeholder' => 'Informe o bairro'
            ),
        ));
        
        $this->add(array(
            'name' => 'city',
            'required' => true,
            'options' => array(
                'type' => 'text',
                'label' => 'Cidade'
            ),
            'attributes' => array(
                'id' => 'city',
                'class' => 'form-control',
                'placeholder' => 'Informe a cidade'
            ),
        ));
        
        $states = new \Zend\Form\Element\Select('state');
        $states->setLabel('Estado');
        $statesList = array('-' => '-- Selecione --') + \Local\Data\BrazilianState::getList();
        $states->setValueOptions($statesList)->setAttribute('class', 'form-control')->setAttribute('id', 'state');

        $this->add($states);
        
        $this->add(array(
            'name' => 'cep',
            'required' => true,
            'options' => array(
                'type' => 'select',
                'label' => 'CEP'
            ),
            'attributes' => array(
                'id' => 'cep',
                'class' => 'cep form-control',
                'placeholder' => 'Informe seu CEP'
            ),
        ));

        $this->add(array(
            'type' => 'Zend\Form\Element\Submit',
            'name' => 'submit',
            'options' => array(
                'type' => 'submit',
            ),
            'attributes' => array(
                'value' => 'Editar',
                'class' => 'btn btn-enviar-mensagem',
            )
        ));
        
    }
    
}
