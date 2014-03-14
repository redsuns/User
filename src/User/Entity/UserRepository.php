<?php

namespace User\Entity;

use Doctrine\ORM\EntityRepository;


class UserRepository extends EntityRepository
{
    
    /**
     * 
     * @param string $email
     * @param string $password
     * @return boolean
     */
    public function findByEmailAndPassword( $email, $password )
    {
        $user = $this->findOneBy(array('email' => $email));
        if ( $user ) {
            $passwordHash = $this->encryptPassword($password);
            
            if ( $passwordHash == $user->getPassword() ) {
                return $user;
            } 
        }
        
        return false;
    }
    
}
