<?php
class dbconnect{
    function connect()
    {  $con = pg_connect("host=localhost port=5432 dbname=afv user=postgres password="); 
       if (!$con)     { die('Could not connect: ' .  pg_last_error());  }}}
       
?>