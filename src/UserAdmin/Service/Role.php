<?php

namespace UserAdmin\Service;

use Doctrine\ORM\EntityManager;
use Base\Service\AbstractService;


class Role extends AbstractService
{

    public function __construct(EntityManager $em)
    {
        parent::__construct($em);

        $this->entity    = 'User\Entity\Role';
    }
    
    
    public function getAllRoles()
    {
        return $this->getRepository($this->entity)->findAll();
    }


    public function read($roleId)
    {
        return $this->getRepository($this->entity)->find($roleId);
    }
    
    
    public function save( $data )
    {
            $role = new $this->entity($data);

            $roleId = isset($data['id']) ? $data['id'] : 0;

            if ( 0!== $roleId ) {
                $role = $this->read($roleId);
                $role->setName($data['name'])
                        ->setDescription($data['description'])
                        ->setModified(new \DateTime());
            }

            $this->em->persist($role);

            $this->em->flush();

            return true;

    }

}
