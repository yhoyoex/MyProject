
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

// if existing user
if($this->user_login_detail != array()){
?>

<table class="table table-condensed">
    <tr>
        <td>Name</td>
        <td>
            <strong><?= $this->user_login_detail["name"] ?></strong>
        </td>
    </tr>
    <tr>
        <td>Initial</td>
        <td><strong><?= $this->user_login_detail["initial"] ?></strong></td>
    </tr>
    <tr>
        <td>Memo</td>
        <td><strong><?= $this->user_login_detail["memo"] ?></strong></td>
    </tr>
    <tr>
        <td>Username</td>
        <td><strong><?= $this->user_login_detail["username"] ?></strong></td>
    </tr>
    <tr>
        <td>Password</td>
        <td>
            <form class="form-inline" id="user_login_change_password_form">
                <input class="form-control" type="password" name="old_password" placeholder="old password" size="14">
                <input class="form-control" type="password" name="new_password" placeholder="new password" size="14">
                <!--input class="form-control btn-warning" type="submit" value="Change" onclick="$.post('ajax_update_password', $('#user_login_change_password_form').serialize()); return false;"-->
                <input class="form-control btn-warning" type="submit" value="Change" onclick="ajax_update_password(<?= $this->user_login_detail["id"] ?>); return false;">
                <div id="password_change_loader" hidden="true">
                    <img src="../public/images/ajax-loader.gif">
                </div>
                <div id="password_change_result" class="text-danger">
                </div>
            </form>
        </td>
    </tr>
    <?php
    if($user["id"] != $this->user_login_detail["id"] && ($user["user_login_level_name"] == "administrator" || $user["user_login_level_name"] == "system_administrator")){
    ?>
    <tr>
        <Td>User Level</Td>
        <td>
            <form class="form-inline" id="user_login_level_change_form">
                <strong id="form_user_login_level_name"><?= $this->user_login_detail["user_login_level_name"] ?></strong>
                <select class="form-control" style="margin-left: 20px" id="form_user_login_level_select" name="new_user_login_level_id" onchange="ajax_update_user_login_level(<?= $this->user_login_detail["id"] ?>); return false;">
                    <?php
                    foreach($this->user_login_level_list as $level){
                        $selected = "";
                        if($level["id"] == $this->user_login_detail["user_login_level_id"]) $selected = "selected";
                        if($level["name"] == "system_administrator") continue;
                        ?>
                    <option value="<?= $level["id"] ?>" <?= $selected ?>><?= $level["name"] ?></option>
                    <?php
                    }
                    ?>
                </select>
                <div id="user_login_level_change_loader" hidden="true">
                    <img src="../public/images/ajax-loader.gif">
                </div>
                <div id="user_login_level_change_result" class="text-danger">
            </form>
        </td>
    </tr>
    <?php
    }
    ?>
</table>

<script>
    function ajax_update_password(id){
        $("#password_change_loader").removeAttr("hidden");
        $("#password_change_result").html("");
        $("#password_change_loader").show();
        $.post(
                '../login/ajax_update_password/'+id, 
                $('#user_login_change_password_form').serialize(),
                function(resp){
                    //console.log(resp);
                    $("#password_change_loader").hide();
                    if(resp.trim() == "success"){
                        alert("Password changed successfully");
                    } else {
                        $("#password_change_result").html("failed");
                    }
                }
            );
    }
    
    function ajax_update_user_login_level(id){
        $("#user_login_level_change_loader").removeAttr("hidden");
        $("#user_login_level_change_result").html("");
        $("#user_login_level_change_loader").show();
        $.post(
                '../login/ajax_update_user_login_level/'+id, 
                $('#user_login_level_change_form').serialize(),
                function(resp){
                    $("#user_login_level_change_loader").hide();
                    if(resp.trim() == "success"){
                        alert("User level changed successfully");
                        $("#form_user_login_level_name").html($("#form_user_login_level_select").find(":selected").html());
                        
                        // this is a hack
                        // since this View is an ajax call, it has the access to its caller
                        $.get(
                                '../login/ajax_get_user_login_row/'+id,
                                Object(),
                                function(resp){
                                    $("#user_login_list_"+id).html(resp);
                                }
                            )
                        
                    } else {
                        $("#user_login_level_change_result").html("failed");
                    }
                }
            );
    }    
</script>

<?php
} else {
    ?>
<form class="form-inline form-group-sm" id="new_user_form">
    <table class="table table-condensed">
        <thead>
            <td>Name</td>
            <td><input type="text" class="form-control" name="login_user_name" ></td>
        </thead>
        <tr>
            <td>Initial</td>
            <td><input type="text" class="form-control" name="login_user_initial" ></td>
        </tr>
        <tr>
            <td>Memo</td>
            <td><input type="text" class="form-control" name="login_user_memo" ></td>
        </tr>
        <tr>
            <td>Username</td>
            <td><input type="text" class="form-control" name="login_user_username" ></td>
        </tr>
        <tr>
            <td>
                <input type="button" id="login_user_save_button" class="form-control btn btn-info" value="Save" onclick="create_new_user(); return false;">
            </td>
            <td>
                <div id="new_user_loader" hidden="true">
                    <img src="../public/images/ajax-loader.gif">
                </div>
                <div id="new_user_result" class="text-danger">
            </td>
        </tr>
    </table>
</form>
<script>
    function create_new_user(){
        $("#new_user_loader").removeAttr("hidden");
        $("#new_user_result").html("");
        $("#new_user_loader").show();
        $("#login_user_save_button").hide();
        
        $.post(
                '../login/ajax_save_new_user/', 
                $('#new_user_form').serialize(),
                function(resp){
                    $("#new_user_loader").hide();
                    if(resp.trim() == "success"){
                        alert("New User Created Successfully");

                        window.location.href = window.location.href;
                        return;
                    } else {
                        $("#login_user_save_button").show();
                        $("#new_user_result").html(resp);
                    }
                }
            );
    }
</script>
<?php
}
?>

