<?php

namespace User\Service;

use Doctrine\ORM\EntityManager;
use Base\Service\AbstractService;
use User\Entity\User as UserEntity;

class User extends AbstractService
{
    
    public function __construct(EntityManager $em)
    {
        parent::__construct($em);

        $this->entity = 'User\Entity\User';
    }
    
    /**
     * 
     * @return int
     */
    public function count()
    {
        $qb = $this->getRepository($this->entity)
            ->createQueryBuilder('u')
            ->select('COUNT(u.id)')
            ->from($this->entity, 'users');

        return $qb->getQuery()->getSingleScalarResult();
    }
    
    /**
     * Grava um novo usuário ou edita
     * 
     * @param array $data
     * @return boolean
     * @throws \InvalidArgumentException
     */
    public function save( array $data )
    {
        if ( empty($data) ) {
            throw new \InvalidArgumentException('Nenhum dado recebido');
        }
        
        $userData = array(
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => $data['password']
        );
        
        $user = new UserEntity( $userData );
        
        $this->em->persist($user);
        
        unset($data['id'], $data['name'], $data['email'], $data['password'], $data['submit'], $data['password_confirm']);
        $userDetail = new \User\Service\UserDetail($this->em);
        $userDetail->setUser($user)->linkDetails($data);
        
        $this->em->flush();
        return true;
    }
    
    
    public function edit( array $data )
    {
        if ( empty($data) ) {
            throw new \InvalidArgumentException('Nenhum dado recebido');
        }
        
        $user = $this->em->getRepository('User\Entity\User')->findOneBy(array('id' => $data['id']));

        $user->setName($data['name'])
             ->setEmail($data['email']);   

        $this->_parsePasswordUpdate($data);
        if ( isset($data['password']) && !empty($data['password']) ) {
            $user->setPassword($data['password']);
        }
        
        unset($data['id'], $data['name'], $data['email'], $data['password'], $data['submit'], $data['password_confirm']);
        $userDetail = new \User\Service\UserDetail($this->em);
        $userDetail->persistDetails($user->getDetail(), $data);
        
        $this->em->persist($user);
        $this->em->flush();
        
        return true;
    }
    
    /**
     * 
     * @param int $id
     * @return boolean
     * @throws \InvalidArgumentException
     */
    public function inactivateProfile( $id = null )
    {
        if ( empty($id) ) {
            throw new \InvalidArgumentException('Não permitido inativar usuário');
        }
        
        $user = $this->em->getRepository($this->entity)->findOneBy(array('id' => $id));
        
        $user->setStatus(false);
        
        $this->em->persist($user);
        $this->em->flush();
        
        return true;
    }
    
    /**
     * 
     * @param int $userId
     * @return \User\Entity\User
     * @throws \InvalidArgumentException
     */
    public function read( $userId )
    {
        if ( empty($userId) ) {
            throw new \InvalidArgumentException('Parâmetro inválido recebido');
        }
        
        return $this->em->getRepository($this->entity)->findOneBy(array('id' => $userId));
    }
    
    /**
     * 
     * @param array $userData
     */
    protected function _parsePasswordUpdate( array &$data )
    {
        if ( isset($data['password']) ) {
            if ( empty($data['password']) ) {
                unset($data['password']);
            }
        }
    }
}
