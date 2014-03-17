<?php

namespace User\Controller;

use Base\Controller\TestCaseController;
use User\Bootstrap;
use Zend\Http\Request as HttpRequest;
use User\Asset\UserAsset;
use Mockery;
use Zend\Authentication\Result;

/**
 * @group Login
 */
class AuthControllerTest extends TestCaseController
{
    protected $controller;
    protected $class;
    protected $asset;
    
    public function setUp()
    {
        $this->setApplicationConfig(Bootstrap::getConfig());
        $this->class = '\User\Controller\AuthController';
        $this->controller = new $this->class();
        $this->asset = new UserAsset();
        $this->em = $this->mockEm();
        parent::setUp();
    }
    
    public function tearDown()
    {
        unset($this->controller, $this->class);
        parent::tearDown();
    }
    
    public function mockAuth()
    {
        $userData = new \User\Entity\User($this->asset->getData());
        
        $userService = Mockery::mock('User\Service\User');
        $userService->shouldReceive('findByEmailAndPassword')->andReturn($userData);
        
        $adapter = Mockery::mock('User\Auth\Adapter');
        $adapter->shouldReceive('authenticate')->andReturn($resultSuccess);
        $adapter->shouldReceive('setUsername')->andReturn($adapter);
        $adapter->shouldReceive('setPassword')->andReturn($adapter);
        $adapter->shouldReceive('getUsername')->andReturn($adapter);
        $adapter->shouldReceive('getPassword')->andReturn($adapter);
        
        $serviceManager = $this->getApplicationServiceLocator();
        $serviceManager->setAllowOverride(true);
        $serviceManager->setService('User\Service\User', $userService);
    }
    
    public function mockEm()
    {
        $user = new \User\Entity\User($this->asset->getData());
        $em = Mockery::mock('Doctrine\ORM\EntityManager');
        $em->shouldReceive('findOneBy')->andReturn($user);
        
        return $em;
    }
    
    public function testIfClassExists()
    {
        $this->assertTrue(class_exists($this->class));
    }
    
    public function testInstanceOf()
    {
        $this->assertInstanceOf($this->class, $this->controller);
    }
    
    public function testIfLoginIsAccessible()
    {
        $this->dispatch('/login');
        
        $this->assertResponseStatusCode(200);
        $this->assertModuleName('User');
        $this->assertControllerName('auth');
        $this->assertActionName('index');
        $this->assertMatchedRouteName('login');
    }
    
    public function testIfIsSendingInvalidDataToLogin()
    {
        $this->markTestIncomplete('Não consegui mockar o suficiente para este teste passar');
    }
    
    public function testIfIsRejectingInvalidCredentials()
    {
        $this->markTestIncomplete('Não consegui mockar o suficiente para este teste passar');
    }
            
    
}
