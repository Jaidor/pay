<?php
namespace Jideola\Routes;

class ApiRoute{
    
    public static function loadRoute()
    {
        return[
            'create_pay'=>['POST'],
            'transactions'=>['POST'],
            'list_items'=>['POST'],
            'list_category'=>['POST'],
            'list_purchase'=>['POST']
        ];
    }
}