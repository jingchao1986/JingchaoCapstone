<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$con = pg_connect("hostaddr=129.219.93.228 port=5432 dbname=afvrouti_map user=afvrouti password=GaFyerDD6FLcp863 sslmode=disable"); 
var_dump($con);
echo(pg_last_notice());
echo(pg_last_error());

?>