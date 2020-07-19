<?php
class dbconnect{
    function connect()
    {  $con = pg_connect("hostaddr=129.219.93.228 port=5432 dbname=afvrouti_map user=afvrouti password=");    if (!$con)     { die('Could not connect: ' .  pg_last_error());  }}}?>