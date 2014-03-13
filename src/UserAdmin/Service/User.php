<?php

namespace UserAdmin\Service;

use Doctrine\ORM\EntityManager;
use Base\Service\AbstractService;
use Doctrine;


class User extends AbstractService
{

    protected $roleEntity;

    public function __construct(EntityManager $em)
    {
        parent::__construct($em);

        $this->entity    = 'User\Entity\User';
        $this->roleEntity = 'User\Entity\Role';
    }
    
    
    public function getAllUsers()
    {
        return $this->getRepository($this->entity)->findAll();
    }
    
    
    public function read($userId)
    {
        return $this->getRepository($this->entity)->find($userId, null);
    }

    
    public function save( $data )
    {
            $user = new $this->entity($data);
            
            $userId = isset($data['id']) ? $data['id'] : 0;
            $roleId = isset($data['role_id']) ? $data['role_id'] : 0;

            $role = $this->em->getRepository($this->roleEntity)->findOneBy(array('id' => $roleId));
            
            $user->setRole($role);
            
            $this->em->persist($user);
            $this->em->flush();
            
            return $user->getId();
    }

    public function count()
    {
        $qb = $this->getRepository($this->entity)
            ->createQueryBuilder('u')
            ->select('COUNT(u.id)')
            ->from($this->entity, 'users');

        return $qb->getQuery()->getSingleScalarResult();
    }

    public function checkEmailUser($email, $id = 0)
    {
        $and = '';
        if ( 0 < $id ) {
            $and = " AND u.id != " . $id;
        }

        $qb = $this->getRepository($this->entity)
            ->createQueryBuilder('u')
            ->select('u.id')
            ->from($this->entity, 'users')
            ->where("u.email='".$email."'" . $and)
            ->setMaxResults(1);

        $result = $qb->getQuery()->getOneOrNullResult();

        return (null === $result);
    }

}
