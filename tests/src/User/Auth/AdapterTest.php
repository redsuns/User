<?php

namespace User\Adapter;

use PHPUnit_Framework_TestCase as PHPUnit;
use Mockery;
use User\Asset\UserAsset;
use User\Bootstrap;

/**
 * @group User
 */
class AdapterTest extends PHPUnit
{
    protected $adapterName;
    protected $em;
    protected $adapter;
    protected $asset;
    
    public function setUp()
    {
        $this->asset = new UserAsset();
        $this->adapterName = 'User\Auth\Adapter';
        
        
        parent::setUp();
    }
    
    public function tearDown()
    {
        unset($this->adapterName, $this->asset);
        parent::tearDown();
    }
    
    public function emMock()
    {
        $user = new \User\Entity\User($this->asset->getData());
        
        $em = Mockery::mock('Doctrine\ORM\EntityManager');
        $em->shouldReceive('getRepository')->andReturn($em);
        $em->shouldReceive('findOneBy')->andReturn($user);
        
        return $em;
    }
    
    public function emMockInvalidUser()
    {
        $user = new \User\Entity\User($this->asset->getData());
        
        $em = Mockery::mock('Doctrine\ORM\EntityManager');
        $em->shouldReceive('getRepository')->andReturn($em);
        $em->shouldReceive('findOneBy')->andReturn(null);
        
        return $em;
    }
    
    public function serviceMock()
    {
        $userData = new \User\Entity\User($this->asset->getData());
        
        $userService = Mockery::mock('User\Service\User');
        $userService->shouldReceive('save')->andReturn(true);
        $userService->shouldReceive('edit')->andReturn(true);
        $userService->shouldReceive('read')->andReturn($userData);
        $userService->shouldReceive('findByEmailAndPassword')->andReturn($userData);
        
        return $userService;
    }
    
    public function serviceMockInvalidCredentials()
    {
        $userData = new \User\Entity\User($this->asset->getData());
        
        $userService = Mockery::mock('User\Service\User');
        $userService->shouldReceive('save')->andReturn(true);
        $userService->shouldReceive('edit')->andReturn(true);
        $userService->shouldReceive('read')->andReturn($userData);
        $userService->shouldReceive('findByEmailAndPassword')->andReturn(null);
        
        return $userService;
    }
    
    public function validAdapter()
    {
        $em = $this->emMock();
        $sm = $this->serviceMock();
        return new $this->adapterName($em, $sm);
    }
    
    public function invalidAdapter()
    {
        $em = $this->emMockInvalidUser();
        $sm = $this->serviceMockInvalidCredentials();
        return new $this->adapterName($em, $sm);
    }
    
    
    
    public function testClassExists()
    {
        $this->assertTrue(class_exists($this->adapterName));
    }
    
    public function testIfMethodAuthenticateExists()
    {
        $this->assertTrue(method_exists($this->validAdapter(), 'authenticate'));
    }
    
    public function testIfIsSettingValues()
    {
        $adapter = $this->validAdapter();
        
        $adapter->setUsername('teste@teste.com.br');
        $adapter->setPassword('1234');
        
        $this->assertNotNull($adapter->getUsername());
        $this->assertNotNull($adapter->getPassword());
    }
    
    
    public function testIfIsIgnoringInvalidData()
    {
        $adapter = $this->invalidAdapter();
        
        $result = $adapter->authenticate('teste@teste.com.br', '1234');
        
        $this->assertNotNull($result);
        $this->assertNull($result->getIdentity());
    }
    
    public function testIfIsAuthenticatingValidData()
    {   
        $this->markTestIncomplete('Não consegui criar os mocks suficientes para o teste passar');
    }
    
    
}
