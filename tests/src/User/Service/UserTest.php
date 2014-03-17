<?php

namespace User\Service;

use Base\Service\ServiceTestCase;
use User\Entity\User;
use Mockery;
use User\Bootstrap;
use User\Service\User as UserService;
use User\Asset\UserAsset;
use User\Asset\UserDetailAsset;

/**
 * @group UserService
 */
class UserTest extends ServiceTestCase
{
    protected $service;
    protected $asset;
    
    public function setUp()
    {
        $this->sm = Bootstrap::getServiceManager();
        parent::setUp();
        
        $this->asset = new UserAsset();
        $this->em = $this->emMock();
        $this->entity = new User( $this->asset->getData() );
        $this->service = new UserService($this->em);
    }
    
    public function tearDown()
    {
        
        parent::tearDown();
    }
    
    public function emMock()
    {
        $detailAsset = new UserDetailAsset();
        
        $userData = $this->asset->getData();
        $user = new \User\Entity\User($userData);
        $user->setDetails(array(0 => new \User\Entity\UserDetail($detailAsset->detailsToArray()))) ;
        
        $entityManager = Mockery::mock('Doctrine\ORM\EntityManager');
        
        $entityManager->shouldReceive('count')->andReturn(1);
        
        $entityManager->shouldReceive('getReference')->andReturn($entityManager);
        $entityManager->shouldReceive('createQueryBuilder')->andReturn($entityManager);
        $entityManager->shouldReceive('getRepository')->andReturn($entityManager);
        $entityManager->shouldReceive('select')->andReturn($entityManager);
        $entityManager->shouldReceive('from')->andReturn($entityManager);
        $entityManager->shouldReceive('getQuery')->andReturn($entityManager);
        $entityManager->shouldReceive('getSingleScalarResult')->andReturn(1);
       
        $entityManager->shouldReceive('remove')->andReturn($entityManager);
        $entityManager->shouldReceive('persist')->andReturn($entityManager);
        $entityManager->shouldReceive('flush')->andReturn($entityManager);
        $entityManager->shouldReceive('findAll')->andReturn(array(0 => new \User\Entity\User($this->asset->getData())));
        $entityManager->shouldReceive('findOneBy')
                        ->with(array('id' => 1))
                        ->andReturn($user);
        $entityManager->shouldReceive('findOneBy')
                        ->with(array('email' => $userData['email']))
                        ->andReturn($user);
        $entityManager->shouldReceive('findOneBy')
                        ->with(array('email' => 'teste@novo.com'))
                        ->andReturn(false);
        
        return $entityManager;
    }
    
    public function testInstanceOf()
    {
        $this->assertInstanceOf('User\Service\User', $this->service);
    }
    
    public function testIfHasMethodCount()
    {
        $this->assertTrue(method_exists($this->service, 'count'));
    }
    
    public function testIfMethodCountIsReturningAnInt()
    {
        $this->assertInternalType('int', $this->service->count());
    }
    
    public function testIfHasMethodSave()
    {
        $this->assertTrue(method_exists($this->service, 'save'));
    }
    
    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Nenhum dado recebido
     */
    public function testIfSaveIsRequiringParams()
    {
        $this->service->save(array());
    }
    
    public function testIfIsSavingAsExpected()
    {
        $this->assertTrue($this->service->save( $this->asset->getData() ));
    }
    
    public function testIfIsdeletingAsExpected()
    {
        $this->assertInternalType('int', $this->service->delete(1));
    }
    
    public function testIfHasMethodInactivateProfile()
    {
        $this->assertTrue(method_exists($this->service, 'inactivateProfile'));
    }
    
    /**
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessage Não permitido inativar usuário
     */
    public function testIfInactivateIsThrowingExceptionOnInvalidId()
    {
        $this->service->inactivateProfile();
    }
    
    public function testIfIsInactivatingAsExpected()
    {
        $this->assertTrue($this->service->inactivateProfile(1));
    }
    
    public function testIfMethodReadExists()
    {
        $this->assertTrue(method_exists($this->service, 'read'));
    }
    
    /**
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessage Parâmetro inválido recebido
     */
    public function testIfReadMethodIsThrowingExceptionIfDontReceiveParamsOrEmptyParams()
    {
        $this->service->read(array());
    }
    
    public function testIfReadMethodIsReturningUserObject()
    {
        $this->assertInstanceOf('User\Entity\User', $this->service->read(1));
    }
    
    public function testIfMethodEditExists()
    {
        $this->assertTrue(method_exists($this->service, 'edit'));
    }
    
    /**
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessage Nenhum dado recebido
     */
    public function testIfIsThrowingExceptionOnEmptyParam()
    {
        $this->assertTrue($this->service->edit(array()));
    }
    
    public function testIfIsEditingWithoutChangePassword()
    {
        $data = $this->asset->getData();
        $data['password'] = '';
        $data['name'] = 'Editado';
        unset($data['password_confirm']);
        
        $this->assertTrue($this->service->edit($data));
    }
    
    public function testIfIsEditingChangingPassword()
    {
        $data = $this->asset->getData();
        $data['name'] = 'Editado';
        unset($data['password_confirm']);
        
        $this->assertTrue($this->service->edit($data));
    }
    
    public function testIfMethodParsePasswordUpdate()
    {
        $this->assertTrue(method_exists($this->service, '_parsePasswordUpdate'));
    }
    
    public function testIfMethodFindByEmailAndPasswordExists()
    {
        $this->assertTrue(method_exists($this->service, 'findByEmailAndPassword'));
    }
    
    public function testIfValidDataIsPassing()
    {
        $data = $this->asset->getData();
        
        $result = $this->service->findByEmailAndPassword($data['email'], $data['password']);
        $this->assertNotNull($result);
    }
    
    public function testIfInvalidPasswordIsRejectingLogin()
    {
        $data = $this->asset->getData();
        
        $result = $this->service->findByEmailAndPassword($data['email'], 123);
        $this->assertFalse($result);
    }
    
    public function testIfInvlidEmailIsRejectingLogin()
    {
        $data = $this->asset->getData();
        
        $result = $this->service->findByEmailAndPassword('teste@novo.com', 123);
        $this->assertFalse($result);
    }
    
}
