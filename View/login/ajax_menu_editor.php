
<?php


$user = Session::get("user");
// this one is from the login level
$menu_hash = $this->user_login_menu_hash;

$login_menu_list = $this->available_menu_list;
$login_menu_lookup_hash = array();
foreach($login_menu_list as $l){
    $login_menu_lookup_hash[$l["id"]] = $l;
}
// this one is the available menu
$login_menu_hash = array();
foreach($login_menu_list as $l){
    $login_menu_hash[$l["parent_id"]][$l["id"]] = $l;
}

if($user["user_login_level_name"] == "system_administrator" || $user["user_login_level_name"] == "administrator") {

    // if existing level
    if($this->user_login_level_detail != array()){
        // // // // // // // // // 
        // existing LEVEL 
        // // // // // // // // // 
    ?>
<div id="console"></div>
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
        foreach($login_menu_hash as $parent_id => $child_list){
            $group_checked = "";
            if($menu_hash[$parent_id]["*"] == "true" || $menu_hash["*"][0] == "true") $group_checked = "checked";
            ?>
        <tr style="background-color: #89A; color: white">
            <td><?= $login_menu_lookup_hash[$parent_id]["title"] ?></td>
            <td><input type="checkbox" <?= $group_checked ?> onchange="ajax_update_menu_value('<?= $parent_id . "/" . "*"?>', '<?= $this->user_login_level_detail["id"] ?>', this.checked); update_group_children('<?= $parent_id ?>',  this.checked)"></td>
        </tr>
        <?php
        $group_counter = 0;
            foreach($child_list as $each) {
                if($each["id"] == $parent_id) continue;
                $each_checked = "";
                if(
                        $menu_hash[$parent_id][$each["id"]] == "true" 
                        || $group_checked == "checked"
                    ) 
                    $each_checked = "checked";
                
                $each_disabled = "";
                if($group_checked == "checked") {
                    $each_disabled = "disabled='true'";
                }
                ?>
        <tr>
            <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?= $each["title"] ?></td>
            <td><input id="<?= $parent_id . "_" . $group_counter ?>" type="checkbox" <?= $each_checked ?> <?= $each_disabled ?> onchange="ajax_update_menu_value('<?= $parent_id . "/" . $each["id"] ?>', '<?= $this->user_login_level_detail["id"] ?>', this.checked)"></td>
        </tr>
        
        <?php
            $group_counter ++;
            }
        }
?>
    </table>

    <script>
        function ajax_update_menu_value(menu, login_level_id, check_value){

            $.post(
                '../login/ajax_change_user_login_level_menu/', 
                {menu: menu, user_login_level_id: login_level_id, check_value: check_value},
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
    <form class="form-inline form-group-sm" id="new_menu_form" onsubmit="create_new_menu(); return false;" >
        <table class="table table-condensed">
            <thead>
                <td>Parent Menu</td>
                <td>
                    <select name="parent_id" class="form-control">
                        <option value="0"></option>
                        <?php
                        foreach($this->available_menu_list as $l){
                            if($l["id"] != $l["parent_id"]) continue;
                            ?>
                        <option value="<?= $l["id"] ?>"><?= $l["title"] ?></option>
                        <?php
                        }
                        ?>
                    </select>
                </td>
            </thead>
            <thead>
                <td>Menu Name</td>
                <td><input type="text" class="form-control" name="menu_name" ></td>
            </thead>
            <thead>
                <td>Link</td>
                <td><input type="text" class="form-control" name="menu_link" value="[ROOT]" ></td>
            </thead>
            <tr>
                <td>
                    <input type="submit" id="menu_save_button" class="form-control btn btn-info" value="Save" onclick="create_new_menu(); return false;" >
                </td>
                <td>
                    <div id="new_menu_loader" hidden="true">
                        <img src="../public/images/ajax-loader.gif">
                    </div>
                    <div id="new_menu_result" class="text-danger">
                </td>
            </tr>
        </table>
    </form>
    <script>
        function create_new_menu(){
            $("#new_menu_loader").removeAttr("hidden");
            $("#new_menu_result").html("");
            $("#new_menu_loader").show();
            $("#menu_save_button").hide();

            $.post(
                    '../login/ajax_create_new_user_login_menu/', 
                    $('#new_menu_form').serialize(),
                    function(resp){
                        console.log(resp);
                        $("#new_menu_loader").hide();
                        if(resp.trim() == "success"){
                            alert("New Menu Created Successfully");

                            window.location.href = window.location.href;
                            return;
                        } else {
                            $("#menu_save_button").show();
                            $("#new_menu_result").html(resp);
                        }
                    }
                );
        }
    </script>
    <?php
    }
}

?>

