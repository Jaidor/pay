<?php

namespace Jideola\Controllers;
use Jideola\Traits\Request;

class TransactionsController {
    use Request;

    public function trans(){
        global $software;
        // $software->protect();

        /* 
        * Fetch transactions
        */
        if($this->request['type'] == 'fetch'){

            $pageNo = 1;


            $whereCondition = " where transaction_id = '2'";
            /* Pagination */
            $pageno = ($pageNo) ? $pageNo : $pageno = 1;
            $totalRecords = $software->dbval("select count(*) from #__transaction ".$whereCondition." ");

            $results_per_page = 10;
            $offset = ($pageno-1) * $results_per_page;
            $totalPages = ceil($totalRecords / $results_per_page);
            $trans = $software->dbarray("select * from #__transaction ".$whereCondition." order by transaction_id desc limit ".$offset.", ".$results_per_page." ");

            $tx_details = [];
            foreach($trans as $tx){

                $value['id'] = $tx['transaction_id'];
                $value['ref'] = $tx['transaction_message'];
                $value['date'] = $tx['transaction_date'];

                $tx_details[] = $value;
            }

            $pagination = [];
            $page['totalRecords'] = $totalRecords;
            $page['totalPages'] = $totalPages;
            $pagination[] = $page;

            if($tx_details) $software->feedback(true, 'TRH_OK', ['Details fetched successfully'],['data'=>$tx_details, 'pagination'=>$pagination]);
            else $software->feedback(false, 'TRH_000', ['Unable to fetch transaction history']);
                    
        }
                
    }
}