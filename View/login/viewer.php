<?php

// making the privilege list looks nicer
$login_level_list = $this->user_login_level_list;
foreach($login_level_list as $index => $l){
    // PRIVILEGE
    $privilege = $l["privilege"];
    $privilege_list = explode("|", $privilege);
    
    $hashed_privilege = array();
    foreach($privilege_list as $each_privilege){
        $temp = split("=", trim($each_privilege));
        $name_split = split("/", trim($temp[0]));
        if(!isset($name_split[1]) || $name_split[1] == "") $name_split[1] = "0";
        
        $hashed_privilege[$name_split[0]][$name_split[1]] = trim($temp[1]);
    }
    $login_level_list[$index]["hashed_privilege"] = $hashed_privilege;
    
    // MENU
    $menu = $l["menu"];
    $menu_list = explode("|", $menu);
    
    $hashed_menu = array();
    foreach($menu_list as $each_menu){
        $temp = split("=", trim($each_menu));
        $name_split = split("/", trim($temp[0]));
        if(!isset($name_split[1]) || $name_split[1] == "") $name_split[1] = "0";
        
        $hashed_menu[$name_split[0]][$name_split[1]] = trim($temp[1]);
    }
    $login_level_list[$index]["hashed_menu"] = $hashed_menu;
}

// making the menu list looks nicer
$login_menu_list = $this->available_menu_list;
$login_menu_lookup_hash = array();
$login_menu_hash = array();
foreach($login_menu_list as $l){
    $login_menu_lookup_hash[$l["id"]] = $l;
}
foreach($login_menu_list as $l){
    if($l["id"] == $l["parent_id"])
        $login_menu_hash[$l["id"]] = array();
}
foreach($login_menu_list as $l){
    if(isset($login_menu_hash[$l["parent_id"]])){
        $login_menu_hash[$l["parent_id"]][$l["id"]] = $l;
    }
}

$user = Session::get("user");
?>



<div>

  <!-- Nav tabs -->
  <ul class="nav nav-pills" data-toggle="tab">
    <li role="presentation" class="active"><a href="#user_login" aria-controls="user_login" role="tab" data-toggle="tab">User Login</a></li>
    <li role="presentation"><a href="#login_level" aria-controls="login_level" role="tab" data-toggle="tab">User Levels</a></li>
    <li role="presentation"><a href="#login_menu" aria-controls="login_menu" role="tab" data-toggle="tab">Menu</a></li>
  </ul>

  <!-- Tab panes -->
  <div class="tab-content">
    <div class="tab-pane fade active in" id="user_login">
    <div class="row">
    <div class="col-md-12"><!-- end page-header -->
    <div class="panel panel-inverse">
    <div class="panel-heading">
                <div class="panel-heading-btn">
                    <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand"></i></a>
                    <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-success" onclick="reload_table()"><i class="fa fa-repeat"></i></a>
                    <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-warning" data-click="panel-collapse"><i class="fa fa-minus"></i></a>
                    <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-danger" data-click="panel-remove"><i class="fa fa-times"></i></a>
                </div>
                <div class="btn-group pull-right">
                    <button type="button" onclick=onclick="load_user_login_modal(0); return false;" class="btn btn-success btn-xs">Add User</button>
                </div>
                <h4 class="panel-title">User List</h4>
            </div>
            <div class="panel-body">
                <div class="panel-body">
                    <div class="table-responsive">
     
        <table id="files" class="table table-bordered" cellspacing="0" width="100%">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Username</th>
                    <th>Initial</th>
                    <th>Memo</th>
                    <th>User Level</th>
                </tr>
            </thead>
            <?php
            foreach($this->user_login_list as $u){
                ?>
            <tr id="user_login_list_<?= $u["id"] ?>">
                <td>
                    <a onclick="load_user_login_modal(<?= $u['id'] ?>)"><?= $u["name"] ?></a>
                    <?php
                        if($user["id"] == $u["id"]) echo "( you )" ;
                    ?>

                </td>
                <td><?= $u["username"] ?></td>
                <td><?= $u["initial"] ?></td>
                <td><?= $u["memo"] ?></td>
                <td><?= $u["user_login_level_name"] ?></td>
            </tr>
            <?php
            }
            ?>
        </table>
        </div>
        </div>
        </div>
        </div>
    </div>
    </div>
    </div>
      <!-- end of TAB 1 -->
      
      <!-- TAB 2 -->
    <div role="tabpanel" class="tab-pane" id="login_level">
        <div class="panel-body">
                <div class="panel-body">
        <table class="table table-condensed">
            <thead>
                <tr>
                    <!-- displaying all the user level -->
                    <th></th>
                    <?php
                    foreach($login_level_list as $u){
                    ?>
                    <th>
                        <?php
                        if($user["user_login_level_name"] == "system_administrator"){
                        ?>
                        <a onclick="load_user_login_level_modal(<?= $u["id"] ?>)"><?= $u["name"] ?></a>
                        <?php
                        } else {
                        ?>
                        <?= $u["name"] ?>
                        <?php
                        }
                        ?>
                    </th>
                    <?php
                    }
                    ?>
                    <!-- done displaying all the user level -->
                    <th>
                        <?php
                        if($user["user_login_level_name"] == "system_administrator") {
                            ?>
                          <button class="btn btn-default" onclick="load_user_login_level_modal(0); return false;">+</button>
                          <?php
                        }
                        ?>
                    </th>
                </tr>
            </thead>
            <?php
            // going through each controller
            foreach($this->available_controller_list as $controller_name => $controller){
                ?>
            <!-- displaying the class header -->
            <tr style="background-color: #89A; color: white">
                <td><?= $controller_name ?></td>
                <?php
                foreach($login_level_list as $u){
                ?>
                <td></td>
                <?php
                }
                ?>
                <td></td>
            </tr>
            <!-- DONE displaying the class header -->
            <?php
                // going through each function
                foreach($controller as $function) {
                ?>
            <tr>
                <td style="padding-left: 30px"><?= $function ?></td>
                <?php
                foreach($login_level_list as $u){
                    
                    $privilege_marker = "";
                    if(
                            $u["hashed_privilege"][$controller_name][$function] == "true"
                            || $u["hashed_privilege"][$controller_name]["*"] == "true"
                            || $u["hashed_privilege"]["*"][0] == "true"
                            )
                        $privilege_marker = "x";
                    
                ?>
                <td><?= $privilege_marker ?></td>
                <?php
                }
                ?>
                <td></td>
            </tr>
            <?php
                }
            }
            ?>
        </table>
        </div>
        </div>
    </div>
      <!-- end of TAB 2 -->

      
      <!-- TAB 3 -->
    <div role="tabpanel" class="tab-pane" id="login_menu">
     <div class="panel-body">
                <div class="panel-body">
      <div>
          <button class="btn btn-primary m-r-5 m-b-5" onclick="load_user_login_menu_modal(0); return false;">New Menu</button>
          <button class="btn btn-warning m-r-5 m-b-5" onclick="show_delete_menu_option(); return false;">Modify</button>
      </div>
        <table class="table table-condensed" id="user_login_menu_table">
            <thead>
                <tr>
                    <!-- displaying all the user level -->
                    <th class="delete_td hidden"></th>
                    <th></th>
                    <?php
                    foreach($login_level_list as $u){
                    ?>
                    <th>
                        <?php
                        if($user["user_login_level_name"] == "system_administrator"){
                        ?>
                        <a onclick="load_user_login_menu_modal(<?= $u["id"] ?>)"><?= $u["name"] ?></a>
                        <?php
                        } else {
                        ?>
                        <?= $u["name"] ?>
                        <?php
                        }
                        ?>
                    </th>
                    <?php
                    }
                    ?>
                    <!-- done displaying all the user level -->
                </tr>
            </thead>
            <?php
            // going through each controller
            foreach($login_menu_hash as $parent_id => $child_array){

                ?>
            <!-- displaying the class header -->
            <tr style="background-color: #89A; color: white">
                <?php if(count($child_array) == 1) { ?>
                <td  class="delete_td hidden">
                    <button class="btn btn-danger" style="padding:0px 8px;" onclick="delete_menu(<?= $login_menu_lookup_hash[$parent_id]["id"] ?>)">-</button>
                    &nbsp;&nbsp;&nbsp;
                    <button class="btn btn-info" style="padding:0px 8px;" onclick="sort_slide_up(<?= $login_menu_lookup_hash[$parent_id]["id"] ?>)">^</button>
                    <button class="btn btn-info" style="padding:0px 8px;" onclick="sort_slide_down(<?= $login_menu_lookup_hash[$parent_id]["id"] ?>)">v</button>
                </td>
                <?php } else { ?>
                <td  class="delete_td hidden">
                    <span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
                    <button class="btn btn-info" style="padding:0px 8px;" onclick="sort_slide_up(<?= $login_menu_lookup_hash[$parent_id]["id"] ?>)">^</button>
                    <button class="btn btn-info" style="padding:0px 8px;" onclick="sort_slide_down(<?= $login_menu_lookup_hash[$parent_id]["id"] ?>)">v</button>
                </td>
                <?php } ?>
                <td><?= $login_menu_lookup_hash[$parent_id]["title"] ?> <?= count($child_array) == 1 ? " - " . $login_menu_lookup_hash[$parent_id]["link"] : "" ?></td>
                <?php
                foreach($login_level_list as $u){
                    $privilege_marker = "";
                    if(
                            $u["hashed_menu"][$parent_id]["*"] == "true"
                            || $u["hashed_menu"]["*"][0] == "true"
                            )
                        $privilege_marker = "x";
                ?>
                <td><?= $privilege_marker ?></td>
                <?php
                }
                ?>
            </tr>
            <!-- DONE displaying the class header -->
            <?php
                // going through each function
                foreach($child_array as $child) {
                    if($child["id"] == $parent_id) continue;
                ?>
            <tr>
                <td  class="delete_td hidden">
                    <button class="btn btn-danger" style="padding:0px 8px;" onclick="delete_menu(<?= $child["id"] ?>)">-</button>
                    &nbsp;&nbsp;&nbsp;
                    <button class="btn btn-info" style="padding:0px 8px;" onclick="sort_slide_up(<?= $child["id"] ?>)">^</button>
                    <button class="btn btn-info" style="padding:0px 8px;" onclick="sort_slide_down(<?= $child["id"] ?>)">v</button>
                </td>
                <td style="padding-left: 30px"><?= $child["title"] ?> - <?= $child["link"] ?></td>
                <?php
                foreach($login_level_list as $u){
                    
                    $privilege_marker = "";
                    if(
                            $u["hashed_menu"][$parent_id][$child["id"]] == "true"
                            || $u["hashed_menu"][$parent_id]["*"] == "true"
                            || $u["hashed_menu"]["*"][0] == "true"
                            )
                        $privilege_marker = "x";
                    
                ?>
                <td><?= $privilege_marker ?></td>
                <?php
                }
                ?>
            </tr>
            <?php
                }
            }
            ?>
        </table>
        </div>
        </div>

    </div>      <!-- end of TAB  -->
  </div>

</div>



<!-- Modal -->
<div class="modal fade" id="user_login_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">View / Edit user</h4>
      </div>
      <div class="modal-body" id="user_login_modal_body">
        ...
      </div>
    </div>
  </div>
</div>


<script>
    function load_user_login_modal(id){
        $.get(
            "<?= URL ?>login/ajax_get_user_login_by_id/"+id,
            {},
            function( resp ) {
                $("#user_login_modal_body").html(resp);
                $("#user_login_modal").modal("show");
            }
        );
    }
</script>


<!-- Modal -->
<div class="modal fade" id="user_login_level_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <input type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="close_login_level_modal(); return false;" value="&times;">
        <h4 class="modal-title" id="myModalLabel">Edit User Level</h4>
      </div>
      <div class="modal-body" id="user_login_level_modal_body">
        ...
      </div>
    </div>
  </div>
</div>


<script>
    function load_user_login_level_modal(id){
        $.get(
            "<?= URL ?>login/ajax_get_user_login_level_by_id/"+id,
            {},
            function( resp ) {
                $("#user_login_level_modal_body").html(resp);
                $("#user_login_level_modal").modal("show");
            }
        );
    }
    
    var url = document.location.toString();
    if (url.match('tab=')) {
        $('.nav-tabs a[href=#'+url.split('tab=')[1]+']').tab('show') ;
    } 

    function close_login_level_modal() {
        window.location.href =  window.location.href.split("?")[0].split("#")[0] + "?tab=login_level";
        return;
    }


</script>



<!-- Modal -->
<div class="modal fade" id="user_login_menu_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <input type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="close_login_menu_modal(); return false;" value="&times;">
        <h4 class="modal-title" id="myModalLabel">Edit Menu</h4>
      </div>
      <div class="modal-body" id="user_login_menu_modal_body">
        ...
      </div>
    </div>
  </div>
</div>


<script>
    function load_user_login_menu_modal(id){
        $.get(
            "<?= URL ?>login/ajax_get_user_login_menu_by_id/"+id,
            {},
            function( resp ) {
                $("#user_login_menu_modal_body").html(resp);
                $("#user_login_menu_modal").modal("show");
            }
        );
    }
    
    function close_login_menu_modal() {
        window.location.href =  window.location.href.split("?")[0].split("#")[0] + "?tab=login_menu";
        return;
    }

    function show_delete_menu_option() {
        if($(".delete_td").hasClass("hidden")){
            $(".delete_td").removeClass("hidden");
        } else {
            $(".delete_td").addClass("hidden");
        }
    }

    function delete_menu(id){
        $.get(
            "<?= URL ?>login/ajax_delete_user_login_menu/"+id,
            {},
            function( resp ) {
                window.location.href =  window.location.href.split("?")[0].split("#")[0] + "?tab=login_menu";
                return;
            }
        );
    }
    
    function sort_slide_up(id){
        sort_slide(id, -1);
    }
    function sort_slide_down(id){
        sort_slide(id, 1);
    }
    function sort_slide(id, direction){
        $.get(
            "<?= URL ?>login/ajax_slide_user_login_menu/"+id+"/"+direction,
            {},
            function( resp ) {
//                console.log(resp);
//                return;
                window.location.href =  window.location.href.split("?")[0].split("#")[0] + "?tab=login_menu";
                return;
            }
        );
    }
</script>
