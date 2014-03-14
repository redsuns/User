<?php

namespace User\Controller;

use Base\Controller\TestCaseController;
use User\Bootstrap;

/**
 * @group Login
 */
class AuthControllerTest extends TestCaseController
{
    protected $controller;
    protected $class;
    
    public function setUp()
    {
        $this->setApplicationConfig(Bootstrap::getConfig());
        $this->class = '\User\Controller\AuthController';
        $this->controller = new $this->class();
        parent::setUp();
    }
    
    public function tearDown()
    {
        unset($this->controller, $this->class);
        parent::tearDown();
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
            
    
}
