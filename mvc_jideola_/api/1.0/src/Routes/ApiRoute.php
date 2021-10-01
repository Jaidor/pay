<?php
namespace Jideola\Routes;

class ApiRoute{
    
    public static function loadRoute()
    {
        return[
            'create_pa'=>['POST'],
            'transactions'=>['POST']
        ];
    }
}