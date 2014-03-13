<?php

namespace UserAdmin\Service;

use Base\Service\ServiceTestCase;
use User\Bootstrap;
use UserAdmin\Service\User as UserService;
use UserAdmin\Service\Role as RoleService;
use User\Entity\Role;
use Mockery;

/**
 * @group Service
 */
class UserTest extends ServiceTestCase
{
    protected $roleService;

    public function setUp()
    {        
        $this->sm = Bootstrap::getServiceManager();
        
        $this->em = $this->sm->get('Doctrine\ORM\EntityManager');
        $this->service = new UserService($this->em);
        
        parent::setUp();
        $this->service->save($this->data());
    }

    public function tearDown()
    {
        parent::tearDown();
    }

    
    protected function data()
    {
        return array(
            'id' => 1, 
            'name' => 'teste',
            'email' => 'teste@teste.com',
            'password' => 'andre',
            'password_confirm' => 'andre',
            'role_id' => 1
        );
    }
    
    
    public function testIfIsAbleToReadSomeRegisteredUser()
    {
        $this->assertNotNull($this->service->read(1));
    }

    public function testIfIsRetrievingAllUsers()
    {
        $result = $this->service->getAllUsers();
        
        $this->assertNotNull($result);

        $firstUser = current($result);
        $this->assertEquals($this->data()['name'], $firstUser->getName());
        $this->assertEquals($this->data()['email'], $firstUser->getEmail());
        $this->assertNotEquals($this->data()['password'], $firstUser->getPassword());
        $this->assertNotEmpty($firstUser->toArray());

        $this->assertEquals(1, $this->service->count());
    }


    public function testIfIsCurrectlySettingRoleToUser()
    {
        $this->markTestIncomplete();
        $savedUser = $this->service->read(1);
        
        $this->assertEquals(1, $savedUser->getId());
        $this->assertEquals('teste', $savedUser->getName());
        $this->assertEquals(1, $savedUser->getRole()->getId());
    }


    public function testIfIsAbleToReadUserPassedByParam()
    {
        $this->assertNotEmpty($this->service->read(1));
    }


    public function testIfIsDeletingCorrectly()
    {
        $this->assertEquals(1, $this->service->delete(1));
    }


    public function testIfIsParsingEditUserPassed()
    {
        $data = $this->data();
        $name = $data['name'];
        $data['id'] = 1;
        $data['name'] = 'Editado';
        
        $id = $this->service->save($data);
        
        $editedUser = $this->service->read($id);

        $this->assertNotEquals($name, $editedUser->getName());
        $this->assertEquals('Editado', $editedUser->getName());
    }

    public function testIfIsCheckingEmailBeforeSave()
    {
        $this->markTestIncomplete();
        $data = $this->data();

        $this->assertTrue($this->service->checkEmailUser( $data['email'] ));

        $this->assertTrue($this->service->checkEmailUser( $data['email'], 1 ));
    }



    /**
     * @expectedException Doctrine\DBAL\DBALException
    */
    public function testIfIsInvalidRoleDataIsThrowingException()
    {
            $data = $this->data();
            $data['name'] = null;

            $this->service->save($data);
    }
    
}