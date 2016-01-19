<?php

class LoginAssist {

    function __construct() {
        $this->db = new Database();
    }

    function logged_in(){
        if(Session::get("TIMEOUT") + 20 * 60 < time()){
            Session::set("username", "");
            Session::set("password", "");
            Session::set("avatar", "");
            Session::set("user", "");
        }
                
        $username = Session::get("username");
        $password = Session::get("password");
        
        if($username == null || $password == null) return null;
        

        $query = "select 
                        user_login.*,
                        user_login_level.name user_login_level_name,
                        user_login_level.privilege user_login_level_privilege,
                        user_login_level.menu user_login_level_menu
                    from 
                        user_login
                        left join user_login_level on user_login.user_login_level_id = user_login_level.id
                    where username = :username and password = :password";

        $user = $this->db->query($query, Array(":username" => $username, ":password" => $password));

        if(count($user) < 1){
            $this->force_logout();
            return null;
        }

        $user = $user[0];
        Session::set("user", $user);
        Session::set("TIMEOUT", time());

        return $user;
    }
        
    function force_logout(){
            Session::set("username", null);
            Session::set("password", null);
            Session::set("avatar", null);
            Session::set("user", null);
    }
    
    function get_user_login_list(){
        $sql = "select 
                        user_login.*,
                        user_login_level.name user_login_level_name,
                        user_login_level.privilege user_login_level_privilege
                    from
                        user_login
                        left join user_login_level on user_login.user_login_level_id = user_login_level.id
                    order by 
                        user_login.name
                        ";
        $res = $this->db->query($sql);
        return $res;
    }
    
    function get_user_login_level_list(){
        $sql = "select
                        *
                    from
                        user_login_level
                    order by 
                        sort
                ";
        $res = $this->db->query($sql);

        return $res;
    }
    
    function get_available_controller_list(){
        $list = array();
        
        $dir_content = scandir("Controller");
        foreach($dir_content as $dir){
            $by_dir = array();
            
            if($dir == "." || $dir == ".." || substr($dir, -4) != ".php")                
                continue;
            
            $handle = fopen("Controller/".$dir, "r");
            while(($line = fgets($handle)) != false){
                $line = trim($line);
                $detection_skip = "function __construct()";
                $detection_1 = "function ";
                $detection_2 = "public function ";
                
                if(substr($line, 0, strlen($detection_skip)) == $detection_skip) {
                    continue;
                } else if(substr($line, 0, strlen($detection_1)) == $detection_1) {
                    $line = fgets($handle);
                    $line = trim($line);
                    if(substr($line, 0, 2) == "//") {
                        $by_dir[] = str_replace("// ", "", $line);
                    }
                } else if(substr($line, 0, strlen($detection_2)) == $detection_2) {
                    $line = fgets($handle);
                    $line = trim($line);
                    if(substr($line, 0, 2) == "//") {
                        $by_dir[] = str_replace("// ", "", $line);
                    }
                }
            }
            fclose($handle);
            
            $list[str_replace("_controller.php", "", $dir)] = $by_dir;
        } 
        return $list;
    }
    
    function get_access_privilege($controller, $method){
        $user = Session::get("user");
        if($method == "") $method = "Index";

        // defaults
        if($controller == "login" && ($method == "Index" || $method == "run")) return true;

        // get the method code
        $handle = fopen("Controller/".$controller."_controller.php", "r");
        $method_found = false;
        $method_code = "";
        while(($line = fgets($handle)) != false){
            $line = trim($line);
            $detection_1 = "function " . $method;
            $detection_2 = "public function " . $method;

            // when you meet a function, check if the function is the right one
            if(substr($line, 0, strlen($detection_1)) == $detection_1 || substr($line, 0, strlen($detection_2)) == $detection_2) {
                $line = fgets($handle);
                $line = trim($line);
                if(substr($line, 0, 2) == "//") {
                    $method_code = str_replace("// ", "", $line);
                }
                $method_found = true;
                break;
            } 
        }
        fclose($handle);

        // check if the method is allowed
        if($method_found) {
            $privilege = $user["user_login_level_privilege"];
            $privilege = $this->translate_user_privilege($privilege);
//            printr($privilege);
            if(
                $privilege["*"][0] == "true" || 
                    (
                        isset($privilege[$controller])
                        && 
                            ($privilege[$controller][$method_code] == "true" || $privilege[$controller]["*"] == "true")
                    )
                ){
                return true;
            }
        }

        return false;
    }
    
    function translate_user_privilege($privilege){

        if(trim($privilege) == "") return array();
        
        $privilege_list = explode("|", $privilege);

        $hashed_privilege = array();
        foreach($privilege_list as $each_privilege){
            $temp = split("=", trim($each_privilege));
            $name_split = split("/", trim($temp[0]));
            if(!isset($name_split[1]) || $name_split[1] == "") $name_split[1] = "0";

            $hashed_privilege[$name_split[0]][$name_split[1]] = trim($temp[1]);
        }
        return $hashed_privilege;
    }
    
    function get_user_login($id){
        $sql = "select 
                        user_login.*,
                        user_login_level.name user_login_level_name,
                        user_login_level.privilege user_login_level_privilege
                    from
                        user_login
                        left join user_login_level on user_login.user_login_level_id = user_login_level.id
                    where
                        user_login.id = :id
                        ";
        $res = $this->db->query($sql, array(":id" => $id));
        return $res[0];
    }
    
    function update_user_password($id, $old_password, $new_password){
        $sql = "select * from user_login where id = :id and password = :old_password";
        $res = $this->db->query($sql, array(":id"=>$id, ":old_password"=>$old_password));
        if(count($res) == 1) {
            $update = $this->execute_update_user_password($id, $new_password);
            return $update;
        }
        else 
            return false;
    }
    
    // this is a very special function
    function force_update_user_password($id, $new_password){
        $user = Session::get("user");
        if($user["user_login_level_name"] == "system_administrator" || $user["user_login_level_name"] == "administrator"){
            $update = $this->execute_update_user_password($id, $new_password);
            return $update;
        }
        return false;
    }
    
    private function execute_update_user_password($id, $new_password){
        $sql = "update user_login set password = :password where id = :id";
        $res = $this->db->query($sql, array(":password" => $new_password, ":id" => $id));
        return true;
    }
    
    function update_user_login_level($id, $new_login_level_id){
        /*
         * GENERAL RULES
         * 1. system_administrator cannot be created / (updated to) using the generic user interface. 
         *    system_administrator can only be created through database manipulation
         * 2. user cannot change their own user level. 
         *    EG. user administrator cannot change its own level to supervisor
         *        user administrator can change another administrator's level to supervisor
         *        user administrator can change a supervisor into a normal user
         *        user administrator can change a normal user into an administrator
         *        user system_administrator can change an administrator into normal user
         * 3. only administrator can change user level
         * 
         */
        
        // checking the user's user level
        $user = Session::get("user");
        $sql = "select user_login_level.name user_login_level_name from user_login left join user_login_level on user_login.user_login_level_id = user_login_level.id where user_login.id = :id";
        $res = $this->db->query($sql, array(":id"=>$user["id"]));
        if(count($res) == 1){
            $level_name = $res[0]["user_login_level_name"];
            if($level_name == "system_administrator" || $level_name == "administrator") {
                
            } else {
                // user is not allowed to change user level
                return false;
            }
        }

        // user cannot change his/her own level
        if($user["id"] == $id) return false;
        
        // checking the target user level
        $sql = "select * from user_login_level where id = :id";
        $res = $this->db->query($sql, array(":id" => $new_login_level_id));
        if(count($res) == 1){
            if($res[0]["name"] == "system_administrator") return false;
            
        }
        
        return $this->execute_update_user_login_level($id, $new_login_level_id);
    }
    
    private function execute_update_user_login_level($id, $new_login_level_id){
        $sql = "update user_login set user_login_level_id = :new_id where id = :id";
        $res = $this->db->query($sql, array(":new_id" => $new_login_level_id, ":id" => $id));
        return true;
    }
    
    public function validate_new_user($save_data){
        // checking name
        $sql = "select * from user_login where LOWER(name) = :name";
        $res = $this->db->query($sql, array(":name"=>  strtolower($save_data["name"])));
        if(count($res) != 0) return "Name already exists";
        
        // checking initial
        $sql = "select * from user_login where LOWER(initial) = :initial";
        $res = $this->db->query($sql, array(":initial"=>strtolower($save_data["initial"])));
        if(count($res) != 0) return "Initial already exists";
        
        // checking username
        $sql = "select * from user_login where LOWER(username) = :username";
        $res = $this->db->query($sql, array(":username"=>strtolower($save_data["username"])));
        if(count($res) != 0) return "Username already exists";
        
        if(trim($save_data["name"]) == ""){
            return "Invalid Name";
        }
        
        if(trim($save_data["initial"]) == ""){
            return "Invalid Initial";
        }
        
        if(trim($save_data["username"]) == ""){
            return "Invalid Username";
        }
        
        return "";
    }
    
    public function save_new_user($save_data){
        // validate again. Just because
        $validate = $this->validate_new_user($save_data);
        if($validate != "") return "validation error";
        
        $user = Session::get("user");
        if($user["user_login_level_name"] == "system_administrator" || $user["user_login_level_name"] == "administrator"){
            // inserting the new user
            $sql = "insert into user_login (id, created, name, username, password, memo, initial, privilege, user_login_level_id) values 
                        (0, now(), :name, :username, :password, :memo, :initial, :privilege, :user_login_level_id)";
            $params = array(
                ":name" => $save_data["name"],
                ":username" => $save_data["username"],
                ":password" => md5(rand(10, 100)),
                ":memo" => $save_data["memo"],
                ":initial" => $save_data["initial"],
                ":privilege" => "",
                ":user_login_level_id" => "0"
            );
            $this->db->query($sql, $params);
            $new_id = $this->db->lastInsertId();
            
            return true;
        }
    }
    
    public function validate_new_user_login_level($save_data){
        // checking name
        $sql = "select * from user_login_level where LOWER(name) = :name";
        $res = $this->db->query($sql, array(":name"=>  strtolower($save_data["name"])));
        if(count($res) != 0) return "Name already exists";
        
        if(trim($save_data["name"]) == ""){
            return "Invalid Name";
        }
        
        return "";
    }
    
    public function save_new_user_login_level($save_data){
        // validate again. Just because
        $validate = $this->validate_new_user_login_level($save_data);
        if($validate != "") return "validation error";
        
        $user = Session::get("user");
        if($user["user_login_level_name"] == "system_administrator"){
            // getting the max sort number
            $sql = "select max(sort)+ 1 next_sort from user_login_level";
            $res = $this->db->query($sql);
            $next = $res[0]["next_sort"];
            if((int)$next == 0){ $next = "1"; }
            
            $sql = "insert into user_login_level (id, created, name, privilege, sort) values (0, now(), :name, '', :next_sort)";
            $this->db->query($sql, array(":name"=>$save_data["name"], ":next_sort"=>$next));
            return true;
        }
    }
        
    function get_user_login_level($id){
        $sql = "select 
                        *
                    from
                        user_login_level
                    where
                        id = :id
                        ";
        $res = $this->db->query($sql, array(":id" => $id));
        return $res[0];
    }
    
    function change_user_login_level_privilege($controller, $id, $value){
        $sql = "select * from user_login_level where id = :id";
        $res = $this->db->query($sql, array(":id"=>$id));
        $privilege = $res[0]["privilege"];
        
        $privilege_hash = $this->translate_user_privilege($privilege);
        
        $controller_split = split("/", $controller);
                
        if($controller_split[1] == "*") {
            if($value == "true") {
                $privilege_hash[$controller_split[0]] = array("*" => "true");
            } else {
                unset($privilege_hash[$controller_split[0]]);
            }
        } else {
            if($privilege_hash[$controller_split[0]]["*"] == "true") return false;
            
            if($value == "true") {
                $privilege_hash[$controller_split[0]][$controller_split[1]] = "true";
            } else {
                unset($privilege_hash[$controller_split[0]][$controller_split[1]]);
            }
        }
        
        $flattened_privilege = $this->flatten_privilege($privilege_hash);
        $sql = "update user_login_level set privilege = :privilege where id = :id";
        $this->db->query($sql, array(":privilege" => $flattened_privilege, ":id" => $id));
        return true;
    }
    
    function flatten_privilege($privilege){
        $privilege_list = array();
        foreach($privilege as $controller => $methods){
            if($privilege[$controller]["*"] == "true"){
                $privilege_list[] = $controller."/*=true";
            } else {
                foreach($methods as $m => $v){
                    $privilege_list[] = $controller."/".$m."=true";
                }
            }
        }
        return implode("|", $privilege_list);
    }
    
    function get_available_menu_list(){
        $sql = "select * from user_login_menu order by sort";
        $res = $this->db->query($sql);
        return $res;
    }
    
    function translate_login_menu($menu){

        if(trim($menu) == "") return array();
        
        $menu_list = explode("|", $menu);

        $hashed_menu = array();
        foreach($menu_list as $each_menu){
            $temp = split("=", trim($each_menu));
            $name_split = split("/", trim($temp[0]));
            if(!isset($name_split[1]) || $name_split[1] == "") $name_split[1] = "0";

            $hashed_menu[$name_split[0]][$name_split[1]] = trim($temp[1]);
        }
        return $hashed_menu;
        
    }
    
    function change_user_login_level_menu($menu, $id, $value){
        $sql = "select * from user_login_level where id = :id";
        $res = $this->db->query($sql, array(":id"=>$id));
        $original_menu = $res[0]["menu"];
        
        $menu_hash = $this->translate_login_menu($original_menu);
        
        $menu_split = split("/", $menu);
                
        if($menu_split[1] == "*") {
            if($value == "true") {
                $menu_hash[$menu_split[0]] = array("*" => "true");
            } else {
                unset($menu_hash[$menu_split[0]]);
            }
        } else {
            if($menu_hash[$menu_split[0]]["*"] == "true") return false;
            
            if($value == "true") {
                $menu_hash[$menu_split[0]][$menu_split[1]] = "true";
            } else {
                unset($menu_hash[$menu_split[0]][$menu_split[1]]);
            }
        }
        
        $flattened_menu = $this->flatten_privilege($menu_hash);
        $sql = "update user_login_level set menu = :menu where id = :id";
        $this->db->query($sql, array(":menu" => $flattened_menu, ":id" => $id));
        return true;
    }
    
    function create_user_login_menu($name, $parent_id, $link){
        if(trim($name) == "") return "invalid name";
        if(trim($link) == "") return "invalid link";
        
        // get the sort
        // check if its parent?
        $parent_id = (int)$parent_id;
        $sort_index = "0";
        if($parent_id != 0){
            $sql = "select max(sort) max_sort from user_login_menu where parent_id = :parent_id";
            $res = $this->db->query($sql, array(":parent_id" => $parent_id));
            if(trim($res[0]["max_sort"]) == "") {
                $sort_index = "1";
            } else {
                $sort_index = (int)$res[0]["max_sort"] + 1;
            }
        } else {
            $sql = "select max(sort) max_sort from user_login_menu where id = parent_id";
            $res = $this->db->query($sql);
            if(trim($res[0]["max_sort"]) == "") {
                $sort_index = "1";
            } else {
                $sort_index = (int)$res[0]["max_sort"] + 1;
            }
        }
        
        $sql = "insert into user_login_menu (id, created, title, parent_id, sort, link) 
                    VALUES (0, now(), :name, :parent_id, :sort, :link)";
        $res = $this->db->query($sql, array(":name" => $name, ":parent_id" => $parent_id, ":sort" => $sort_index, ":link" => $link));

        
        $this->db->query("update user_login_menu set parent_id = id where parent_id = 0");
        
        return true;
    }
    
    function delete_user_login_menu($id){
        $sql = "select parent_id from user_login_menu where id = :id";
        $res = $this->db->query($sql, array(":id"=>$id));
        
        if($res[0]["parent_id"] == "$id") {
            $sql = "select count(*) count from user_login_menu where parent_id = :id";
            $res = $this->db->query($sql, array(":id"=>$id));
            if($res[0]["count"] != "1") return;
        }
        
        $sql = "delete from user_login_menu where id = :id";
        $this->db->query($sql, array(":id"=>$id));
    }
    function slide_user_login_menu($id, $direction){
        if((int)$id == 0) return;
        
        $direction_sign = "";
        $direction_order = "";
        if($direction == "1") {
            $direction_sign = ">";
            $direction_order = "asc";
        } else if($direction == "-1") {
            $direction_sign = "<";
            $direction_order = " desc";
        } else {
            return;
        }
        
        $switch_source = null;
        $switch_target = null;
        
        $is_parent = true;
        $sql = "select * from user_login_menu where id = :id";
        $res = $this->db->query($sql, array(":id"=>$id));
        $switch_source = $res[0];
        if($switch_source["parent_id"] != $id){
            $is_parent = false;
        }
        
        if($is_parent){
            $sql = "select * from user_login_menu where parent_id = id and sort $direction_sign :sort order by sort $direction_order limit 1";
            echo ":sql1 : " . $sql;
            $res = $this->db->query($sql, array(":sort" => $switch_source["sort"]));
            if(count($res) == 0) return;
            $switch_target = $res[0];
        } else {
            $sql = "select * from user_login_menu where parent_id != id and parent_id = :parent_id and sort $direction_sign :sort order by sort $direction_order limit 1";
            echo ":sql2 : " . $sql;
            $res = $this->db->query($sql, array(":parent_id" => $switch_source["parent_id"], ":sort" => $switch_source["sort"]));
            if(count($res) == 0) return;
            $switch_target = $res[0];
        }
        
        $sql = "update user_login_menu set sort = :sort where id = :id";
        $this->db->query($sql, array(":sort" => $switch_target["sort"], ":id" => $switch_source["id"]));
        $this->db->query($sql, array(":sort" => $switch_source["sort"], ":id" => $switch_target["id"]));
        printr($switch_source);
        printr($switch_target);
    }
}