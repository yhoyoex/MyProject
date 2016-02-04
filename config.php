<?php

define("DB_TYPE", "mysql");
define("DB_HOST", "localhost");
define("DB_USER", "root");
define("DB_PASS", "");
define("DB_NAME", "test_project");
define("ENVIRONMENT", "DEV");

/*
$uri = $_SERVER["REQUEST_URI"];
$slash_count = substr_count($uri, "/");
$slash_count = $slash_count - 2;
$public_folder = "";
while($slash_count > 0){
    $public_folder .= "../";
    $slash_count--;
}
define("URL", $public_folder);
*/

$self = "http://".$_SERVER['SERVER_NAME'].$_SERVER["PHP_SELF"];
$self = str_replace("index.php", "", $self);
define("URL", $self);

?>