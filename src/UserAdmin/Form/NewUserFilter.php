<?php

namespace UserAdmin\Form;

use Zend\InputFilter\InputFilter;


class NewUserFilter extends InputFilter
{
    public function __construct()
    {

        $this->add(array(
            'name' => 'role_id',
            'required' => true,
        ));
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
                        'messages' => array('isEmpty' => 'Informe o nome.')
                    )
                ),
            )
        ));

        $this->add(array(
            'name' => 'email',
            'required' => true,
            'filters' => array(
                array('name' => 'StripTags'),
                array('name' => 'StringTrim'),
            ),
            'validators' => array(
                array(
                    'name' => 'notEmpty',
                    'options' => array(
                        'messages' => array('isEmpty' => 'Por favor informe o e-mail'),
                    )
                ),
                array(
                    'name' => 'EmailAddress',
                )
            )
        ));
    }
}
