<?php

namespace User\Form;

use Base\Form\FormTestCase;

/**
 * @group User
 */
class RegisterTest extends FormTestCase
{
    
    public function setUp()
    {
        $this->class = '\\User\\Form\\Register';
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
            'name' => 'Teste',
            'email' => 'test@teste.com',
            'id' => 1,
            'password' => '123456789',
            'password_confirm' => '123456789',
            'phone' => '4133333333',
            'cpf' => '000.000.000-00',
            'address' => 'Rua teste',
            'address_number' => 1234,
            'address_complement' => 'teste',
            'district' => 'teste',
            'city' => 'teste',
            'state' => 'PR',
            'cep' => '82.899-899'
        );
    }
    
    public function formFields() 
    {
        return array(
            array('id'),
            array('name'),
            array('email'),
            array('password'),
            array('password_confirm'),
            array('phone'),
            array('cpf'),
            array('rg'),
            array('address'),
            array('address_number'),
            array('address_complement'),
            array('district'),
            array('city'),
            array('state'),
            array('cep'),
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
    
    public function testIfUnmatchedPassowrdAreReturningFalse()
    { 
        $data = $this->getData();
        $data['password_confirm'] = 'teste';
        
        $this->form->setData($data);
        $this->assertFalse($this->form->isValid());
    }
    
    public function testIfIsRejectingNotRangeLengthPassword()
    {
        $data = $this->getData();
        $data['password'] = '123';
        $data['password_confirm'] = '123';
        
        $this->form->setData( $data );
        $this->assertFalse($this->form->isValid());
    }
    
    public function testIfInvalidDataIsReturningMessages()
    {
        $data = $this->getData();
        $data['email'] = 'teste';
        
        $this->form->setData($data);
        $this->assertFalse($this->form->isValid());
        $this->assertTrue(count($this->form->getMessages()) > 0);
    }
    
    public function testIfIsRequiringPhoneNumber()
    {
        $data = $this->getData();
        $data['phone'] = '';
        
        $this->form->setData($data);
        $this->assertFalse($this->form->isValid());
    }
    
    public function testIfIsRequiringCpf()
    {
        $data = $this->getData();
        $data['cpf'] = '';
        
        $this->form->setData($data);
        $this->assertFalse($this->form->isValid());
    }
    
    public function testIfIsRequiringAddress()
    {
        $data = $this->getData();
        $data['address'] = '';
        
        $this->form->setData($data);
        $this->assertFalse($this->form->isValid());
    }
    
    public function testIfIsRequiringAddressNumber()
    {
        $data = $this->getData();
        $data['address_number'] = '';
        
        $this->form->setData($data);
        $this->assertFalse($this->form->isValid());
    }
    
    public function testIfIsRequiringDistrict()
    {
        $data = $this->getData();
        $data['district'] = '';
        
        $this->form->setData($data);
        $this->assertFalse($this->form->isValid());
    }
    
    public function testIfIsRequiringCity()
    {
        $data = $this->getData();
        $data['city'] = '';
        
        $this->form->setData($data);
        $this->assertFalse($this->form->isValid());
    }
    
    public function testIfIsRequiringState()
    {
        $data = $this->getData();
        $data['state'] = '';
        
        $this->form->setData($data);
        $this->assertFalse($this->form->isValid());
    }
    
    public function testIfIsRequiringCep()
    {
        $data = $this->getData();
        $data['cep'] = '';
        
        $this->form->setData($data);
        $this->assertFalse($this->form->isValid());
    }
    
}
