<?php

namespace User\Entity;

use Base\Entity\EntityTestCase;

/**
* @group User
*/
class UserDetailTest extends EntityTestCase
{
    
    public function setUp()
    {
        $this->entity = __NAMESPACE__ . '\UserDetail';
        parent::setUp();
    }
    
    public function tearDown()
    {
        unset($this->entity);
        parent::tearDown();
    }
    
    public function data()
    {
        return [
            'id' => 1,
            'field' => 'telefone',
            'value' => 'Novo teste',
            'label' => 'Teste',
            'user' => new \User\Entity\User(['id' => 1])
        ];
    }
    
    public function testClassExists()
    {
        $classExists = (true == class_exists($this->entity));
        $this->assertTrue($classExists);
    }
    
    public function testMethodToArrayExists()
    {
        $methodExists = (true === method_exists(new $this->entity(), 'toArray'));
        $this->assertTrue($methodExists);
    }
    
    public function testMethodToStringExists()
    {
        $methodExists = (true === method_exists(new $this->entity(), '__toString'));
        $this->assertTrue($methodExists);
    }
    
    public function testIfIsSettingDataAsExpected()
    {
        $entity = new $this->entity( $this->data() );
        
        $this->assertNotNull($entity);
        $this->assertInstanceOf($this->entity, $entity);
    }
    
    public function testIfIsReturningAsArray()
    {
        $entity = new $this->entity( $this->data() );
        
        $this->assertNotNull($entity);
        $this->assertInternalType('array', $entity->toArray());
    }
    
    public function testIfIsReturningAsString()
    {
        $entity = new $this->entity( $this->data() );
        
        $this->assertNotNull($entity);
        $this->assertInternalType('string', sprintf($entity));
    }
}