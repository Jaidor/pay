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
            $send['item'] = 'Flipflops';
            $send['category'] = 'Slippers';
            $send['availableUnit'] = '12';
            $send['unitPrice'] = '2000';
            $send['normalPrice'] = '4000';

            $all_send[] = $send;

            $sendx=[];
            $sendx['id'] = '2';
            $sendx['item'] = 'Phones';
            $sendx['category'] = 'Samsung';
            $sendx['availableUnit'] = '15';
            $sendx['unitPrice'] = '5000';
            $sendx['normalPrice'] = '2000';

            $all_send[] = $sendx;

            $pagination = [];
            $page['totalRecords'] = $totalRecords;
            $page['totalPages'] = $totalPages;
            $pagination[] = $page;

            if($all_send) $software->feedback(true, 'TRH_OK', ['Details fetched successfully'],['data'=>$all_send, 'pagination'=>$pagination]);
            else $software->feedback(false, 'TRH_000', ['Unable to fetch transaction history']);
                    
        // }
                
    }
}