<?php

namespace UserAdmin\Form;

use Zend\InputFilter\InputFilter;

class NewRoleFilter extends InputFilter 
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
                        'messages' => array('isEmpty' => 'Informe o nome da função.')
                    )
                ),
            )
        ));
        
        $this->add(array(
            'name' => 'description',
            'required' => true,
            'filters' => array(
                array('name' => 'StripTags'),
                array('name' => 'StringTrim'),
             ),
        ));
        
    }
}
