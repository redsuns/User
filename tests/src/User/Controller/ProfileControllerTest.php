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
class ProfileControllerTest extends TestCaseController
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
        
        $userData->setDetail($details);
        
        $userService = Mockery::mock('User\Service\User');
        $userService->shouldReceive('save')->andReturn(true);
        $userService->shouldReceive('edit')->andReturn(true);
        $userService->shouldReceive('read')->andReturn($userData);
        
        
        $userDetailService = Mockery::mock('User\Service\UserDetail');
        $userDetailService->shouldReceive('read')->with(1)->andReturn($userData);
        $userDetailService->shouldReceive('parseDetails')->andReturn($detailAsset->detailsToArray());
        
        $serviceManager = $this->getApplicationServiceLocator();
        $serviceManager->setAllowOverride(true);
        $serviceManager->setService('User\Service\User', $userService);
        $serviceManager->setService('User\Service\UserDetail', $userDetailService);
    }
    
    public function testIfInstanceOf()
    {
        $this->assertInstanceOf('User\Controller\ProfileController', new \User\Controller\ProfileController());
    }
    
    
    public function testIfEditActionIsAccessible()
    {
        $this->dispatch('/meu-perfil/1', HttpRequest::METHOD_GET);
        
        $this->assertModuleName('User');
        $this->assertControllerName('profile');
        $this->assertActionName('index');
        $this->assertResponseStatusCode(200);
        $this->assertMatchedRouteName('meu-perfil');
    }
    
    /**
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessage Parâmetro inválido recebido
     */
    public function testIfEditActionIsThrowingExceptionIfDontReceiveParams()
    {
        $this->dispatch('/meu-perfil/', HttpRequest::METHOD_GET, array('id' => null));
    }
    
    public function testIfIsObtainingExistentUser()
    {
        $this->dispatch('/meu-perfil/1', HttpRequest::METHOD_GET, array('id' => 1));
        
        $this->assertResponseStatusCode(200);
        $this->assertModuleName('User');
        $this->assertControllerName('profile');
        $this->assertActionName('index');
        $this->assertMatchedRouteName('meu-perfil');
    }
    
    public function testIfEditIsSavingAsExpected()
    {       
        $this->dispatch('/meu-perfil/1', HttpRequest::METHOD_POST, $this->asset->getData());
        
        $this->assertModuleName('User');
        $this->assertControllerName('profile');
        $this->assertActionName('index');
        $this->assertMatchedRouteName('meu-perfil');
        $this->assertRedirectTo('/meu-perfil/1');
        $this->assertResponseStatusCode(302);
    }
    
    public function testIfEditIsDoingNothingOnInvalidMethodData()
    {       
        $this->dispatch('/meu-perfil/1', HttpRequest::METHOD_GET, $this->asset->getData());
        
        $this->assertResponseStatusCode(200);
        $this->assertModuleName('User');
        $this->assertControllerName('profile');
        $this->assertActionName('index');
        $this->assertMatchedRouteName('meu-perfil');
    }
}
