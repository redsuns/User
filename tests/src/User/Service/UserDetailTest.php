<?php

namespace User\Service;

use Base\Service\ServiceTestCase;
use User\Entity\UserDetail as UserDetailEntity;
use Mockery;
use User\Bootstrap;
use User\Service\UserDetail as UserDetailService;
use User\Asset\UserAsset;

/**
 * @group User
 */
class UserDetailTest extends ServiceTestCase
{
    
    protected $service;
    protected $asset;
    
    public function setUp()
    {
        $this->sm = Bootstrap::getServiceManager();
        parent::setUp();
        
        $this->asset = new UserAsset();
        $this->em = $this->emMock();
        $this->entity = new UserDetailEntity( $this->asset->getData() );
        $this->service = new UserDetailService($this->em);
    }
    
    public function tearDown()
    {
        
        parent::tearDown();
    }
    
    public function emMock()
    {
        $entityManager = Mockery::mock('Doctrine\ORM\EntityManager');
        
        $entityManager->shouldReceive('persist')->andReturn($entityManager);
        $entityManager->shouldReceive('flush')->andReturn($entityManager);
        
        return $entityManager;
    }
    
    public function getData()
    {
        $data = $this->asset->getData();
        unset($data['id'], $data['name'], $data['email'], $data['password']);
        
        return $data;
    }
    
    public function getUserEntityData()
    {
        return new \User\Entity\User($this->asset->getData());
    }
    
    
    public function testInstanceOf()
    {
        $this->assertInstanceOf('User\Service\UserDetail', $this->service);
    }
    
    public function testIfHasMethodSave()
    {
        $this->assertTrue(method_exists($this->service, 'persistDetails'));
    }
    
    public function testIfHasMethodSetUser()
    {
        $this->assertTrue(method_exists($this->service, 'setUser'));
    }
    
    /**
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessage Nenhum dado recebido
     */
    public function testIfIsThrowingExceptionOnReceiveAnEmptyArrayAsParam()
    {
        $this->service->linkDetails(array());
    }
    
    public function testIfIsSavingAsExpected()
    {
        $result = $this->service->setUser($this->getUserEntityData())->linkDetails($this->getData());
        $this->assertNotNull($result);
        $this->assertInternalType('array', $result);
    }
    
    public function testIfMethodParseDetailsExists()
    {
        $this->assertTrue(method_exists($this->service, 'parseDetails'));
    }
    
    public function testIfParseDetailsIsReturningAnyResult()
    {
        $assetDetails = new \User\Asset\UserDetailAsset();
        $details = $assetDetails->detailsToUser(new \User\Entity\User($this->asset->getData()));
        
        $result = $this->service->parseDetails($details);
        $this->assertNotNull($result);
    }
    
    public function testIfParseDetailsIsReturningAnArrayAsResult()
    {
        $assetDetails = new \User\Asset\UserDetailAsset();
        $details = $assetDetails->detailsToUser(new \User\Entity\User($this->asset->getData()));
        
        $result = $this->service->parseDetails($details);
        $this->assertInternalType('array', $result);
    }
    
    public function testIfMethodPersistDetailsExist()
    {
        $this->assertTrue(method_exists($this->service, 'persistDetails'));
    }
    
    public function testIfPersistDetailsIsReturningTrue()
    {
        $edited = $this->getData();
        $details = array(0 => new UserDetailEntity(array('id' => 1, 'field' => 'phone', 'value' => $edited['phone'])));
        
        $this->assertTrue($this->service->persistDetails($details, $edited));
    }
    
}
