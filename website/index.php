<?
require "config.php";
require "lib/database.php";
require "lib/xmlarray.php";
require "lib/class_http.php";
require "lib/busdata.php";

//home!
$lat = 41.9004331392957;
$lon = -87.6868722790678;
?>
<html>
	<head>
		<title>CTA Bus Tracker</title>
		<link rel="stylesheet" href="/css/style.css"></link>
	</head>
	<body>
		<div id="wrapper">
			<h1>Which way?</h1>
			<div id="compass">
				<a id="nw" class="intermediate" href="/?d=NW"><span>NW</span></a>
				<a id="n" href="/?d=N"><span>N</span></a>
				<a id="ne" class="intermediate" href="/?d=NE"><span>NE</span></a>
				<a id="w" href="/?d=W"><span>W</span></a>
				<a id="middle" href="/"><span></span></a>
				<a id="e" href="/?d=E"><span>E</span></a>
				<a id="sw" class="intermediate" href="/?d=SW"><span>SW</span></a>
				<a id="s" href="/?d=S"><span>S</span></a>
				<a id="se" class="intermediate" href="/?d=SE"><span>SE</span></a>
				<div style="clear:both"></div>
			</div>
			<? if (isset($_GET["d"])): 
				$directions = str_split($_GET["d"]);
				foreach ($directions as $d): ?>
					<div class="direction">
						<h2><? print fullDirectionName($d); ?></h2>
						<? $stops = findNearByStops($lat, $lon, $d); ?>
						<ul>
							<? foreach ($stops as $s): ?>
							<li>
								<div class="route">
									<span class="routename">Route: <?=$s["routename"]?></span>
									<div class="stop">
										<span class="stopname">Stop: <?=$s["stopname"]?></span>
										<? if (isset($s["times"]["stop"]["noPredictionMessage"])): ?>
											<span class="times">No buses</span>
										<? else: ?>
											<ul>
											<? foreach ($s["times"]["stop"]["pre"] as $t): ?>
												<li>
													<?=$t["pt"]?>
												</li>
											<? endforeach; ?>
											</ul>
										<? endif; ?>
									</div>
								</div>									
								<xmp><? //print_r($s); ?></xmp>
							</li>
							<? endforeach; ?>
						</ul>
					</div>
				<? endforeach; ?>
			<? endif; ?>
		</div>
	</body>
</html>