<?php
namespace UserAdmin\Controller;

use Zend\Http\Request as HttpRequest;
use User\Bootstrap;
use Base\Controller\TestCaseController;
use Mockery;



class RoleControllerTest extends TestCaseController
{
    protected $Role;
    protected $service;


    public function setUp()
    {
        $this->setApplicationConfig(Bootstrap::getConfig());
        
        parent::setUp();
    }
    
    
    
    public function createMock( $target = 'default' )
    {   
        $service = Mockery::mock('UserAdmin\Service\Role');
        $role = new \User\Entity\Role(array('name' => 'teste', 'description' => 'Teste'));
        $roles = array(0 => $role);

        switch( $target ){
            case 'edit':
                $service->shouldReceive('save')
                        ->shouldReceive('read')
                        ->andReturn($role);
                break;
            case 'allRoles':
                $service->shouldReceive('getAllRoles')
                    ->andReturn($role);
                break;
            default:
                $service->shouldReceive('getAllRoles')
                    ->shouldReceive('read')
                    ->shouldReceive('save')
                    ->shouldReceive('delete')
                    ->andReturn($roles);
                break;
        }

        $serviceManager = $this->getApplicationServiceLocator();
        $serviceManager->setAllowOverride(true);
        $serviceManager->setService('UserAdmin\Service\Role', $service);
    }

    
    public function testIfIndexActionIsAccessible()
    {
        $this->createMock( 'allRoles' );
        
        $this->dispatch('/admin/roles');
        $this->assertResponseStatusCode(200);
        
        $this->assertModuleName('UserAdmin');
        $this->assertControllerName('roles');
        $this->assertControllerClass('RoleController');
        $this->assertActionName('index');
        $this->assertMatchedRouteName('admin');
    }
    

    public function testIfAddNewRoleIsAccessible()
    {
        $this->dispatch('/admin/roles/add');
        $this->assertResponseStatusCode(200);

        $this->assertModuleName('UserAdmin');
        $this->assertControllerName('roles');
        $this->assertControllerClass('RoleController');
        $this->assertActionName('add');
        $this->assertMatchedRouteName('admin');
    }
    
    
    public function testIfIsAbleToAddNewRole()
    {
        $this->createMock();
        
        $data = array('name' => 'Novo', 'description' => 'teste');
        $this->dispatch('/admin/roles/add', HttpRequest::METHOD_POST, $data);

        $this->assertModuleName('UserAdmin');
        $this->assertControllerName('roles');
        $this->assertControllerClass('RoleController');
        $this->assertActionName('add');
        $this->assertMatchedRouteName('admin');
        $this->assertResponseStatusCode(302);

    }
    

    public function testIfEditRoleIsAccessible()
    {
        $this->createMock();
        
        $this->dispatch('/admin/roles/edit', HttpRequest::METHOD_GET, array('id' => 1));

        $this->assertModuleName('UserAdmin');
        $this->assertControllerName('roles');
        $this->assertControllerClass('RoleController');
        $this->assertActionName('edit');
        $this->assertMatchedRouteName('admin');
    }
    

    public function testIfRedirectsAfterInvalidRoleAccess()
    {
        $this->createMock();
        
        $this->dispatch('/admin/roles/edit/999', HttpRequest::METHOD_GET);

        $this->assertModuleName('UserAdmin');
        $this->assertControllerName('roles');
        $this->assertControllerClass('RoleController');
        $this->assertActionName('edit');
        $this->assertMatchedRouteName('admin-internals');
        $this->assertResponseStatusCode(302);
    }
    
    public function testIfIsAbleToEditRole()
    {
        $this->createMock('edit');
        
        $editedRole = array('id' => 1, 'name' => 'novo', 'description' => 'Teste');
        $this->dispatch('/admin/roles/edit/1', HttpRequest::METHOD_POST, $editedRole);

        $this->assertModuleName('UserAdmin');
        $this->assertControllerName('roles');
        $this->assertControllerClass('RoleController');
        $this->assertActionName('edit');
        $this->assertMatchedRouteName('admin-internals');
        
        $this->assertResponseStatusCode(302);
    }


    public function testIfDeleteRoleIsAccessible()
    {
        $this->createMock();

        $this->dispatch('/admin/roles/delete/1');

        $this->assertModuleName('UserAdmin');
        $this->assertControllerName('roles');
        $this->assertActionName('delete');
        $this->assertMatchedRouteName('admin-internals');
        $this->assertResponseStatusCode(302);
    }

    public function testIfIsAbleToDeleteRole()
    {
        $this->createMock();

        $this->dispatch('/admin/roles/delete', HttpRequest::METHOD_POST, array('id' => 1));

        $this->assertModuleName('UserAdmin');
        $this->assertControllerName('roles');
        $this->assertActionName('delete');
        $this->assertMatchedRouteName('admin');

        $this->assertResponseStatusCode(302);
    }

}