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
    public function linkDetails( array $data )
    {
        if ( empty($data) ) {
            throw new \InvalidArgumentException('Nenhum dado recebido');
        }
        
        $underscoreToSeparator = new UnderscoreToSeparator();
        $underscoreToSeparator->setReplacementSeparator(' ');
        
        $detail = array();
        foreach($data as $field => $value) {
            if ( !empty($field) && !empty($value) ) {
                $detail[] = new Detail(array(
                    'user' => $this->user,
                    'field' => $field,
                    'value' => $value,
                    'label' => ucwords($underscoreToSeparator->filter($field))
                ));
            }
        }
        
        return $detail;
    }
    
    /**
     * 
     * @param array $details
     * @param array $edited
     * @return boolean
     */
    public function persistDetails( $details, array $edited )
    {
        foreach($details as $detail) {
            if ( array_key_exists($detail->getField(), $edited) ) {
                $detail->setValue($edited[$detail->getField()]);
                
                $this->em->persist($detail);
            }
        }
        
        return true;
    }
    
    /**
     * 
     * @param array $details
     * @return array
     */
    public function parseDetails( $details )
    {
        $items = array();
        foreach($details as $detail) {
            $items[$detail->getField()] = $detail->getValue();
        }
        
        return $items;
    }
    
}
