<?php

namespace User\Asset;

class UserAsset
{
    
    public function getData()
    {
        return [
            'id' => 1,
            'name' => 'teste',
            'email' => 'teste@teste.com.br',
            'password' => '123',
            'phone' => '4133333333',
            'cpf' => '000.000.000-00',
            'address' => 'Rua teste',
            'address_number' => 1234,
            'address_complement' => 'teste',
            'district' => 'teste',
            'city' => 'teste',
            'state' => 'PR',
            'cep' => '82.899-899',
            'status' => true
        ];
    }
    
}
