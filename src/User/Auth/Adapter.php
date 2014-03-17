<?php

namespace User\Auth;

use Zend\Authentication\Adapter\AdapterInterface;
use Zend\Authentication\Result;
use User\Service\User as UserService;

class Adapter implements AdapterInterface
{

    protected $em;
    protected $username;
    protected $password;

    public function __construct(\Doctrine\ORM\EntityManager $em)
    {
        $this->em = $em;
    }

    public function getUsername()
    {
        return $this->username;
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function setUsername($username)
    {
        $this->username = $username;
        return $this;
    }

    public function setPassword($password)
    {
        $this->password = $password;
        return $this;
    }
    
    /**
     * 
     * @return \Zend\Authentication\Result
     */
    public function authenticate() 
    {
        $service = new \User\Service\User($this->em);
        $user = $service->findByEmailAndPassword($this->getUsername(), $this->getPassword());
        
        if ( $user ) {
            return new Result(Result::SUCCESS, array('user' => $user), array('Ok'));
        }
        
        return new Result(Result::FAILURE_CREDENTIAL_INVALID, null, array());
    }

}
