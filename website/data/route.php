<?
set_time_limit(0);
require "../config.php";
require "../lib/database.php";
require "../lib/class_http.php";
require "../lib/xmlarray.php";

header("Content-type: text/plain");
$routes = fetcharray("select * from routes");
$conn = dbConnect();

if ($_GET["initialrun"] == "true") {
	$initialrun = true;
}
else {
	$initialrun = false;
}

dbQuery("TRUNCATE TABLE stops;", $conn);

foreach($routes as $r) {
	$route_id = $r["id"];


	$h = new http();
	$h->url = "http://chicago.transitapi.com/bustime/map/getRoutePoints.jsp?route=" . $r["number"];
	$h->fetch($h->url);
	$xmlObj    = new XmlToArray($h->body);

	$points = $xmlObj->createArray();	
	
	if ($intialrun == true) {
		$h->url = "http://chicago.transitapi.com/bustime/eta/routeDirectionStopAsXML.jsp?route=" . $r["number"];
		$h->fetch($h->url);

		$xmlObj = new XmlToArray($h->body);
		$directions = $xmlObj->createArray();	

		if ($directions["direction-list"]["direction"]["name"] == "West Bound") {
			$direction = "east-west";
		}
		else {
			$direction = "north-south";
		}
	}
	
	print "Loading Route: $r[name] ($r[number])\n";
	
	//print_r($points);
	//print_r($points["route"]["pas"][0]["pa"][0]["pt"]);

	dbQuery("UPDATE routes SET direction='$direction' WHERE id=$route_id", $conn);
	if ($points["route"]["pas"] == "") {
		print "No data for Route Number $r[number] ($route_id) \n";
	}
	else {
		//insert stops
		foreach ($points["route"]["pas"][0]["pa"][0]["pt"] as $p) {
			if (isset($p["bs"])) {
				$name = str_replace("'", "\'", $p["bs"][0]["st"]);
				$cta_stop_id =  $p["bs"][0]["id"];
				$lat =  $p["lat"];
				$lon = $p["lon"];
				dbQuery("INSERT INTO stops (name, lat, lon, route_id, cta_stop_id) 
			         VALUES ('$name', '$lat', '$lon', '$route_id', '$cta_stop_id')", $conn);
			}

		}
	}
}

?>