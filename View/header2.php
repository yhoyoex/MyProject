<!DOCTYPE html>
<html>
    <head>
        <title><?= $this->title ?></title>
        <link rel="stylesheet" href="<?php echo URL; ?>public/css/default.css" />
        <link rel="stylesheet" href="<?php echo URL; ?>public/css/bootstrap.css">
        <link rel="stylesheet" href="<?php echo URL; ?>public/css/simple-sidebar.css">
    </head>
    <body style="background-color: #EEE;">
<div id="wrapper">
    <!-- Sidebar -->
    <div id="sidebar-wrapper">
        <ul class="sidebar-nav text_larger" style="">
            <li align="center" style="margin-left: 0px"></li>
            <li class="sidebar-brand header_bar" style="border-bottom:1px solid #888;">
                Test
            </li>
            
            <?php
            foreach($this->application_menu as $parent => $children){
                if(count($children) == 1) {
                    $menu = $children[$parent];
                    ?>
                        <li style="border-bottom:1px solid #888">
                            <a href="<?= str_replace("[ROOT]", URL, $menu["link"]) ?>"><?= $menu["title"] ?></a>
                        </li>
                    <?php
                    continue;
                } else if(count($children) == 0) {
                    continue;
                } else {
                    ?>
                    <li style="border-bottom:1px solid #888">
                        <a href="#"><?= $children[$parent]["title"] ?></a>
                    </li>
                    <?php
                    foreach($children as $child_id => $child) {
                        if($child_id == $parent) continue;
                        $menu = $child;
                        ?>
                            <li style="border-bottom:1px solid #888; padding-left: 20px">
                                <a href="<?= str_replace("[ROOT]", URL, $menu["link"]) ?>"> > <?= $menu["title"] ?></a>
                            </li>
                        <?php
                    }
                }  
            }
            ?>
            
        </ul>
    </div>
    
    
    <!-- HEADER BAR -->
    <div id="top-nav" width="100%" align="right">
        <table>
            <tr>
                <td>
                    <?php
                    $user = Session::get("user");
                    $level_display = "";
                    if($user["user_login_level_name"] == "administrator" || $user["user_login_level_name"] == "system_administrator"){
                        $level_display = " ( " . $user["user_login_level_name"] . " ) ";
                    }
                    if($user != null) { ?>
                    <div class="dropdown" id="username_dropdown" style="width: 200px">
                        <a href="#" data-toggle="dropdown" class="header_link" id="username_dropdown_button"><?= $user["name"]; ?> <b class="caret"></b></a><?= $level_display ?>
                        <ul class="dropdown-menu">
                            <li><a href="<?= URL ?>/login" style="color: #777; font-weight: bold">Logout</a></li>
                            <li>
                                <a href="#" onclick="$('#password_change_form').removeAttr('hidden'); unhide_clicked=true; ">Change Password</a>
                                <form id="password_change_form" hidden>
                                    <input class="form-control" style=" width:150px; height:22px; margin: 0px 8px" type="password" name="old_password" placeholder="old password" id="old_password_input"/>
                                    <input class="form-control" style=" width:150px; height:22px; margin: 0px 8px" type="password" name="new_password_1" placeholder="new password"/>
                                    <input class="form-control" style=" width:150px; height:22px; margin: 0px 8px" type="password" name="new_password_2" placeholder="new password"/>
                                    <input class="btn btn-warning" style=" width:150px; height:22px; margin: 0px 8px" type="submit" value="save">
                                </form>
                            </li>
                        </ul>
                    </div>
                    <?php } ?>
                </td>
            </tr>
        </table>
    </div> <!-- HEADER BAR -->

    <script>
            var unhide_clicked = false;
            $("#username_dropdown").on("hidden.bs.dropdown", function(){
                if(unhide_clicked) {
                    unhide_clicked = false;
                    $('#username_dropdown').addClass("open");
                    $("#old_password_input").focus();
                } else {
                    $('#password_change_form').attr('hidden', true);
                }
            });
    </script>
        
<div style="padding: 20px"> 
    <div id="main_content" style="padding:20px; background-color: #FFF; border: 1px solid #DDD">
                                            
