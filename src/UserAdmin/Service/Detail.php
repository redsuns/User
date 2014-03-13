<?php

namespace UserAdmin\Service;

use Doctrine\ORM\EntityManager;
use Base\Service\AbstractService;
use User\Entity\Detail as Entity;


class Detail extends AbstractService
{
    public function __construct(EntityManager $em) 
    {
        $this->entity = 'User\Entity\Detail';
        parent::__construct($em);
    }
    
    /**
     * 
     * @return array
     */
    public function allDetails()
    {
        return $this->em->getRepository($this->entity)->findAll();
    }
    
    /**
     * 
     * @param array $data
     * @return boolean|int
     */
    public function save( array $data = array() )
    {
        $user = $this->em->getReference('User\Entity\User', $data['user']);
        if ( !$user ) {
            return false;
        }
        
        $detail = new Entity( $data );
        $detail->setUser($user);
        
        $this->em->persist($detail);
        $this->em->flush();
        
        return $detail->getId();
    }
      
}
