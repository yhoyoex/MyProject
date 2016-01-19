
<?php
/*
 * GENERAL RULES
 * 1. system_administrator cannot be created / (updated to) using the generic user interface. 
 *    system_administrator can only be created through database manipulation
 * 2. user cannot change their own user level. 
 *    EG. user administrator cannot change its own level to supervisor
 *        user administrator can change another administrator's level to supervisor
 *        user administrator can change a normal user into an administrator
 *        user system_administrator can change an administrator into normal user
 * 3. only administrator can change user level
 * 
 */
$user = Session::get("user");
$privilege_hash = $this->user_login_level_privilege_hash;
if($user["user_login_level_name"] == "system_administrator") {

    // if existing level
    if($this->user_login_level_detail != array()){
        // // // // // // // // // 
        // existing LEVEL 
        // // // // // // // // // 
    ?>

    <table class="table table-condensed">
        <thead>
            <td>Login Level Name</td>
            <td>
                <strong><?= $this->user_login_level_detail["name"] ?></strong>
                <br>
            </td>
            <Td>
        </thead>
<?php
        foreach($this->available_controller_list as $c_name => $c){
            $group_checked = "";
            $group_disabled = "";
            if($privilege_hash[$c_name]["*"] == "true" || $privilege_hash["*"][0] == "true") $group_checked = "checked";
            if($this->user_login_level_detail["name"] == "system_administrator") $group_disabled = "disabled='true'";
            ?>
        <tr style="background-color: #89A; color: white">
            <td><?= $c_name ?></td>
            <td><input type="checkbox" <?= $group_checked ?> <?= $group_disabled ?> onchange="ajax_update_privilege_value('<?= $c_name . "/" . "*"?>', '<?= $this->user_login_level_detail["id"] ?>', this.checked); update_group_children('<?= $c_name ?>',  this.checked)"></td>
        </tr>
        <?php
        $group_counter = 0;
            foreach($c as $each) {
                $each_checked = "";
                if(
                        $privilege_hash[$c_name][$each] == "true" 
                        || $group_checked == "checked"
                    ) 
                    $each_checked = "checked";
                
                $each_disabled = "";
                if($group_checked == "checked") {
                    $each_disabled = "disabled='true'";
                }
                ?>
        <tr>
            <td><?= $each ?></td>
            <td><input id="<?= $c_name . "_" . $group_counter ?>" type="checkbox" <?= $each_checked ?> <?= $each_disabled ?> onchange="ajax_update_privilege_value('<?= $c_name . "/" . $each?>', '<?= $this->user_login_level_detail["id"] ?>', this.checked)"></td>
        </tr>
        
        <?php
            $group_counter ++;
            }
        }
?>
    </table>

    <script>
        function ajax_update_privilege_value(controller, login_level_id, check_value){

            $.post(
                '../login/ajax_change_user_login_level_privilege/', 
                {controller: controller, user_login_level_id: login_level_id, check_value: check_value},
                function(resp){
                    //console.log(resp);
                    //$("#console").html(resp);
                    if(resp.trim() == "success"){
                    } else {
                        alert("failed : " + controller + " - " + check_value);
                    }
                }
            );
        }
        function update_group_children(group_name, parent_check_value){
            var counter = 0;
            var new_value = true;
            var new_disabled = true;

            if(parent_check_value == false) new_value = false;
            if(parent_check_value == false) new_disabled = false;
            
            while(true){
                
                //console.log("updating " + "#"+group_name+"_"+counter + "  - "  + new_value + " - "  +new_disabled);
                if( $("#"+group_name+"_"+counter).length ) {
                    
                    $("#"+group_name+"_"+counter).removeAttr("checked");
                    $("#"+group_name+"_"+counter).removeAttr("disabled");
                    
                    $("#"+group_name+"_"+counter).prop("checked", new_value);
                    $("#"+group_name+"_"+counter).prop("disabled", new_disabled);
                } else {
                    break;
                }
                counter++;
            }
        }
    </script>

    <?php
    } else {
        
        // // // // // // // // // 
        // NEW LEVEL 
        // // // // // // // // // 
        ?>
    <form class="form-inline form-group-sm" id="new_user_login_level_form" onsubmit="create_new_user_login_level(); return false;" >
        <table class="table table-condensed">
            <thead>
                <td>Login Level Name</td>
                <td><input type="text" class="form-control" name="login_level_name" ></td>
            </thead>
            <tr>
                <td>
                    <input type="submit" id="login_user_save_button" class="form-control btn btn-info" value="Save" onclick="create_new_user_login_level(); return false;" >
                </td>
                <td>
                    <div id="new_user_login_level_loader" hidden="true">
                        <img src="../public/images/ajax-loader.gif">
                    </div>
                    <div id="new_user_login_level_result" class="text-danger">
                </td>
            </tr>
        </table>
    </form>
    <script>
        function create_new_user_login_level(){
            $("#new_user_login_level_loader").removeAttr("hidden");
            $("#new_user_login_level_result").html("");
            $("#new_user_login_level_loader").show();
            $("#login_user_save_button").hide();

            $.post(
                    '../login/ajax_save_new_user_login_level/', 
                    $('#new_user_login_level_form').serialize(),
                    function(resp){
                        console.log(resp);
                        $("#new_user_login_level_loader").hide();
                        if(resp.trim() == "success"){
                            alert("New Login Level Successfully");

                            window.location.href = window.location.href;
                            return;
                        } else {
                            $("#login_user_save_button").show();
                            $("#new_user_login_level_result").html(resp);
                        }
                    }
                );
        }
    </script>
    <?php
    }

}
?>

