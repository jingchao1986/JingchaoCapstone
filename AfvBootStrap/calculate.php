<?php
//ini_set('display_errors', 1);
//ini_set('display_startup_errors', 1);
//error_reporting(E_ALL);

if (!isset($_GET['to']))
    header("refresh:0;url=index.html");
include("dbconnect.php");
include("getDist.php");
require("Dijkstra.php");
$con = new dbconnect();
$con->connect();
$g = new Graph();


#Get User variables
$to = $_GET["to"];
$from = $_GET["from"];;
$fuel = $_GET['fuel'];
$privatetext=" like 'Pub%'";
if($fuel=="HYP")
    {
        $fuel = "hy";
        $privatetext=" like 'P%'";
    };
 if($fuel=="HY")
    {
        $fuel = "hy";
    };  

$range = $_GET['range'] * 1609;
$startrange = $_GET['init'] * $range / 100;
$endrange = $range;
if($_GET['round'] == 'true')
    $endrange = ($range / 2);


$rangemi = $_GET['range'];
    $endpoint = 'http://dev.virtualearth.net/REST/v1/Routes?';
    $params = array(
    'key'=> 'AmQPhKl45GKdrmoOkZIsiUsL75DBRva9n3jfXmOkZnZNS23ZbKPSJa2O-9e3CeWU',
    'wp.0' => substr($to,1,-1),
    'wp.1' => substr($from,1,-1),
    'optimize' => 'distance',
    'distanceUnit' => 'mi',

    );
	//echo(substr($to,1,-1));
    //echo(substr($from,1,-1));

    $link = $endpoint . http_build_query($params);
    $json = file_get_contents($link);
    $data = json_decode($json);


        // If we got directions, output all of the HTML instructions
    if ($data->statusCode === 200) {
    $mindist = (float) $data->resourceSets[0]->resources[0]->travelDistance;
    $mintime = $data->resourceSets[0]->resources[0]->travelDuration;

#echo "The path with no stations is " . $mindist . " miles long<br/><h1>Start to Stations</h1>";
//need to switch from lat lon to lon lat
$to_pg = swap($to);
$from_pg = swap($from);
#var_dump($bblist);
$miny = $data->resourceSets[0]->resources[0]->bbox[0];
$maxy= $data->resourceSets[0]->resources[0]->bbox[2];
$minx = $data->resourceSets[0]->resources[0]->bbox[1];
$maxx = $data->resourceSets[0]->resources[0]->bbox[3];



$bb = $minx . " " . $miny . "," . $minx . " " . $maxy . "," . $maxx . " " . $maxy . "," . $minx . " " . $miny;
    }
//echo $bb;
// execute query for start

$hsql = "select lat, lon, id from hstations where ST_DWithin(ST_transform(ST_GeomFromText('POINT$from_pg', 4326), 3857),ST_transform(ST_GeomFromText('POINT(' || hstations.lon || ' ' ||hstations.lat || ')',4326),3857), $startrange) and access ".$privatetext ." order by ST_Distance(ST_transform(ST_GeomFromText('POINT$to_pg', 4326), 3857),ST_transform(ST_GeomFromText('POINT(' || hstations.lon || ' ' ||hstations.lat || ')',4326),3857)) asc limit 150";
$csql = "select lat, lon, id from stations where ST_DWithin(ST_transform(ST_GeomFromText('POINT$from_pg', 4326), 3857),ST_transform(ST_GeomFromText('POINT(' || stations.lon || ' ' ||stations.lat || ')',4326),3857), $startrange) order by ST_Distance(ST_transform(ST_GeomFromText('POINT$to_pg', 4326), 3857),ST_transform(ST_GeomFromText('POINT(' || stations.lon || ' ' ||stations.lat || ')',4326),3857)) asc limit 100";
//$csql = "select lat, lon, id from stations where ST_DWithin(ST_transform(ST_GeomFromText('POINT$from_pg', 4326), 3857),stations.geom, $startrange) order by ST_Distance(ST_transform(ST_GeomFromText('POINT$to_pg', 4326), 3857),stations.geom) asc limit 100";
//$csql = "select lat, lon, id from stations where ST_DWithin(ST_transform(ST_GeomFromText('POINT$from_pg', 4326), 3857),ST_GeomFromText('POINT(' || stations.lon || ' ' ||stations.lat || ')',3857), $startrange) order by ST_Distance(ST_transform(ST_GeomFromText('POINT$to_pg', 4326), 3857),ST_GeomFromText('POINT(' || stations.lon || ' ' ||stations.lat || ')',3857)) asc limit 100";

if ($fuel=="hy"){
$result = pg_query($hsql);}
else 
{
	$result = pg_query($csql);
	//echo($csql);
}
if (!$result) {
    die("Error in SQL query 1: " . pg_last_error());
}
// iterate over result set
// print each row
while ($row = pg_fetch_array($result)) {
    $dist = getDist($row['lat'], $row['lon'], $from, "start");
    $g->addedge("start", $row['id'], $dist + 1);
    //echo($g);
    //echo "start" . " to " .  $row['id'] . " is " .  $dist . "<br/>";
}


// free memory
pg_free_result($result);


//execute query for end.
$hsql = "select lat, lon, id from hstations where ST_DWithin(ST_transform(ST_GeomFromText('POINT$to_pg', 4326), 3857),ST_transform(ST_GeomFromText('POINT(' || hstations.lon || ' ' ||hstations.lat || ')',4326),3857), $endrange) and access ".$privatetext ." order by ST_Distance(ST_transform(ST_GeomFromText('POINT$to_pg', 4326), 3857),ST_transform(ST_GeomFromText('POINT(' || hstations.lon || ' ' ||hstations.lat || ')',4326),3857)) asc limit 150";
$csql = "select lat, lon, id from stations where ST_DWithin(ST_transform(ST_GeomFromText('POINT$to_pg', 4326), 3857),ST_transform(ST_GeomFromText('POINT(' || stations.lon || ' ' ||stations.lat || ')',4326),3857), $endrange) order by ST_Distance(ST_transform(ST_GeomFromText('POINT$to_pg', 4326), 3857),ST_transform(ST_GeomFromText('POINT(' || stations.lon || ' ' ||stations.lat || ')',4326),3857)) asc limit 100";
//echo $hsql;
//echo $csql;
if ($fuel=="hy"){
$result = pg_query($hsql);}
else $result = pg_query($csql);

if (!$result) {
    die("Error in SQL query2: " . pg_last_error());
}
// iterate over result set
// print each row
while ($row = pg_fetch_array($result)) {
    $dist = getDist($row['lat'], $row['lon'], $to, "end");
    $g->addedge($row['id'], "end", $dist + 1);
	//echo($g);
    //echo "end" . " to " .  $row['id'] . " is " .  $dist . "<br/>";
}

pg_free_result($result);

//execute query for links in the middle
$hsql = "SELECT hnetwork.source, hnetwork.target, hnetwork.cost FROM hnetwork, hstations WHERE access ".$privatetext ." and hnetwork.source = hstations.id AND ST_DWithin(ST_transform(ST_GeomFromText('Polygon(($bb))', 4326), 3857),ST_transform(ST_GeomFromText('POINT(' || hstations.lon || ' ' ||hstations.lat || ')',4326),3857), $range*6) AND hnetwork.cost < $rangemi";
$csql = "SELECT network.source, network.target, network.cost FROM network, stations WHERE network.source = stations.id AND ST_DWithin(ST_transform(ST_GeomFromText('Polygon(($bb))', 4326), 3857),ST_transform(ST_GeomFromText('POINT(' || stations.lon || ' ' ||stations.lat || ')',4326),3857), $range*6) AND network.cost < $rangemi";
#echo $hsql;
//echo $csql;
if ($fuel=="hy"){
$result = pg_query($hsql);}
else $result = pg_query($csql);
if (!$result) {
    die("Error in SQL query for middle: " . pg_last_error());
}
// iterate over result set
// print each row
while ($row = pg_fetch_array($result)) {
    $g->addedge($row['source'], $row['target'], $row['cost']);
    //echo $row['source'] . " to " .  $row['target'] . " is " .  $row['cost'] . "<br/>";
}
list($distances, $prev) = $g->paths_from("start");
$path = $g->paths_to($prev, "end");

$dom = new DOMDocument("1.0");
$node = $dom->createElement("stations");
$parnode = $dom->appendChild($node);
header("Content-type: text/xml");
//echo(count($path));
// Iterate through the rows, adding XML nodes for each
for ($i = 1; $i < count($path) - 1; $i++) {
    // ADD TO XML DOCUMENT NODE
    $hsql = "select lat, lon, id, stationnam, city from hstations where id = $path[$i]";
    $csql = "select lat, lon, id, stationnam, city from stations where id = $path[$i]";
   
    if ($fuel=="hy"){
		//echo($hsql);
		$result = pg_query($hsql);
	}
    else {
		//echo($csql);
		$result = pg_query($csql);
	}
    if (!$result) {
        die("Error in SQL query for middle: " . pg_last_error());
		//echo(pg_last_error());
    }
    $row = pg_fetch_array($result);
	
    $node = $dom->createElement("station");
    $newnode = $parnode->appendChild($node);
    $newnode->setAttribute("stationnam", $row['stationnam']);
    //$newnode->setAttribute("address", $row['address']);
	$newnode->setAttribute("address", $row['address']);
    $newnode->setAttribute("lat", $row['lat']);
    $newnode->setAttribute("lon", $row['lon']);
    #$newnode->setAttribute("type", $row['type']);
}
$node = $dom->createElement("data");
$newnode = $parnode->appendChild($node);
$newnode->setAttribute("time", $mintime);
$newnode->setAttribute("dist", $mindist);

echo $dom->saveXML();

#echo "<h1>Final Shortest Path</h1>";
#var_dump($path);





?>