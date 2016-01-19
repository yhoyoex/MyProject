<?php

class SoapCall2 {

    function __construct(&$db) {
        $this->db = $db;
        $this->wsdl = "";
        $this->identity = "";
        
        require_once('lib/common/nusoap/lib/nusoap.php');
    }
    
    function set_target_wsdl($server){        
        $sql = "select * from soap_servers where name = :server";
        $res = $this->db->query($sql, array(":server" => $server));
        if(count($res) == 0) return;
        $res = $res[0];
        $this->wsdl = $res["server"];
        $this->identity = $res["identity"];
        
    }
    
    function init(){
        $this->client =new nusoap_client($this->wsdl,true);
        $err = $this->client->getError();
        if($err){
            echo 'Construct Error<br>' . $err . '<br>';
        }
        $this->client->timeout = 0;
        $this->client->response_timeout = 5;
    }
    function call($service, $parameters){

        $parameters["identity"] = $this->identity;

        $result = $this->client->call($service, $parameters);
        
        return $result;
    }    
    
}