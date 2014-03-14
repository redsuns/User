<?php

namespace UserAdmin\Service;

use Base\Service\ServiceTestCase;
use User\Bootstrap;
use UserAdmin\Service\Role as RoleService;
use User\Entity\Role;
use User\Entity\User;
use Mockery;

/**
 * @group ServiceAdmin
 */
class RoleTest extends ServiceTestCase
{
    protected $Role;
    protected $User;

    public function setUp()
    {
        $this->sm = Bootstrap::getServiceManager();
        
        $this->em = $this->sm->get('Doctrine\ORM\EntityManager');
        $this->service = new RoleService($this->em);
        
        $this->Role = new Role();
        $this->User = new User();
        
        parent::setUp();
    }

    protected function _addRole()
    {
        return array(
            'name' => 'teste',
            'description' => 'teste'
        );
    }
    
    protected function _addUser()
    {
        return $this->User->setName('Andre')
                            ->setEmail('andre@redsuns.com.br')
                            ->setPassword('andre')
                            ->setModified(new \DateTime());
    }


    public function testIfIsRetrievingAllRoles()
    {
        $this->service->save( $this->_addRole() );
        
        $result = $this->service->getAllRoles();
        
        $this->assertNotNull($result);
        
        $firstRole = current($result);
        $this->assertEquals('teste', $firstRole->getName());
        $this->assertEquals('teste', $firstRole->getDescription());
        $this->assertNotEmpty($firstRole->toArray());
        $this->assertEquals('teste', sprintf($firstRole));
    }
    

    public function testIfIsCurrectlySettingUserDataToRole()
    {
        $role = new Role();
        $role->setName('teste')
                ->setDescription('Teste')
                ->setUser($this->_addUser());
        
        $this->assertNotEmpty($role->getUser());
    }

    public function testIfIsAbleToReadRolePassedByParam()
    {
        $this->assertTrue($this->service->save($this->_addRole()) );
        $this->assertNotEmpty($this->service->read(1));
    }


    public function testIfIsDeletingCorrectly()
    {
        $this->service->save($this->_addRole());

        $this->assertEquals(1, $this->service->delete(1));
    }

    public function testIfIsParsingEditRolePassed()
    {
        $this->service->save($this->_addRole());

        $data = $this->_addRole();
        $data['id'] = 1;

        $response = $this->service->save($data);
        $this->assertTrue($response);
    }

}