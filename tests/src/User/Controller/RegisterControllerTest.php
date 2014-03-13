<?php

namespace User\Controller;

use Zend\Http\Request as HttpRequest;

use User\Bootstrap;
use Base\Controller\TestCaseController;
use Mockery;

/**
 * @group User
 */
class RegisterControllerTest extends TestCaseController
{
    public function setUp()
    {
        $this->setApplicationConfig(Bootstrap::getConfig());
        
        parent::setUp();
        
        $this->smMock();
    }
    
    public function smMock()
    {
        $userService = Mockery::mock('User\Service\User');
        $userService->shouldReceive('save')->andReturn(true);
     
        $serviceManager = $this->getApplicationServiceLocator();
        $serviceManager->setAllowOverride(true);
        $serviceManager->setService('User\Service\User', $userService);
        return $userService;
    }
    
    public function userData()
    {
        return [
            'name' => 'teste',
            'email' => 'teste@teste.com',
            'password' => '123456789',
            'password_confirm' => '123456789',
            'phone' => '(41) 3333-3333',
            'cpf' => '000.000.000-00',
            'address' => 'Rua teste',
            'address_number' => 12334,
            'address_complement' => 'teste',
            'district' => 'teste',
            'city' => 'teste',
            'state' => 'PR',
            'cep' => '82.899-899'
        ];
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
        $post = $this->userData();
        
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
        $post = $this->userData();
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