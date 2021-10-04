<?php

namespace Jideola\Controllers;
use Jideola\Traits\Request;

class ListPurchaseController {
    use Request;

    public function ListPurchase(){
        global $software;

            $all_send=[];

            $send=[];
            $send['id'] = '1';
            $send['item'] = 'Bulb';
            $send['quantity'] = '35';
            $send['amountPaid'] = '2000';
            $send['profitMade'] = '40';

            $all_send[] = $send;

            $sendx=[];
            $sendx['id'] = '2';
            $sendx['item'] = 'Electronics';
            $sendx['quantity'] = '13';
            $sendx['amountPaid'] = '5000';
            $sendx['profitMade'] = '10';

            $all_send[] = $sendx;

            $pagination = [];
            $page['totalRecords'] = $totalRecords;
            $page['totalPages'] = $totalPages;
            $pagination[] = $page;

            if($all_send) $software->feedback(true, 'TRH_OK', ['Details fetched successfully'],['data'=>$all_send, 'pagination'=>$pagination]);
            else $software->feedback(false, 'TRH_000', ['Unable to fetch transaction history']);
                    
                
    }
}