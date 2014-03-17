<?php

namespace User\Form;

use Base\Form\FormTestCase;

/**
 * @group User
 */
class LoginTest extends FormTestCase
{
    
    public function setUp()
    {
        $this->class = '\User\Form\Login';
        $this->form  = new $this->class();
        
        parent::setUp();
    }

    public function testExistsClass()
    {
        $class = class_exists($this->class);
        $this->assertTrue($class);
    }
    
    public function testIfClassIsASubClassOfZendForm()
    {
        $class = class_parents($this->form);
        $formExtendsOf = current($class);
        $this->assertEquals('Zend\Form\Form', $formExtendsOf);
    }
    
    public function getData()
    {
        return array(
            'email' => 'test@teste.com',
            'password' => '123456789',
        );
    }
    
    public function formFields() 
    {
        return array(
            array('email'),
            array('password'),
            array('submit'),
        );
    }
    
    /**
     * 
     * @dataProvider formFields()
     */
    public function testFormFields($fieldName) 
    {
        $this->assertTrue($this->form->has($fieldName), 'Field "'.$fieldName.'" not found.');           
    }
    
    public function getFormAttributes()
    {
        $dataProviderTest = $this->formFields();
        $definedAttributes = array();
        foreach($dataProviderTest as $item) {
            $definedAttributes[] = $item[0];
        }
        
        return $definedAttributes;
    }
    
    /**
     * Test if the attributes are in the form and config in tests 
     */
    public function testAttributsAreMirrored()
    {
        $definedAttributes    = $this->getFormAttributes();
        $attributesFormClass = $this->form->getElements();
        $attributesForm = array();
        foreach($attributesFormClass as $key => $value) {
            $attributesForm[] = $key;
            $messageAssert = 'Attribute "'.$key.'" not found in class test. Value - '.$value->getName();
            $this->assertContains($key, $definedAttributes, $messageAssert);
        }
        
        $this->assertTrue(($definedAttributes === $attributesForm), 'Attributes not equals.');        
    }
    
    public function testIfCompleteDataAreValid()
    { 
        $this->form->setData($this->getData());
        $this->assertTrue($this->form->isValid());
    }
    
    public function testIfCompleteDataAreInvalid()
    { 
        $data = $this->getData();
        $data['email'] = 'teste';
        
        $this->form->setData($data);
        $this->assertFalse($this->form->isValid());
    }
    
}
