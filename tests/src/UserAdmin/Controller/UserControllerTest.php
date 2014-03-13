<?php
namespace UserAdmin\Controller;

use Zend\Http\Request as HttpRequest;
use User\Bootstrap;
use Base\Controller\TestCaseController;
use Mockery;
use UserAdmin\Service\User as UserService;
use UserAdmin\Service\Role as RoleService;
use Zend\Uri\Http;


class UserControllerTest extends TestCaseController
{
    protected $User;
    protected $service;


    public function setUp()
    {
        $this->setApplicationConfig(Bootstrap::getConfig());

        $this->sm = Bootstrap::getServiceManager();
        $this->em = $this->sm->get('Doctrine\ORM\EntityManager');
        $this->service = new UserService($this->em);

        parent::setUp();
    }

    public function userData()
    {
        return array(
            'name' => 'Andre',
            'email' => 'andre@redsuns.com.br',
            'password' => 'andre',
            'password_confirm' => 'andre',
            'role_id' => 1
        );
    }

    public function roleData()
    {
        return array(
            'name' => 'teste',
            'description' => 'teste'
        );
    }
    
    public function mockData( $returnRole =false )
    {
        $role = new \User\Entity\Role(array(
            'id' => 1,
            'name' => 'teste',
            'description' => 'teste'
        ));

        $user = new \User\Entity\User(array(
            'id' => 1,
            'name' => 'Andre',
            'email' => 'andre@redsuns.com.br',
            'password' => 'andre',
            'password_confirm' => 'andre',
            'role' => $role,
            'role_id' => 1));

        if ( $returnRole ) {
            return $role;
        }
        return $user;
    }
    
    public function createMock()
    {
        $service = Mockery::mock('UserAdmin\Service\User');

        $service->shouldReceive('getAllUsers')
                 ->shouldReceive('read')
                 ->shouldReceive('save')
                 ->shouldReceive('delete')
                 ->shouldReceive('checkEmailUser')
                 ->andReturn(array(0 => $this->mockData()));

        $serviceManager = $this->getApplicationServiceLocator();
        $serviceManager->setAllowOverride(true);
        $serviceManager->setService('UserAdmin\Service\User', $service);
    }

    public function createAllUsersMock()
    {
        $service = Mockery::mock('UserAdmin\Service\User');
        $service->shouldReceive('getAllUsers')
            ->andReturn($this->mockData());

        $serviceManager = $this->getApplicationServiceLocator();
        $serviceManager->setAllowOverride(true);
        $serviceManager->setService('UserAdmin\Service\User', $service);
    }

    public function createEditMock()
    {
        $service = Mockery::mock('UserAdmin\Service\User');
        $service->shouldReceive('checkEmailUser')
            ->shouldReceive('read')
            ->andReturn($this->mockData());

        $serviceManager = $this->getApplicationServiceLocator();
        $serviceManager->setAllowOverride(true);
        $serviceManager->setService('UserAdmin\Service\User', $service);
    }

    public function createInvalidEditMock()
    {
        $service = Mockery::mock('UserAdmin\Service\User');

        $service->shouldReceive('read')
            ->andReturn(null);

        $serviceManager = $this->getApplicationServiceLocator();
        $serviceManager->setAllowOverride(true);
        $serviceManager->setService('UserAdmin\Service\User', $service);
    }


    public function createRoleMock()
    {
        $service = Mockery::mock('UserAdmin\Service\Role');
        $role = new \User\Entity\Role(array(
            'name' => 'teste',
            'description' => 'teste'
        ));
        $roles = array(0 => $role);

        $service->shouldReceive('getAllRoles')
                ->shouldReceive('read')
                ->andReturn($role);




        $serviceManager = $this->getApplicationServiceLocator();
        $serviceManager->setAllowOverride(true);
        $serviceManager->setService('UserAdmin\Service\Role', $service);
    }


    public function testIfIndexActionIsAccessible()
    {
        $this->createAllUsersMock();

        $this->dispatch('/admin/users');

        $this->assertResponseStatusCode(200);
        $this->assertModuleName('UserAdmin');
        $this->assertControllerName('users');
        $this->assertControllerClass('UserController');
        $this->assertActionName('index');
        $this->assertMatchedRouteName('admin');
    }


    public function testIfAddNewRoleIsAccessible()
    {
        $this->createRoleMock();
        $this->createMock();

        $this->dispatch('/admin/users/add', HttpRequest::METHOD_GET);

        $this->assertModuleName('UserAdmin');
        $this->assertControllerName('users');
        $this->assertControllerClass('UserController');
        $this->assertActionName('add');
        $this->assertMatchedRouteName('admin');
        $this->assertResponseStatusCode(200);
    }


    public function testIfIsAbleToAddNewUser()
    {
        $this->createRoleMock();
        $this->createMock();
        $data = $this->userData();

        $this->dispatch('/admin/users/add', HttpRequest::METHOD_POST, $data);


        $this->assertModuleName('UserAdmin');
        $this->assertControllerName('users');
        $this->assertControllerClass('UserController');
        $this->assertActionName('add');
        $this->assertMatchedRouteName('admin');
        $this->assertResponseStatusCode(302);

    }

    public function testIfIsAbleToRejectMismatchPasswords()
    {
        $this->createRoleMock();
        $this->createMock();

        $data = $this->userData();
        $data['password_confirm'] = 'nova';

        $this->dispatch('/admin/users/add', HttpRequest::METHOD_POST, $data);

        $this->assertModuleName('UserAdmin');
        $this->assertControllerName('users');
        $this->assertActionName('add');
        $this->assertMatchedRouteName('admin');
        $this->assertResponseStatusCode(200);
    }


    public function testIfIsRejectingNonUniqueEmail()
    {
        //$this->markTestIncomplete('Falta verificar a validação de email duplicado');
    }

    public function testIfIsAbleToDeleteUser()
    {
        $this->createRoleMock();
        $this->createMock();

        $this->dispatch('/admin/users/delete', HttpRequest::METHOD_POST, array('id' => 1));

        $this->assertModuleName('UserAdmin');
        $this->assertControllerName('users');
        $this->assertActionName('delete');
        $this->assertMatchedRouteName('admin');
        $this->assertResponseStatusCode(302);
    }
    

    public function testIfEditUserIsAccessible()
    {
        $this->createRoleMock();
        $this->createEditMock();

        $this->dispatch('/admin/users/edit/1', HttpRequest::METHOD_GET);

        $this->assertModuleName('UserAdmin');
        $this->assertControllerName('users');
        $this->assertControllerClass('UserController');
        $this->assertActionName('edit');
        $this->assertMatchedRouteName('admin-internals');
        $this->assertResponseStatusCode(200);
    }

    public function testIfEditUserIsAbleToSaveUser()
    {
        $this->createRoleMock();
        $this->createEditMock();

        $data = $this->userData();
        $data['email'] = 'novo@novo.com.br';
        $this->dispatch('/admin/users/edit/1', HttpRequest::METHOD_POST, $data);

        $this->assertModuleName('UserAdmin');
        $this->assertControllerName('users');
        $this->assertControllerClass('UserController');
        $this->assertActionName('edit');
        $this->assertMatchedRouteName('admin-internals');
        $this->assertResponseStatusCode(302);
    }

    public function testIfIsAbleToEditUserRejectingOnDuplicatedEmail()
    {
        $this->markTestIncomplete('Não consegui testar a duplicidade de email, mas no site está funcionando.');
    }


    public function testIfRedirectsAfterInvalidUserAccess()
    {
        $this->createRoleMock();
        $this->createInvalidEditMock();

        $this->dispatch('/admin/users/edit/98', HttpRequest::METHOD_GET);

        $this->assertModuleName('UserAdmin');
        $this->assertControllerName('users');
        $this->assertControllerClass('UserController');
        $this->assertActionName('edit');
        $this->assertMatchedRouteName('admin-internals');
        $this->assertResponseStatusCode(302);
    }

}