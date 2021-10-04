<?php

namespace Jideola\Controllers;
use Jideola\Traits\Request;

class ListCategoryController {
    use Request;

    public function ListCategory(){
        global $software;

            $all_send=[];

            $send=[];
            $send['id'] = '1';
            $send['category'] = 'Food';
            $send['amountPaid'] = '7000';
            $send['profitMade'] = '7000';

            $all_send[] = $send;

            $sendx=[];
            $sendx['id'] = '2';
            $sendx['category'] = 'Stationaries';
            $sendx['amountPaid'] = '4000';
            $sendx['profitMade'] = '4000';

            $all_send[] = $sendx;

            $pagination = [];
            $page['totalRecords'] = $totalRecords;
            $page['totalPages'] = $totalPages;
            $pagination[] = $page;

            if($all_send) $software->feedback(true, 'TRH_OK', ['Details fetched successfully'],['data'=>$all_send, 'pagination'=>$pagination]);
            else $software->feedback(false, 'TRH_000', ['Unable to fetch transaction history']);
                
    }
}