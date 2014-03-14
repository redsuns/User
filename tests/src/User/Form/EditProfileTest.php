<?php

namespace User\Form;

use Base\Form\FormTestCase;
use User\Asset\UserAsset;

/**
 * @group User
 */
class EditProfileTest extends FormTestCase
{
 
    protected $asset;
    
    public function setUp()
    {
        $this->class = '\\User\\Form\\EditProfile';
        $this->form  = new $this->class();
        
        parent::setUp();
        
        $this->asset = new UserAsset();
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
    
    public function formFields() 
    {
        return array(
            array('id'),
            array('name'),
            array('email'),
            array('password'),
            array('phone'),
            array('cpf'),
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
        $this->form->setData($this->asset->getData());
        $this->assertTrue($this->form->isValid());
    }
    
    public function testIfCompleteDataAreInvalid()
    { 
        $data = $this->asset->getData();
        $data['email'] = 'teste';
        
        $this->form->setData($data);
        $this->assertFalse($this->form->isValid());
    }
    
    public function testIfIsRejectingNotRangeLengthPassword()
    {
        $data = $this->asset->getData();
        $data['password'] = '123';
        
        $this->form->setData( $data );
        $this->assertFalse($this->form->isValid());
    }
    
    public function testIfIsAcceptingEmptyPasswordAsExpectedOnEdit()
    {
        $data = $this->asset->getData();
        $data['password'] = '';
        
        $this->form->setData( $data );
        $this->assertTrue($this->form->isValid());
    }
    
    public function testIfInvalidDataIsReturningMessages()
    {
        $data = $this->asset->getData();
        $data['email'] = 'teste';
        
        $this->form->setData($data);
        $this->assertFalse($this->form->isValid());
        $this->assertTrue(count($this->form->getMessages()) > 0);
    }
    
    public function testIfIsRequiringPhoneNumber()
    {
        $data = $this->asset->getData();
        $data['phone'] = '';
        
        $this->form->setData($data);
        $this->assertFalse($this->form->isValid());
    }
    
    public function testIfIsRequiringCpf()
    {
        $data = $this->asset->getData();
        $data['cpf'] = '';
        
        $this->form->setData($data);
        $this->assertFalse($this->form->isValid());
    }
    
    public function testIfIsRequiringAddress()
    {
        $data = $this->asset->getData();
        $data['address'] = '';
        
        $this->form->setData($data);
        $this->assertFalse($this->form->isValid());
    }
    
    public function testIfIsRequiringAddressNumber()
    {
        $data = $this->asset->getData();
        $data['address_number'] = '';
        
        $this->form->setData($data);
        $this->assertFalse($this->form->isValid());
    }
    
    public function testIfIsRequiringDistrict()
    {
        $data = $this->asset->getData();
        $data['district'] = '';
        
        $this->form->setData($data);
        $this->assertFalse($this->form->isValid());
    }
    
    public function testIfIsRequiringCity()
    {
        $data = $this->asset->getData();
        $data['city'] = '';
        
        $this->form->setData($data);
        $this->assertFalse($this->form->isValid());
    }
    
    public function testIfIsRequiringState()
    {
        $data = $this->asset->getData();
        $data['state'] = '';
        
        $this->form->setData($data);
        $this->assertFalse($this->form->isValid());
    }
    
    public function testIfIsRequiringCep()
    {
        $data = $this->asset->getData();
        $data['cep'] = '';
        
        $this->form->setData($data);
        $this->assertFalse($this->form->isValid());
    }
    
}
