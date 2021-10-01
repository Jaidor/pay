<?php
namespace Jideola\Traits;
use Jideola\Middlewares\RouteMiddleware;

trait Request{
    public $request;
    public $http_origin;

    public function __construct(){
        header('Content-Type: application/json');
        
        $requestType = RouteMiddleware::requestType();
        if($requestType === false) die(json_encode(['status'=>false, 'error'=>'Method not allowed', 'message'=>'Endpoint request missing...']));

        if(in_array($_SERVER['REQUEST_METHOD'] ,$requestType) && $_SERVER['REQUEST_METHOD']  == 'GET'){
            $this->cleanGetRequest();
        }elseif (in_array($_SERVER['REQUEST_METHOD'] ,$requestType) && $_SERVER['REQUEST_METHOD']  == 'POST'){
            $this->cleanPostRequest();
        }elseif($_SERVER['REQUEST_METHOD'] == 'OPTIONS'){
            http_response_code(200);
            die();
        }else{
            die(json_encode(['status'=>405, 'error'=>'Method not allowed', 'message'=>'Request method not supported']));
        }
        
    }

    private function cleanGetRequest()
    {
        global $software;
        /* Skip the first two get parameters api and version */
        $x = 1;
        foreach ($_GET as $index=>$value){
            if($x > 2) {
                $this->request[$index] = $software->antiHacking($value);
            }
            $x++;
        }
    }

    /**
     * function supports both form data and json post requests
     */
    private function cleanPostRequest()
    {
        global $software;

        $incomingJson = json_decode(file_get_contents('php://input'), true);
        if(!empty($incomingJson)) $_POST = $incomingJson;

        if(!empty($_POST['start'])) {

            /* decrypt data */
            $data = $software->privDecrypt($_POST['start']);
            $data = json_decode($data,true);
            
            /* To support multidimentional array */
            $clean_data=[];
            foreach ($data as $key=> $info) {
                if(is_array($info)) $clean_data[trim($key)] = $this->clean_array($info);
                else $clean_data[trim($key)] = $software->antiHacking($info);
            }

            $request = ['endpoint' =>$software->getCall(), 'scope'=>$software->getScope(), 'method'=>$software->getMethod(), 'version'=>$software->getVersion(), 'header'=>$software->getHeader(), 'request' => $data, 'date' => date('Y-m-d H:i:s')];
            $request = $software->array2string($request);
            $software->dbquery("insert into #__api_trail (api_trail_method, api_trail_data) values('{$software->getMethod()}', '$request') ");
            file_put_contents(MVC.'extension/logs/request_'.$software->getScope().'_'.$software->getMethod().'.txt',"Module: ".$software->getCall()."\n\nHeader:".print_r($software->getHeader(),true)."\n\nRaw: ".$_POST['start']."\n\nData: ".print_r($data,true));

            $this->request = $clean_data;
        }

    }

    private function clean_array($array)
    {
        global $software;
        $cleaned=[];
        foreach ($array as $key=> $info)
        {
            if(is_array($info)) $cleaned[trim($key)] = $this->clean_array($info);
            else $cleaned[trim($key)] = $software->antiHacking($info);
        }

        return $cleaned;
    }

}