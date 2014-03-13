<?php

namespace User\Asset;


class UserDetailAsset
{
    
    /**
     * 
     * @param \User\Entity\User $user
     * @return array
     */
    public function detailsToUser( \User\Entity\User $user )
    {
        return array(
            0 => new \User\Entity\UserDetail(array('id' => 1, 'field' => 'phone', 'value' => '4133333333', 'user' => $user)),
            1 => new \User\Entity\UserDetail(array('id' => 2, 'field' => 'cpf', 'value' => '00000000', 'user' => $user)),
            2 => new \User\Entity\UserDetail(array('id' => 1, 'field' => 'address', 'value' => 'teste', 'user' => $user)),
            3 => new \User\Entity\UserDetail(array('id' => 1, 'field' => 'address_number', 'value' => 41, 'user' => $user)),
            4 => new \User\Entity\UserDetail(array('id' => 1, 'field' => 'address_complement', 'value' => 'teste', 'user' => $user)),
            5 => new \User\Entity\UserDetail(array('id' => 1, 'field' => 'district', 'value' => 'teste', 'user' => $user)),
            6 => new \User\Entity\UserDetail(array('id' => 1, 'field' => 'city', 'value' => 'teste', 'user' => $user)),
            7 => new \User\Entity\UserDetail(array('id' => 1, 'field' => 'state', 'value' => 'PR', 'user' => $user)),
            8 => new \User\Entity\UserDetail(array('id' => 1, 'field' => 'cep', 'value' => '82900270', 'user' => $user)),
        );
    }
    
    /**
     * 
     * @return array
     */
    public function detailsToArray()
    {
        return array(
            'phone' => '4133333333',
            'cpf' => '000.000.000-00',
            'address' => 'Rua teste',
            'address_number' => 1234,
            'address_complement' => 'teste',
            'district' => 'teste',
            'city' => 'teste',
            'state' => 'PR',
            'cep' => '82.899-899',
        );
    }
    
}
