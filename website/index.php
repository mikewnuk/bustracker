<?
require "config.php";
require "lib/database.php";
require "lib/class_http.php";
require "lib/xmlarray.php";
require "lib/cta_interface.php";

$directions = array("North%20Bound", "South%20Bound", "East%20Bound", "West%20Bound");
//home!
$lat = $_POST["lat"]; //41.9004331392957;
$lon = $_POST["lon"]; //-87.6868722790678;
?>
<html>
	<head>
		<title>CTA Bus Tracker</title>
		<meta content='width=device-width; initial-scale=1.0; maximum-scale=1.0; user-scalable=0;' name='viewport' />
		<meta name="viewport" content="width=320" />		
		<link rel="stylesheet" href="/css/style.css"></link>
	</head>
	<body>
<xmp>
	<? //print_r($_POST)?>
</xmp>
		<div id="wrapper">
			<form action="/index.php" method="POST">
				<input type="hidden" name="lat" id="lat"/>
				<input type="hidden" name="lon" id="lon"/>			
				<input type="submit" value="Update" name="update"/>
			</form>
			<? if (isset($_POST["lat"])):?>
				<? foreach ($directions as $d): ?>
				
					<div class="direction">
						<div class="direction_label"><? print substr($d, 0,1); ?></div>
						<? $stops = find_nearby_stops($lat, $lon, $d); ?>
						<ul class="info">
							<? foreach ($stops as $s): ?>
							<li>
								<div class="route">
									<span class="routename"><?=$s["routename"]?></span>, 
									<span class="stopname"><?=$s["stopname"]?></span>
									
									<div class="stop">
										<? if ( count($s["times"]) == 0 ): ?>
											<span class="times">No buses</span>
										<? else: ?>
											<div class="times">
											<? foreach ($s["times"] as $t): ?>
												<? $time = split(" ", $t["prdtm"]); ?>
												<span class="time"><? print $time[1] . " "; ?></span>
											<? endforeach; ?>
										</div>
										<? endif; ?>
									</div>
								</div>									
							</li>
							<? endforeach; ?>
						</ul>
						<div style="clear:both"></div>
					</div>
				<? endforeach; ?>
			<? endif;?>
		</div>
		<script type="text/javascript">
			function findstops(position) {
				document.getElementById("lat").value = position.coords.latitude;
				document.getElementById("lon").value = position.coords.longitude;
			}
			navigator.geolocation.getCurrentPosition(findstops);
		</script>
	</body>
</html>