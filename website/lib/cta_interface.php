<?
header("Content-Type:text/html");


function cta_api_request($request, $params = array()) {
	$params["key"] = CTA_API_KEY;
	$param_string = "";
	foreach ($params as $k=>$v) {	
		$param_string .= "$k=$v&";	
	}
	$h = new http();
	$h->url = CTA_API_URL . $request . "?" . $param_string;
	$h->fetch($h->url);
	$xmlObj = new XmlToArray($h->body);
	$array =  $xmlObj->createArray();
	return $array["bustime-response"];
}

function get_bus_routes() {
	$r =  cta_api_request("getroutes");
	return $r["route"];
}

function get_bus_stops($route, $direction) {
	$stops =  cta_api_request("getstops", array("rt" => $route, "dir" => $direction));
	return $stops["stop"];
}

function get_bus_directions($route) {
	$directions = cta_api_request("getdirections", array("rt" => $route));
	if ($directions["dir"] == "South Bound" || $directions["dir"] == "North Bound") {
		return array("North%20Bound", "South%20Bound");
	}
	else {
		return array("East%20Bound", "West%20Bound");
	}
}

function get_bus_times($route, $cta_stop_id) {
	$times =  cta_api_request("getpredictions", array("stpid"=> $cta_stop_id));
	return $times["prd"];
}

function find_nearby_stops($lat, $lon, $direction) {
	$sql = "SELECT 
				((ACOS(SIN({$lat} * PI() / 180) * 
				  SIN(`lat` * PI() / 180) + COS({$lat} * PI() / 180) * 
				  COS(`lat` * PI() / 180) * COS(({$lon} - `lon`) * 
				  PI() / 180)) * 180 / PI()) * 60 * 1.1515)
			AS `distance`, `stops`.`name` as stopname, `routes`.`number` as `number`, `route_id`, `routes`.`name` as routename, cta_stop_id 	 
			FROM `stops` 
			INNER JOIN `routes`
			ON `stops`.`route_id` = `routes`.`number`
			WHERE `stops`.`direction`='$direction'
			HAVING `distance`<=0.50 ORDER BY `distance` ASC";
	//print "<xmp>$sql</xmp>";
	$stops = fetcharray($sql);
	$uniqueRoutes = array();
	foreach ($stops as $s) {
		if (!array_key_exists($s["route_id"], $uniqueRoutes)) {
			$uniqueRoutes[$s["route_id"]] = $s;		
			$uniqueRoutes[$s["route_id"]]["times"] = get_bus_times($s["number"], $s["cta_stop_id"]);
		}
	}
	return $uniqueRoutes;
}

function full_direction_name($d) {
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


/* test */ 
//print_r(cta_api_request("getroutes", array("rt"=>20) ));

//print_r(get_bus_routes());

//print_r(get_bus_stops("66", "West%20Bound"));
?>