<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ImageServerEngine
 *
 * @author samamaju
 */
class ImageServerEngine {
    function __construct() {                               
        $this->db = new Database(DB_NAME);
    }
    
    function get_client_info(){
        $sql = "Select * from imageserver";
        $imageserver = $this->db->query($sql);
        $client_info = $imageserver[0];
        
                
        return $client_info;
    }
    
    function logout_from_server($token){
        $client_info = $this->get_client_info();
        $server = "http://" . $client_info['ip_server'] . "login/client_logout/" . $token;
        
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $server);        
        curl_exec($curl);                      
        curl_close($curl);
    }
    
    function login_to_server(){                               
        $client_info = $this->get_client_info();

        $fields = array(
            "client_id" => $client_info['client_name'],
            "client_password" => $client_info['client_password']
        );

        $server = "http://" . $client_info['ip_server'] . "login/client_login/W";

        foreach ($fields as $key => $value) {
            $field_string .= $key .'=' . $value . '&';
        }
        
        $field_string = rtrim($field_string, '&');        

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $server);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER,true);
        curl_setopt($curl, CURLOPT_HEADER, false);
        curl_setopt($curl, CURLOPT_POST, count($fields));
        curl_setopt($curl, CURLOPT_POSTFIELDS, $field_string);

        $token = trim(curl_exec($curl));

        ob_get_clean();        
        
        curl_close($curl); 
        
        return $token;
    }
    
    function get_token($user_key){

        $token = $this->login_to_server();        
        $this->logout_from_server();
        
        return $token;
    }
    
    function upload_image_to_server($files, $token){            
        $client_info = $this->get_client_info();
        $url = "http://" . $client_info['ip_server'] . "image/upload?token=" . $token;
        
        for($i=0; $i< count($files['photo']['name']); $i++){
            $filename = $files['photo']['name'][$i];
            $filedata = $files['photo']['tmp_name'][$i];
            $filesize = $files['photo']['size'][$i];
            $filetype = $files['photo']['type'][$i];
            
            $headers = array("Content-Type:multipart/form-data"); // cURL headers for file uploading
            $postfields = array(
                "image" => "@$filedata;filename=$filename;type=$filetype;size=$filesize", 
                "client_id" => "casp2"
                    );
            $ch = curl_init();
            $options = array(
                CURLOPT_URL => $url,
                CURLOPT_HEADER => true,
                CURLOPT_POST => 1,
                CURLOPT_HTTPHEADER => $headers,
                CURLOPT_POSTFIELDS => $postfields,
                CURLOPT_INFILESIZE => $filesize,
                CURLOPT_RETURNTRANSFER => true
            ); // cURL options
            curl_setopt_array($ch, $options);
            $result = curl_exec($ch);
            if(!curl_errno($ch))
            {
                $info = curl_getinfo($ch);
                if ($info['http_code'] == 200)
                    $errmsg = "File uploaded successfully";
            }else{
                $errmsg = curl_error($ch);
            }
            curl_close($ch);    
            
            echo $i+1 . " :: " . substr($result, strlen($result) - 40) . "<br>";
        }
        
        //echo $errmsg . '<br>';        
    }
    
    function set_image_received_to_server($photo){        
        $token = $this->login_to_server();          
        // =====================================================================
        $client_info = $this->get_client_info();
        $url = "http://" . $client_info['ip_server'] . "image/set_images_received/" . str_replace(",", "_", $photo) . "?token=" . $token;
        
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER,true);
        curl_setopt($curl, CURLOPT_HEADER, false);
        $result = trim(curl_exec($curl));       
        
        $this->logout_from_server($token);
    }
}