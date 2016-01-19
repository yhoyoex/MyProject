<?php 
if (substr_count($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip') && !isset($_GET["nocompress"])) ob_start("ob_gzhandler"); 

date_default_timezone_set('Asia/Ujung_Pandang');

require "config.php";
require "lib/basic.php";
require "lib/Database.php";
require "lib/Controller.php";
require "lib/Model.php";
require "lib/View.php";
require "lib/Bootstrap.php";
require "lib/common/soap2.php";
require "lib/Session.php";
require "lib/LoginAssist.php";

Session::init();

$bootstrap = new Bootstrap();
?>