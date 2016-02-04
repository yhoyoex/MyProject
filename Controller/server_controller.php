<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of client_access
 *
 * @author samamaju
 */
class server_controller extends Controller {
    function __construct() {
        parent::__construct();
    }
    
    public function get_token($user_key){
        $this->view->token = $this->model->get_token($user_key);
        $this->view->render('server/token',true);
    }

    public function get_preview_img($name){
        $url = $this->model->get_url_img($name);
        header("Content-Type: image/jpeg");
        //header('Content-type: application/pdf');
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_exec($ch);
        curl_close($ch);
    }
}
