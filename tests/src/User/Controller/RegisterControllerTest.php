<?php

namespace User\Controller;

use Zend\Http\Request as HttpRequest;

use User\Bootstrap;
use Base\Controller\TestCaseController;
use Mockery;
use User\Asset\UserAsset;
use User\Asset\UserDetailAsset;

/**
 * @group User
 */
class RegisterControllerTest extends TestCaseController
{
    protected $asset;
    
    public function setUp()
    {
        $this->setApplicationConfig(Bootstrap::getConfig());
        $this->asset = new UserAsset();
        
        parent::setUp();
        
        $this->smMock();
    }
    
    public function smMock()
    {
        $detailAsset = new UserDetailAsset();
        
        $userData = new \User\Entity\User($this->asset->getData());
        $details = $detailAsset->detailsToUser($userData);
        
        $userData->setDetails($details);
        
        $userService = Mockery::mock('User\Service\User');
        $userService->shouldReceive('save')->andReturn(true);
        $userService->shouldReceive('edit')->andReturn(true);
        $userService->shouldReceive('read')->with(1)->andReturn($userData);
        
        
        $userDetailService = Mockery::mock('User\Service\UserDetail');
        $userDetailService->shouldReceive('read')->with(1)->andReturn($userData);
        $userDetailService->shouldReceive('parseDetails')->andReturn($detailAsset->detailsToArray());
        
        $serviceManager = $this->getApplicationServiceLocator();
        $serviceManager->setAllowOverride(true);
        $serviceManager->setService('User\Service\User', $userService);
        $serviceManager->setService('User\Service\UserDetail', $userDetailService);
    }
    
    
    public function testIfCanAccessIndexAction() 
    {   
        $this->dispatch('/cadastre-se');
        $this->assertResponseStatusCode(200);
        
        $this->assertModuleName('User');
        $this->assertControllerName('register');
        $this->assertControllerClass('RegisterController');
        $this->assertActionName('index');
        $this->assertMatchedRouteName('cadastre-se');   
    }
    
    public function testIfFormWasSetted()
    {        
        $this->dispatch('/cadastre-se');
        $this->assertResponseStatusCode(200);
        
        $this->assertModuleName('User');
        $this->assertControllerName('register');
        $this->assertControllerClass('RegisterController');
        $this->assertActionName('index');
        $this->assertMatchedRouteName('cadastre-se');
        
        $viewManager = $this->getApplicationServiceLocator()->get('ViewManager');
        $variables   = $viewManager->getViewModel()->getChildren()[0]->getVariables();

        $this->assertArrayHasKey('form', $variables);
        return $this->getStatus();
    }
    
    public function testSendValidPostToIndex() 
    {   
        $post = $this->asset->getData();
        
        $this->dispatch('/cadastre-se', HttpRequest::METHOD_POST, $post);
        
        $this->assertResponseStatusCode(302);
        $this->assertModuleName('User');
        $this->assertControllerName('register');
        $this->assertActionName('index');
        $this->assertMatchedRouteName('cadastre-se');
        $this->assertRedirectTo('/meu-perfil');
    }
    
    public function testSendInvalidPostToIndex() 
    {   
        $post = $this->asset->getData();
        $post['email'] = 'teste';
        
        $this->dispatch('/cadastre-se', HttpRequest::METHOD_POST, $post);
        
        $this->assertResponseStatusCode(200);
        $this->assertModuleName('User');
        $this->assertControllerName('register');
        $this->assertActionName('index');
        $this->assertMatchedRouteName('cadastre-se');
    }
    
    public function testIfHasInputsOnView()
    {
        $this->dispatch('/cadastre-se');
        
        $this->assertQuery('body form input[name="name"]');
        $this->assertQuery('body form input[name="email"]');
    }
    
}