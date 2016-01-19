<?php

class Login_controller extends Controller {

    function __construct() {
        parent::__construct();
    }

    public function Index(){ 
        /* LOGIN PAGE */
        $login_assist = new LoginAssist();
        $login_assist->force_logout();
        
        $this->view->render("login/index", true);
    }
        
    public function run(){
        /* PROCESS LOGIN */
        if(isset($_POST["login_username"]) && isset($_POST["login_password"])){
            Session::set("username", $_POST["login_username"]);
            Session::set("password", md5($_POST["login_password"]));
            Session::set("TIMEOUT", time());
        }
        header("Location: ".URL."index/");
    }    
    
    public function viewer(){
        // VIEW LOGIN LIST
        $this->view->page_header = "Settings" ;
        $this->view->panel_title = "Login Manager" ;
        $this->view->user_login_list = $this->model->get_user_login_list();
        $this->view->user_login_level_list = $this->model->get_user_login_level_list();
        
        $this->view->available_menu_list = $this->model->get_available_menu_list();
        $this->view->available_controller_list = $this->model->get_available_controller_list();

        $this->view->render("login/viewer");
    }
    
    public function ajax_get_user_login_by_id($id){
        // (ajax) VIEW LOGIN DETAIL
        $this->view->user_login_detail = $this->model->get_user_login($id);
        $this->view->user_login_level_list = $this->model->get_user_login_level_list();
        
        $this->view->render("login/ajax_editor", true);
    }
    public function ajax_update_password($id){
        // (ajax) UPDATE USER PASSWORD
        $res = $this->model->update_user_password($id, md5($_POST["old_password"]), md5($_POST["new_password"]));
        if($res == true){
            echo "success";
        } else {
            echo "faileds";
        }
    }
    public function ajax_update_user_login_level($id){
        // (ajax) UPDATE USER LEVEL
        $res = $this->model->update_user_login_level($id, $_POST["new_user_login_level_id"]);
        
        if($res == true){
            echo "success";
        } else {
            echo "failed";
        }
    }
    public function ajax_get_user_login_row($id){
        // (ajax) READ USER LOGIN ROW
        $this->view->user_login_detail = $this->model->get_user_login($id);
        $this->view->render("login/ajax_login_row", true);
    }
    public function ajax_save_new_user(){
        // (ajax) SAVE NEW USER
        $res = $this->model->save_new_user($_POST);

        if(gettype($res) == "boolean" && $res == true){
            echo "success";
        } else {
            echo $res;
        }
    }
    
    public function ajax_get_user_login_level_by_id($id){
        // (ajax) VIEW LOGIN LEVEL DETAIL
        $this->view->user_login_level_detail = $this->model->get_user_login_level($id);
        $this->view->user_login_level_privilege_hash = $this->model->get_user_login_level_privilege_hash($id);
        $this->view->available_controller_list = $this->model->get_available_controller_list();

        $this->view->render("login/ajax_level_editor", true);
    }
    
    public function ajax_save_new_user_login_level(){
        // (ajax) SAVE NEW LOGIN LEVEL
        $res = $this->model->save_new_user_login_level($_POST);

        if(gettype($res) == "boolean" && $res == true){
            echo "success";
        } else {
            echo $res;
        }
    }
    
    public function ajax_change_user_login_level_privilege(){
        // (ajax) UPDATE LOGIN LEVEL PRIVILEGE
        $res = $this->model->change_user_login_level_privilege($_POST);
        
        if(gettype($res) == "boolean" && $res == true){
            echo "success";
        } else {
            echo $res;
        }
    }
    
    public function ajax_get_user_login_menu_by_id($id){
        // (ajax) VIEW LOGIN MENU DETAIL
        $this->view->user_login_level_detail = $this->model->get_user_login_level($id);
        $this->view->user_login_menu_hash = $this->model->get_user_login_menu_hash($id);
        $this->view->available_menu_list = $this->model->get_available_menu_list();

        $this->view->render("login/ajax_menu_editor", true);
    }

    public function ajax_change_user_login_level_menu(){
        // (ajax) UPDATE LOGIN MENU
        $res = $this->model->change_user_login_level_menu($_POST);
        
        if(gettype($res) == "boolean" && $res == true){
            echo "success";
        } else {
            echo $res;
        }
    }
    
    public function ajax_create_new_user_login_menu(){
        // (ajax) CREATE NEW MENU
        $name = $_POST["menu_name"];
        $link = $_POST["menu_link"];
        $parent_id = $_POST["parent_id"];
        
        $res = $this->model->crate_user_login_menu($name, $parent_id, $link);
        
        if(gettype($res) == "boolean" && $res == true){
            echo "success";
        } else {
            echo $res;
        }
    }
   
    public function ajax_delete_user_login_menu($id){
        // (ajax) DELETE MENU
        $this->model->delete_user_login_menu($id);
    }
    
    public function ajax_slide_user_login_menu($id, $direction){
        // (ajax) ADJUST SORT MENU
        $this->model->slide_user_login_menu($id, $direction);
    }
    
}