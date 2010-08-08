<? 
require "../config.php";
require "../lib/database.php";
$routes = fetcharray("select * from routes");	
?>
<html>
	<head>
		<title>CTA Bus Tracker</title>
		<link rel="stylesheet" href="/css/style.css"></link>
		<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js"></script>
	</head>
	<body>
		<div id="wrapper">
			<h1>Rebuild Database</h1>

			<table>
				<tr>
					<th>Route</th>
					<th>Status</th>
				</tr>
				<? foreach($routes as $r): ?>
				<tr>
					<td><span class="number"><?=$r["number"]?></span> <?=$r["name"]?></td>
					<td>status</td>
				</tr>
				<? endforeach ?>
			</table>
		</div>
	</body>
</html>