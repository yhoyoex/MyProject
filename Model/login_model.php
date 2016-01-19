<?php

class Login_Model extends Model{

    function __construct() {
        parent::__construct();
        $this->login_assist = new LoginAssist();
    }    
    
    function get_user_login_list(){
        $list = $this->login_assist->get_user_login_list();
        return $list;
    }
    
    function get_available_controller_list(){
        $list = $this->login_assist->get_available_controller_list();
        return $list;
    }
    
    function get_user_login_level_list(){
        $list = $this->login_assist->get_user_login_level_list();
        return $list;
    }
    function get_user_login($id){
        $res = $this->login_assist->get_user_login($id);
        return $res;
    }
    function update_user_password($id, $old_password, $new_password){
        $user = Session::get("user");
        if($user["user_login_level_name"] == "system_administrator" || $user["user_login_level_name"] == "administrator"){
            $res = $this->login_assist->force_update_user_password($id, $new_password);
        } else {
            $res = $this->login_assist->update_user_password($id, $old_password, $new_password);
        }
        return $res;
    }
    function update_user_login_level($id, $new_user_login_level_id){
        return $this->login_assist->update_user_login_level($id, $new_user_login_level_id);
    }
    function save_new_user($save_data){
        $save_data_internal = array(
            "name" => trim($save_data["login_user_name"]),
            "initial" => trim($save_data["login_user_initial"]),
            "memo" => trim($save_data["login_user_memo"]),
            "username" => trim($save_data["login_user_username"]),
        );
        
        $validate_result_string = $this->login_assist->validate_new_user($save_data_internal);
        if($validate_result_string != "") return $validate_result_string;

        $insert_result = $this->login_assist->save_new_user($save_data_internal);
        return $insert_result;
    }
    
    function save_new_user_login_level($save_data){
        $save_data_internal = array(
            "name" => trim($save_data["login_level_name"]),
        );
        
        $validate_result_string = $this->login_assist->validate_new_user_login_level($save_data_internal);
        if($validate_result_string != "") return $validate_result_string;

        $insert_result = $this->login_assist->save_new_user_login_level($save_data_internal);
        return $insert_result;
    }
    function get_user_login_level($id){
        $res = $this->login_assist->get_user_login_level($id);
        return $res;
    }
    function get_user_login_level_privilege_hash($id){
        $res = $this->login_assist->get_user_login_level($id);
        $privilege = $this->login_assist->translate_user_privilege($res["privilege"]);
        return $privilege;
    }
    function change_user_login_level_privilege($update_data) {
        $res = $this->login_assist->change_user_login_level_privilege($update_data["controller"], $update_data["user_login_level_id"], $update_data["check_value"]);
        return $res;
    }
    
    function get_available_menu_list(){
        $res = $this->login_assist->get_available_menu_list();
        return $res;
    }
    
    function get_user_login_menu_hash($id){
        $res = $this->login_assist->get_user_login_level($id);
        $menu_hash = $this->login_assist->translate_login_menu($res["menu"]);
        return $menu_hash;
    }
    function change_user_login_level_menu($update_data) {
        $res = $this->login_assist->change_user_login_level_menu($update_data["menu"], $update_data["user_login_level_id"], $update_data["check_value"]);
        return $res;
    }
    function crate_user_login_menu($name, $parent_id, $link){
        $res = $this->login_assist->create_user_login_menu($name, $parent_id, $link);
        return $res;
    }
    function delete_user_login_menu($id){
        $this->login_assist->delete_user_login_menu($id);
    }
    function slide_user_login_menu($id, $direction){
        $this->login_assist->slide_user_login_menu($id, $direction);
    }
}
