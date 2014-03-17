<?php

namespace User\Entity;

use Doctrine\ORM\Mapping as ORM;
use Zend\Stdlib\Hydrator;
use Zend\Math\Rand;
use Zend\Crypt\Key\Derivation\Pbkdf2;

/**
 * @ORM\Entity
 * @ORM\Table(name="users")
 */
class User
{

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     * @var int
     */
    protected $id;

    /**
     * @ORM\Column(type="text")
     * @var string
     */
    protected $name;

    /**
     * @ORM\Column(type="text")
     * @var string
     */
    protected $email;
    
    /**
     * @ORM\Column(type="text")
     * @var string
     */
    protected $password;

    /**
     * @ORM\Column(type="text")
     * @var string
     */
    protected $salt;
    
    /**
     * @ORM\Column(type="boolean")
     * @var boolean 
     */
    protected $status;
    
    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime", nullable=false)
     */
    protected $created;
    
    
    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime", nullable=false)
     */
    protected $modified;
    
    /**
     * @ORM\OneToMany(targetEntity="UserDetail", mappedBy="user", cascade={"all"}, orphanRemoval=true)
     */
    protected $details;
    

    
    public function getId()
    {
        return $this->id;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function getSalt()
    {
        return $this->salt;
    }
    
    public function getStatus()
    {
        return $this->status;
    }

    public function getDetails()
    {
        return $this->details;
    }

    public function getCreated()
    {
        return $this->created;
    }

    public function getModified()
    {
        return $this->modified;
    }

    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    public function setEmail($email)
    {
        $this->email = $email;
        return $this;
    }

    public function setPassword($password)
    {
        $this->password = $this->encryptPassword($password);
        return $this;
    }
    
    public function setStatus($status)
    {
        $this->status = $status;
        return $this;
    }

    public function setSalt($salt)
    {
        $this->salt = $salt;
        return $this;
    }

    public function setModified(\DateTime $modified)
    {
        $this->modified = $modified;
        return $this;
    }
    
    public function setCreated(\DateTime $created)
    {
        $this->created = $created;
        return $this;
    }

    public function setDetails(array $details)
    {
        $this->details = $details;
        return $this;
    }

    public function toArray()
    {
        $hydrator = new Hydrator\ClassMethods();
        return $hydrator->extract($this);
    }

    public function encryptPassword($password)
    {
        return base64_encode(Pbkdf2::calc('sha256', $password, $this->salt, 10000, strlen($password*2)));
    }
    
    
    public function __construct(array $options = array())
    {
        $this->setSalt(base64_encode(Rand::getBytes(8, true)));
        
        $hydrator = new Hydrator\ClassMethods();
        $hydrator->hydrate($options, $this);

        $this->setCreated(new \Datetime());
        $this->setModified(new \DateTime());
        $this->setStatus(true);
    }
    
    public function __toString()
    {
        return $this->getName();
    }

}
