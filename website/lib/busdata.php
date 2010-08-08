<?
/*
This is the interface to the cta bus data. 

User input: A direction (N,S,W,E, NW, NE, SW, SE) plus lat + long


Step 1: Find nearby stops for given direction

Step 2: Determine routes that go through the stops

Step 3: Let's do 1 & 2 first. 

*/
function fullDirectionName($d) {
	switch ($d) {
		case "N":  return "North";
		case "NW": return "Northwest";
		case "NE": return "Northeast";
		case "S":  return "South";
		case "SW": return "Southwest";
		case "SE": return "Southeast";
		case "W":  return "West";
		case "E":  return "East";
	}
}

function findNearByStops($lat, $lon, $d) {
	if ($d == "N" || $d == "S") {
		$direction = "north-south";
	}
	else {
		$direction = "east-west";
	}

	$sql = "SELECT 
				((ACOS(SIN(41.9004331392957 * PI() / 180) * 
				  SIN(`lat` * PI() / 180) + COS(41.9004331392957 * PI() / 180) * 
				  COS(`lat` * PI() / 180) * COS((-87.6868722790678 - `lon`) * 
				  PI() / 180)) * 180 / PI()) * 60 * 1.1515)
			AS `distance`, `stops`.`name` as stopname, `routes`.`number` as `number`, `route_id`, `routes`.`name` as routename, cta_stop_id 	 
			FROM `stops` 
			INNER JOIN `routes`
			ON `stops`.`route_id` = `routes`.`id`
			WHERE `routes`.`direction`='$direction'
			HAVING `distance`<=0.50 ORDER BY `distance` ASC";

	$stops = fetcharray($sql);
	$uniqueRoutes = array();
	foreach ($stops as $s) {
		if (!array_key_exists($s["route_id"], $uniqueRoutes)) {
			$uniqueRoutes[$s["route_id"]] = $s;
			$uniqueRoutes[$s["route_id"]]["times"] = getBusTimes($s["number"], $s["cta_stop_id"]);
		}
	}
	return $uniqueRoutes;
}


function getBusTimes($route_number, $cta_top_id) {
	$h = new http();
	$h->url = "http://chicago.transitapi.com/bustime/map/getStopPredictions.jsp?stop={$cta_top_id}&route={$route_number}";
	$h->fetch($h->url);
	$xmlObj = new XmlToArray($h->body);
	return $xmlObj->createArray();
}


?>