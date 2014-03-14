<?php

namespace User\Entity;

use Base\Entity\EntityTestCase;
use User\Entity\User;
use Doctrine\ORM\Mapping\ClassMetadata;
use Mockery;
use User\Asset\UserAsset;

/**
 * @group Login
 */
class UserRepositoryTest extends EntityTestCase
{
    protected $entity;
    protected $repositoryName;
    protected $repository;
    protected $asset;
    
    public function setUp()
    {
        $this->asset = new UserAsset();
        $this->entity = new User($this->asset->getData());
        $this->repositoryName = 'User\Entity\UserRepository';
        $this->repository = new $this->repositoryName($this->em, $this->classMock());
        
        parent::setUp();
        $this->em = $this->createEmMock();
    }
    
    public function tearDown()
    {
        unset($this->entity, $this->repository, $this->repositoryName, $this->asset);
        parent::tearDown();
    }
    
    public function classMock()
    {
        $class = Mockery::mock('Doctrine\ORM\Mapping\ClassMetadata');
        $class->shouldReceive('hasField')->andReturn($class);
        
        return $class;
    }
    
    public function createEmMock()
    {
        $em = Mockery::mock('Doctrine\ORM\EntityManager');
        $em->shouldReceive('findOneBy')->andReturn(new \User\Entity\User($this->asset->getData()));
        
        return $em;
    }
    
    public function testIfClassExists()
    {
        $this->assertTrue(class_exists($this->repositoryName));
    }
    
    public function testIfFindByEmailAndPasswordMethodExists()
    {
        $this->assertTrue(method_exists($this->repository, 'findByEmailAndPassword'));
    }
    
    public function testInvalidCredentials()
    {
        $result = $this->repository->findByEmailAndPassword('teste@teste.com.br', '1234567890');
        $this->assertFalse($result);
    }
    
}
