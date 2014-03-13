<?php

namespace User\Service;

use Base\Service\ServiceTestCase;
use User\Entity\User;
use Mockery;
use User\Bootstrap;
use User\Service\User as UserService;
use User\Asset\UserAsset;

/**
 * @group User
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
                        ->andReturn(new \User\Entity\User($this->asset->getData()));
        
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
}
