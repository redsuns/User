<?php

namespace User\Form;

use Zend\InputFilter\InputFilter;
use Zend\Form\Element;
use Zend\InputFilter\InputProviderInterface;
use Zend\Validator\ValidatorInterface;
use Zend\Filter\PregReplace;

class EditProfileFilter extends InputFilter
{

    public function __construct()
    {

        $this->add(array(
            'name' => 'name',
            'required' => true,
            'filters' => array(
                array('name' => 'StripTags'),
                array('name' => 'StringTrim'),
            ),
            'validators' => array(
                array(
                    'name' => 'NotEmpty',
                    'options' => array(
                        'messages' => array('isEmpty' => 'Nome não pode estar em branco.')
                    )
                )
            )
        ));

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
            'required' => false,
            'filters' => array(
                array('name' => 'StripTags'),
                array('name' => 'StringTrim'),
            ),
            'validators' => array(
                array(
                    'name' => 'StringLength',
                    'options' => array('encoding' => 'UTF-8', 'min' => 8, 'max' => 16,
                        'messages' => array(
                            'stringLengthTooShort' => 'O campo senha deve ter no mínimo 8 caracteres e no máximo 16 caracteres',
                            'stringLengthTooLong' => 'O campo senha deve ter no mínimo 8 caracteres e no máximo 16 caracteres'),)
                ),
            )
        ));

        $this->add(array(
            'name' => 'phone',
            'required' => true,
            'filters' => array(
                array('name' => 'StripTags'),
                array('name' => 'StringTrim'),
                array('name' => 'PregReplace',
                    'options' => array(
                        'pattern' => '/[^0-9]/',
                        'replacement' => ''
                    ),
                ),
            ),
            'validators' => array(
                array(
                    'name' => 'NotEmpty',
                    'options' => array(
                        'messages' => array('isEmpty' => 'Por favor informe seu Telefone')
                    )
                ),
            ),
        ));

        $this->add(array(
            'name' => 'cpf',
            'required' => true,
            'filters' => array(
                array('name' => 'StripTags'),
                array('name' => 'StringTrim'),
                array('name' => 'PregReplace',
                    'options' => array(
                        'pattern' => '/[^0-9]/',
                        'replacement' => ''
                    ),
                ),
            ),
            'validators' => array(
                array(
                    'name' => 'notEmpty',
                    'options' => array(
                        'messages' => array('isEmpty' => 'Por favor informe seu CPF')
                    )
                )
            ),
        ));

        $this->add(array(
            'name' => 'address',
            'required' => true,
            'filters' => array(
                array('name' => 'StripTags'),
                array('name' => 'StringTrim'),
            ),
            'validators' => array(
                array(
                    'name' => 'notEmpty',
                    'options' => array(
                        'messages' => array('isEmpty' => 'Por favor informe seu endereço')
                    )
                )
            ),
        ));

        $this->add(array(
            'name' => 'address_number',
            'required' => true,
            'filters' => array(
                array('name' => 'StripTags'),
                array('name' => 'StringTrim'),
            ),
            'validators' => array(
                array(
                    'name' => 'notEmpty',
                    'options' => array(
                        'messages' => array('isEmpty' => 'Por favor informe o número')
                    )
                )
            ),
        ));

        $this->add(array(
            'name' => 'district',
            'required' => true,
            'filters' => array(
                array('name' => 'StripTags'),
                array('name' => 'StringTrim'),
            ),
            'validators' => array(
                array(
                    'name' => 'notEmpty',
                    'options' => array(
                        'messages' => array('isEmpty' => 'Por favor informe o bairro')
                    )
                )
            ),
        ));

        $this->add(array(
            'name' => 'city',
            'required' => true,
            'filters' => array(
                array('name' => 'StripTags'),
                array('name' => 'StringTrim'),
            ),
            'validators' => array(
                array(
                    'name' => 'notEmpty',
                    'options' => array(
                        'messages' => array('isEmpty' => 'Por favor informe a cidade')
                    )
                )
            ),
        ));

        $this->add(array(
            'name' => 'state',
            'required' => true,
            'filters' => array(
                array('name' => 'StripTags'),
                array('name' => 'StringTrim')
            ),
            'validators' => array(
                array(
                    'name' => 'NotEmpty',
                    'options' => array(
                        'messages' => array('isEmpty' => 'Por favor selecione o estado')
                    )
                ),
                array(
                    'name' => 'StringLength',
                    'options' => array('encoding' => 'UTF-8', 'min' => 2, 'max' => 2,
                        'messages' => array(
                            'stringLengthTooShort' => 'Por favor selecione o estado',
                            'stringLengthTooLong' => 'Por favor selecione o estado'),)
                ),
            ),
        ));

        $this->add(array(
            'name' => 'cep',
            'required' => true,
            'filters' => array(
                array('name' => 'StripTags'),
                array('name' => 'StringTrim'),
                array('name' => 'PregReplace',
                    'options' => array(
                        'pattern' => '/[^0-9]/',
                        'replacement' => ''
                    ),
                ),
            ),
            'validators' => array(
                array(
                    'name' => 'notEmpty',
                    'options' => array(
                        'messages' => array('isEmpty' => 'Por favor informe seu CEP')
                    )
                )
            ),
        ));
    }

}
