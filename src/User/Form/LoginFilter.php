<?php

namespace User\Form;

use Zend\InputFilter\InputFilter;
use Zend\Form\Element;
use Zend\InputFilter\InputProviderInterface;
use Zend\Validator\ValidatorInterface;

class LoginFilter extends InputFilter
{

    public function __construct()
    {

        $this->add(
                array(
                    'name' => 'email',
                    'required' => true,
                    'filters' => array(
                        array('name' => 'StringTrim'),
                    ),
                    'validators' => array(
                        array(
                            'name' => 'NotEmpty',
                            'options' => array(
                                'messages' => array('isEmpty' => 'E-mail não pode estar vazio.'),
                            ),
                        ),
                        array(
                            'name' => 'EmailAddress',
                            'options' => array(
                                'messages' => array(
                                    'emailAddressInvalidFormat' => 'O email fornecido parece ser inválido'
                                )
                            )
                        ),
                    )
                )
        );

        $this->add(array(
            'name' => 'password',
            'required' => true,
            'filters' => array(
                array('name' => 'StripTags'),
                array('name' => 'StringTrim'),
            ),
            'validators' => array(
                array(
                    'name' => 'NotEmpty',
                    'options' => array(
                        'messages' => array('isEmpty' => 'Por favor defina sua senha.'),
                    ),
                ),
            )
        ));

    }

}
