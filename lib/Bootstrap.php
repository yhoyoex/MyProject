<?php

class Bootstrap {

    function __construct() {

        $url = isset($_GET['url']) ? $_GET['url'] : null;
        $url = rtrim($url, '/');
        $full_url = $url;
        $url = explode('/', $url);

        $login_assist = new LoginAssist();
        if( 
                $login_assist->logged_in() == null 
                && (isset($url[0]) && !($url[0] == "login" && !isset($url[1])))
                && $full_url != "login/run"
            )
        {
            $login_url = URL."login";
            echo "<META HTTP-EQUIV=\"Refresh\" Content=\"0; URL=".$login_url."\">";    
            exit;
        }

        if (empty($url[0])) {
	   $url[0] = "index";
//            require 'Controller/index_controller.php';
//            $controller = new Index_Controller();
//            $controller->index();
//            return false;
        }

        // checking for access privilege
	$privilege = false;

	if(!isset($url[1])){
 	    $privilege = $login_assist->get_access_privilege($url[0], "");
	} else {
 	    $privilege = $login_assist->get_access_privilege($url[0], $url[1]);
	}

        // getting the menu items
        $user = Session::get("user");
        $available_menu = $login_assist->get_available_menu_list();
        $login_menu_lookup_hash = array();
        $login_menu_hash = array();
        foreach($available_menu as $l){
            $login_menu_lookup_hash[$l["id"]] = $l;
        }
        foreach($available_menu as $l){
            if($l["id"] == $l["parent_id"])
                $login_menu_hash[$l["id"]] = array();
        }
        foreach($available_menu as $l){
            if(isset($login_menu_hash[$l["parent_id"]])){
                $login_menu_hash[$l["parent_id"]][$l["id"]] = $l;
            }
        }

        $allowed_menu = $user["user_login_level_menu"];
        $allowed_menu_hash = $login_assist->translate_login_menu($allowed_menu);
        $final_menu = array();
        
        if($allowed_menu_hash['*'][0] == "true") { 
            $final_menu = $login_menu_hash;
        } else {
            foreach($login_menu_hash as $parent_id => $children){
                $temp = array();
                
                if($allowed_menu_hash[$parent_id]["*"] == "true"){
                    $temp = $children;
                } else {
                    foreach($children as $child_id => $child){
                        if($allowed_menu_hash[$parent_id][$child_id] == "true"){
                            $temp[$child_id] = $child;
                        }
                    }
                }
                if(count($temp) != 0){
                    $temp[$parent_id] = $login_menu_lookup_hash[$parent_id];
                    $final_menu[$parent_id] = $temp;
                }
            }
        }
        
        // getting the controller
        $file = 'Controller/' . $url[0] . '_controller.php';
        if (file_exists($file)) {
            require $file;
        } else {
            $this->error("Invalid Controller");
            
        }
        $controller_name = $url[0]."_Controller";

        $controller = new $controller_name;
        $controller->loadModel($url[0]);
        $controller->view->application_menu = $final_menu;

        // calling methods
        if (isset($url[4])) {
            if (method_exists($controller, $url[1])) {
                if($privilege) {
                    $controller->{$url[1]}($url[2], $url[3], $url[4]);
                } else {
                    $this->error("Bad privilege");
                }
            } else {
                $this->error("Invalid Controller (4)");
            }
        }
        else if (isset($url[3])) {
            if (method_exists($controller, $url[1])) {
                if($privilege) {
                    $controller->{$url[1]}($url[2], $url[3]);
                } else {
                    $this->error("Bad privilege");
                }
            } else {
                $this->error("Invalid Controller (3)");
            }
        }
        if (isset($url[2])) {
            if (method_exists($controller, $url[1])) {
                if($privilege) {
                    $controller->{$url[1]}($url[2]);
                } else {
                    $this->error("Bad privilege");
                }
            } else {
                $this->error("Invalid Controller (2)");
            }
        } else {
            if (isset($url[1])) {
                if (method_exists($controller, $url[1])) {
                    if($privilege) {
                        $controller->{$url[1]}();
                    } else {
                        $this->error("Bad privilege");
                    }
                } else {
                    $this->error("Invalid Controller (1)");
                }
            } else {
                if($privilege) {
                    $controller->index();
                } else {
                    $this->error("Bad privilege");
                }
            }
        }
        
    }

    public function error( $error_msg = false) {
        echo "ERROR in bootstrap : " . $error_msg . "<br>";
    }

}