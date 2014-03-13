<?php

namespace UserAdmin\Service;

use Base\Service\ServiceTestCase;
use User\Bootstrap;
use UserAdmin\Service\Detail as DetailService;
use UserAdmin\Service\User as UserService;
use User\Entity\User;
use Mockery;

/**
 * @group Service
 */
class DetailTest extends ServiceTestCase
{
    
    protected $service;
    
    public function SetUp()
    {
        $this->sm = Bootstrap::getServiceManager();
        
        parent::setUp();
        
        $this->em = $this->sm->get('Doctrine\ORM\EntityManager');
        $this->service = new DetailService($this->em);
        
        
        $this->em->persist($this->userData());
        
        $detail = new \User\Entity\Detail($this->data());
        $this->em->persist($detail);
        
        $this->em->flush();
    }
    
    public function tearDown() 
    {
        unset($this->em, $this->service);
        parent::tearDown();
    }
    
    public function data()
    {
        return [
            'field' => 'telefone',
            'value' => 'Novo teste',
            'label' => 'Teste',
            'user' => $this->userData()
        ];
    }
    
    public function userData()
    {
        return new \User\Entity\User([
           'id' => 1,
            'name' => 'teste',
            'email' => 'teste@teste.com',
            'password' => '123'
        ]);
    } 
           
    
    public function testGetAllDetails()
    {
        $result = $this->service->allDetails();
        
        $this->assertNotNull($result);
        $this->assertInstanceOf('User\Entity\Detail', current($result));
    }
    
    public function testSave()
    {
        $data = $this->data();
        $data['user'] = 1;
        
        $result = $this->service->save( $data );
        
        $this->assertNotNull($result);
        $this->assertInternalType('int', $result);
    }
    
}
