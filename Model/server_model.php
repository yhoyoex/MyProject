 <?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of client_access_model
 *
 * @author samamaju
 */
class server_model extends Model {
    function __construct() {
        parent::__construct();        
        $this->imageserver = new ImageServerEngine();
    }
    
    function get_token($user_key){        
        return $this->imageserver->get_token($user_key);
    }

    public function get_client_info() {
		$this->imageserver = new ImageServerEngine();
		return $this->imageserver->get_client_info();
	}

	public function get_url_img($name) {
		$client_info = $this->get_client_info();
		$client_name = $client_info['client_name'];
		
		$server = $client_info['ip_server'] ;
		$url = "http://" . $server . "login/client_login/R";             
		$fields = array(
		    "client_id" => $client_info['client_name'],
		    "client_password" => $client_info['client_password']
		);                        
		foreach ($fields as $key => $v) {
		    $field_string .= $key .'=' . $v . '&';
		}
		$field_string = rtrim($field_string, '&');     
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER,true);
		curl_setopt($curl, CURLOPT_HEADER, false);
		curl_setopt($curl, CURLOPT_POST, count($fields));
		curl_setopt($curl, CURLOPT_POSTFIELDS, $field_string);
		$token = trim(curl_exec($curl));
		curl_close($curl);

		$url = "http://" . $server . "login/client_logout/";
		foreach ($fields as $key => $v) {
		    $field_string .= $key .'=' . $v . '&';
		}

		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, $url);
		curl_exec($curl);
		curl_close($curl);


		$url = "http://".$client_info['ip_server']."image/download/".$name."/large?token=".$token."&client_id=".$client_info['client_name'];
		return $url;
	}

}
