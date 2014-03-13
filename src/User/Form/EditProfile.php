<?php

namespace User\Form;

use Zend\Form\Form;

class EditProfile extends Form
{
    
    public function __construct() 
    {
        parent::__construct('edit-profile');
        
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
                'placeholder' => 'Informe um email',
            )
        ));


        $this->add(array(
            'type' => 'Zend\Form\Element\Password',
            'name' => 'password',
            'required' => false,
            'options' => array(
                'type' => 'password',
                'label' => 'Defina sua senha'
            ),
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
                'class' => 'telefone'
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
                'class' => 'cpf'
            ),
        ));

        $this->add(array(
            'name' => 'rg',
            'options' => array(
                'type' => 'text',
                'label' => 'RG'
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
                'class' => 'address'
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
                'class' => 'address_number'
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
                'class' => 'address_complement'
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
                'class' => 'district'
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
                'class' => 'city'
            ),
        ));
        
        $states = new \Zend\Form\Element\Select('state');
        $states->setLabel('Estado');
        $statesList = array('-' => '-- Selecione --') + \Local\Data\BrazilianState::getList();
        $states->setValueOptions($statesList);

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
                'class' => 'cep'
            ),
        ));

        $this->add(array(
            'type' => 'Zend\Form\Element\Submit',
            'name' => 'submit',
            'options' => array(
                'type' => 'submit',
            ),
            'attributes' => array(
                'value' => 'Alterar dados',
            )
        ));
        
    }
    
}
