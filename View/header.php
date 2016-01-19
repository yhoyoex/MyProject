<!DOCTYPE html>
<!--[if IE 8]> <html lang="en" class="ie8"> <![endif]-->
<!--[if !IE]><!-->
<html lang="en">
<!--<![endif]-->

<head>
	<meta charset="utf-8" />
	<title><?= $this->project_name ?> | <?= $this->page_header ?></title>
	<meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" name="viewport" />
	<meta content="" name="description" />
	<meta content="" name="author" />
	
	<!-- ================== BEGIN BASE CSS STYLE ================== -->
	<link href="<?php echo URL; ?>public/css/font_googleapis.css" rel="stylesheet">
	<link href="<?php echo URL; ?>public/plugins/jquery-ui/themes/base/minified/jquery-ui.min.css" rel="stylesheet" />
	<link href="<?php echo URL; ?>public/plugins/bootstrap/css/bootstrap.css" rel="stylesheet" />
	<link href="<?php echo URL; ?>public/plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet" />
	<link href="<?php echo URL; ?>public/css/animate.min.css" rel="stylesheet" />
	<link href="<?php echo URL; ?>public/css/style.min.css" rel="stylesheet" />
	<link href="<?php echo URL; ?>public/css/style-responsive.min.css" rel="stylesheet" />
	<link href="<?php echo URL; ?>public/css/style.css" rel="stylesheet" />
	<link href="<?php echo URL; ?>public/css/theme/default.css" rel="stylesheet" id="theme" />
	<link href="<?php echo URL; ?>public/plugins/DataTables/css/data-table.css" rel="stylesheet" />
	<link href="<?php echo URL; ?>public/plugins/bootstrap-tagsinput/bootstrap-tagsinput.css" rel="stylesheet" />
	<link href="<?php echo URL; ?>public/plugins/jquery-ui/jquery-ui.css" rel="stylesheet" />
	<link href="<?php echo URL; ?>public/plugins/bootstrap-tagsinput/typeahead.css" rel="stylesheet" />
	<!-- ================== END BASE CSS STYLE ================== -->

	<!-- ================== BEGIN PAGE LEVEL CSS STYLE ================== -->
    <link href="<?php echo URL; ?>public/plugins/jquery-file-upload/css/jquery.fileupload.css" rel="stylesheet" />
    <link href="<?php echo URL; ?>public/plugins/jquery-file-upload/css/jquery.fileupload-ui.css" rel="stylesheet" />
    <noscript><link rel="stylesheet" href="<?php echo URL; ?>public/plugins/jquery-file-upload/css/jquery.fileupload-noscript.css"></noscript>
	<noscript><link rel="stylesheet" href="<?php echo URL; ?>public/plugins/jquery-file-upload/css/jquery.fileupload-ui-noscript.css"></noscript>
	<link href="<?php echo URL; ?>public/plugins/jquery-file-upload/blueimp-gallery/blueimp-gallery.min.css" rel="stylesheet" />
    <link href="<?php echo URL; ?>public/plugins/gritter/css/jquery.gritter.css" rel="stylesheet" />
    <link href="<?php echo URL; ?>public/plugins/bootstrap-validator/dist/css/bootstrapValidator.css" rel="stylesheet" />
	<!-- ================== END PAGE LEVEL CSS STYLE ================== -->
	
	<!-- ================== BEGIN BASE JS ================== -->
	<script src="<?php echo URL; ?>public/plugins/jquery/jquery-2.1.4.min.js"></script>
	<script src="<?php echo URL; ?>public/plugins/jquery/jquery-migrate-1.1.0.min.js"></script>
	<script src="<?php echo URL; ?>public/plugins/jquery-ui/jquery-ui.js"></script>
    <script src="<?php echo URL; ?>public/plugins/bootstrap/js/bootstrap.min.js"></script>
	<script src="<?php echo URL; ?>public/plugins/pace/pace.min.js"></script>
	<script src="<?php echo URL; ?>public/plugins/DataTables/js/jquery.dataTables.js"></script>
	<script src="<?php echo URL; ?>public/plugins/DataTables/js/dataTables.columnFilter.js"></script>
	<!-- ================== END BASE JS ================== -->
</head>
<body>
	<!-- begin #page-loader -->
	<div id="page-loader" class="fade in"><span class="spinner"></span></div>
	<!-- end #page-loader -->
	
	<!-- begin #page-container -->
	<div id="page-container" class="fade page-sidebar-fixed page-header-fixed">
		<!-- begin #header -->
		<div id="header" class="header navbar navbar-default navbar-fixed-top">
			<!-- begin container-fluid -->
			<div class="container-fluid">
				<!-- begin mobile sidebar expand / collapse button -->
				<div class="navbar-header">
					<a href="index.html" class="navbar-brand"><span class="navbar-logo"></span>Project One</a>
					<button type="button" class="navbar-toggle" data-click="sidebar-toggled">
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
					</button>
				</div>
				<!-- end mobile sidebar expand / collapse button -->
				
				<!-- begin header navigation right -->
				<ul class="nav navbar-nav navbar-right">
					
					<?php
							$user = Session::get("user");
							$level_display = "";
							if($user["user_login_level_name"] == "administrator" || $user["user_login_level_name"] == "system_administrator"){
								$level_display = $user["user_login_level_name"];
                    		}
                    		if($user != null) { ?>
					<li class="dropdown navbar-user">
						<a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown">
							<img src="<?php echo URL; ?>public/images/avatar/<?= $user["avatar"]; ?>" alt="" />
							<span class="hidden-xs"><?= $user["username"]; ?></b></span> <b class="caret"></b>
						</a>
						<ul class="dropdown-menu animated fadeInLeft">
							<li class="arrow"></li>
							<li><a href="javascript:;">Edit Profile</a></li>
							<li><a href="<?= URL ?>login/viewer">Setting</a></li>
							<li class="divider"></li>
							<li><a href="<?= URL ?>login">Log Out</a></li>
						</ul>
					</li>
				</ul>
				<!-- end header navigation right -->
			</div>
			<!-- end container-fluid -->
		</div>
		<!-- end #header -->
		
		<!-- begin #sidebar -->
		<div id="sidebar" class="sidebar">
			<!-- begin sidebar scrollbar -->
			<div data-scrollbar="true" data-height="100%">
				<!-- begin sidebar user -->
				<ul class="nav">
					<li class="nav-profile">
						<div class="image">
							<a href="javascript:;"><img src="<?php echo URL; ?>public/images/avatar/<?= $user["avatar"]; ?>" alt="" /></a>
						</div>
						<div class="info">
                    		<?= $user["name"]; ?></b>
							<small><?= $level_display ?></small>
							<?php } ?>
						</div>
					</li>
				</ul>
				<!-- end sidebar user -->
				<!-- begin sidebar nav -->
				<ul class="nav">
					<?php
            		foreach($this->application_menu as $parent => $children){
                		if(count($children) == 1) 
                		{
                    		$menu = $children[$parent];
                    ?>
							<li>
								<a href="<?= str_replace("[ROOT]", URL, $menu["link"]) ?>">
									<i class="fa fa-align-left"></i>
									<span><?= $menu["title"] ?></span>
								</a>
							</li>
								
                    	<?php 
                    		continue;
                		} 
                		else if(count($children) == 0) 
                		{
                    		continue;
                		} 
                		else 
                		{
                    	?>
                    		<li class="has-sub">
                        		<a href="javascript:;">
                        			<b class="caret pull-right"></b>
                        			<i class="fa fa-align-left"></i>
                        			<span><?= $children[$parent]["title"] ?></span>
                        		</a>
                        		<ul class="sub-menu">
                        			<?php
                    				foreach($children as $child_id => $child) {
                        			if($child_id == $parent) continue;
                        			$menu = $child;
                        			?>
						    		<li><a href="<?= str_replace("[ROOT]", URL, $menu["link"]) ?>"><?= $menu["title"] ?></a></li>
						    		<?php } ?>
								</ul>
                    		</li>
                        	<?php
                		}	  
            		}
            		?>
			        <!-- begin sidebar minify button -->
					<li><a href="javascript:;" class="sidebar-minify-btn" data-click="sidebar-minify"><i class="fa fa-angle-double-left"></i></a></li>
			        <!-- end sidebar minify button -->
				</ul>
				<!-- end sidebar nav -->
			</div>
			<!-- end sidebar scrollbar -->
		</div>
		<div class="sidebar-bg"></div>
		<!-- end #sidebar -->
		
		<!-- begin #content -->
		<div id="content" class="content">
			<ol class="breadcrumb pull-right"><!-- begin breadcrumb -->
				<li><a href="javascript:;">Home</a></li>
				<li class="active"><?= $this->page_header ?></li>
			</ol><!-- end breadcrumb -->
			<h1 class="page-header"><?= $this->page_header ?></h1>