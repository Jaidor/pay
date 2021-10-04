<?php

namespace Jideola\Controllers;
use Jideola\Traits\Request;

class ListItemsController {
    use Request;

    public function ListItems(){
        global $software;
        // $software->protect();

        /* 
        * Fetch transactions
        */
        // if($this->request['type'] == 'fetch'){

            // $pageNo = 1;


            // $whereCondition = " where transaction_id = '2'";
            // /* Pagination */
            // $pageno = ($pageNo) ? $pageNo : $pageno = 1;
            // $totalRecords = $software->dbval("select count(*) from #__transaction ".$whereCondition." ");

            // $results_per_page = 10;
            // $offset = ($pageno-1) * $results_per_page;
            // $totalPages = ceil($totalRecords / $results_per_page);
            // $trans = $software->dbarray("select * from #__transaction ".$whereCondition." order by transaction_id desc limit ".$offset.", ".$results_per_page." ");

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


            // $tx_details = [];
            // foreach($all_send as $tx){

            //     $value['id'] = $tx['transaction_id'];
            //     $value['ref'] = $tx['transaction_message'];
            //     $value['date'] = $tx['transaction_date'];

            //     $tx_details[] = $value;
            // }

            $pagination = [];
            $page['totalRecords'] = $totalRecords;
            $page['totalPages'] = $totalPages;
            $pagination[] = $page;

            if($all_send) $software->feedback(true, 'TRH_OK', ['Details fetched successfully'],['data'=>$all_send, 'pagination'=>$pagination]);
            else $software->feedback(false, 'TRH_000', ['Unable to fetch transaction history']);
                    
        // }
                
    }
}