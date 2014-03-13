<?php

namespace User\Entity;

use Base\Entity\EntityTestCase;

/**
* @group User
*/
class UserTest extends EntityTestCase
{
    
    public function setUp()
    {
        $this->entity = __NAMESPACE__ . '\User';
        parent::setUp();
    }
    
    public function tearDown()
    {
        unset($this->entity);
        parent::tearDown();
    }
    
    public function data()
    {
        return array(
            'id' => 1,
            'name' => 'Teste',
            'email' => 'teste@teste.com',
            'password' => '123',
            'detail' => [
                new \User\Entity\UserDetail(array('id' => 1, ''))
            ]
        );
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