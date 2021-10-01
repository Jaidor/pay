<?php
namespace Jideola\Routes;

class ApiRoute{
    
    public static function loadRoute()
    {
        return[
            'create_pay'=>['POST'],
            'transactions'=>['POST']
        ];
    }
}