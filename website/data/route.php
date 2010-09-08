<?
set_time_limit(0);
require "../lib/cta_interface.php";

header("Content-type: text/plain");

$conn = dbConnect();

dbQuery("TRUNCATE TABLE routes;", $conn);
dbQuery("TRUNCATE TABLE stops;", $conn);

//LOAD ROUTES
$routes = get_bus_routes();

foreach ($routes as $r) {
	$route = $r["rt"];
	$name = $r["rtnm"];
	dbQuery("INSERT INTO routes (`name`, `number`) VALUES ('$name', '$route')", $conn);
	$directions = get_bus_directions($route);
	foreach ($directions as $d) {
		$stops = get_bus_stops($route, $d);
		
		foreach ($stops as $s) {
			//insert stop
			$name = str_replace("'", "\'", $s["stpnm"]);
			$cta_stop_id =  $s["stpid"];
			$lat =  $s["lat"];
			$lon = $s["lon"];
			dbQuery("INSERT INTO stops (name, lat, lon, route_id, cta_stop_id, direction) 
		         VALUES ('$name', '$lat', '$lon', '$route', '$cta_stop_id', '$d')", $conn);			
			
		}
	}
}
?>