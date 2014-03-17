<?php

namespace User\Entity;

use Doctrine\ORM\Mapping as ORM;
use Zend\Stdlib\Hydrator;

/**
 * @ORM\Entity
 * @ORM\Table(name="user_details")
 */
class UserDetail
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
    protected $field;

    /**
     * @ORM\Column(type="text")
     * @var string
     */
    protected $value;
    
    /**
     * @ORM\Column(type="text")
     * @var string
     */
    protected $label;
    
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
     * @ORM\ManyToOne(targetEntity="User", inversedBy="detail", cascade={"persist", "merge", "refresh"})
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    protected $user;
    

    public function getId()
    {
        return $this->id;
    }

    public function getField()
    {
        return $this->field;
    }

    public function getValue()
    {
        return $this->value;
    }

    public function getLabel()
    {
        return $this->label;
    }

    public function getCreated()
    {
        return $this->created;
    }

    public function getModified()
    {
        return $this->modified;
    }
    
    public function getUser()
    {
        return $this->user;
    }

    public function setField($field)
    {
        $this->field = $field;
        return $this;
    }

    public function setValue($value)
    {
        $this->value = $value;
        return $this;
    }

    public function setLabel($label)
    {
        $this->label = $label;
        return $this;
    }
    
    public function setUser($user)
    {
        $this->user = $user;
        return $this;
    }

    public function toArray()
    {
        $hydrator = new Hydrator\ClassMethods();
        return $hydrator->extract($this);
    }
    
    public function __toString()
    {
        return $this->getField();
    }
    
    public function __construct(array $options = array())
    {
        $hydrator = new Hydrator\ClassMethods();
        $hydrator->hydrate($options, $this);

        $this->created = new \Datetime('now');
        $this->modified = new \Datetime('now');
    }

}
