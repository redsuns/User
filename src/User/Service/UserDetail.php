<?php

namespace User\Service;

use Base\Service\AbstractService;
use User\Entity\UserDetail as Detail;
use Zend\Filter\Word\UnderscoreToSeparator;

class UserDetail extends AbstractService
{
    protected $user;
    
    public function __construct(\Doctrine\ORM\EntityManager $em)
    {
        parent::__construct($em);
    }
    
    /**
     * 
     * @param \User\Entity\User $user
     * @return \User\Service\UserDetail
     */
    public function setUser(\User\Entity\User $user) 
    {
        $this->user = $user;
        return $this;
    }
    
    /**
     * 
     * @param array $data
     * @return boolean
     * @throws \InvalidArgumentException
     */
    public function persistDetails( array $data )
    {
        if ( empty($data) ) {
            throw new \InvalidArgumentException('Nenhum dado recebido');
        }
        
        $underscoreToSeparator = new UnderscoreToSeparator();
        $underscoreToSeparator->setReplacementSeparator(' ');
        
        foreach($data as $field => $value) {
            $detail = new Detail(array(
                'user' => $this->user,
                'field' => $field,
                'value' => $value,
                'label' => ucwords($underscoreToSeparator->filter($field))
            ));
            
            $this->em->persist($detail);
        }
        
        return true;
    }
    
}
