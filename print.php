<?php
include 'functions.php';
session_name('Store');
session_start();

if(isset($_POST['slist'])){
	$table = $_POST['from'];
	$service = $_POST['service'];

	echo "<p class='text-center'><a class='btn btn-primary' href='index.php' style='width:30%'>Back</a>&nbsp;<a style='width:30%' class='btn btn-primary' href='javascript:window.print()'>Print</a></p>";
	if($table == 'stock'){
		echo  "
		<table class='table table-striped'>
		<thead><tr style='font-weight:bold'>
		<th class='text-center'>Stock ID</th>
		<th class='text-center'>Product Code</th>
		<th class='text-center'>Quantity</th>
		<th class='text-center'>Price</th>
		<th class='text-center'>Date</th>
		</tr></thead>
		<tbody>";
		foreach($_POST['slist'] as $p){
			$result = queryMysql("SELECT * FROM stock WHERE stockID='$p'");
			if($result->num_rows !== 0){
			$row = $result->fetch_array(MYSQLI_ASSOC);
			echo "<tr><td class='text-center'>" . $row['stockID'] . "</td><td class='text-center'>" . $row['productCode'] . "</td><td class='text-center'>" . $row['quantity'] . "</td><td class='text-center'>$ " . $row['price'] . "</td><td class='text-center'>" . $row['stamp'] . "</td></tr>";
			}
		}
		echo "</tbody></table>";
	}else if($table == 'orders'){
		foreach($_POST['slist'] as $id => $p){
			$result = queryMysql("SELECT * FROM orders WHERE reportID='$p'");
			$row = $result->fetch_array(MYSQLI_ASSOC);

			$poslaju[$id] =  "<table border='0' style='width:100%; height:6.5cm;'>
						<tr>
							<td></td>
						</tr>
					</table>

					<table align='left' border='0' style='width:10.8cm; height:2.5cm; margin-left: 2.5cm; table-layout: fixed;'>
						<tr>
							<td align='left' style='height:15%; vertical-align:top; font-size:11px;'>" . strtoupper($row['name']) . "</td>
						</tr>
						<tr>
							<td align='left' style='height:40%; vertical-align:top; font-size:12px;'>".strtoupper($row['address']) . "</td>
						</tr>
					</table>

					<table align='left' border='0' style='width:15cm; height:0.5cm; margin-left: 5.6cm;'>
						<tr>
							<td align='left' style='font-size:18px; vertical-align:bottom;'>" . $row['contactNum'] . "</td>
						</tr>
					</table>

					<table align='left' border='0' style='width:7cm; height:1.7cm; margin-left: 16.5cm; table-layout: fixed;'>
						<tr>
							<td align='left' style='vertical-align:top; font-size:11.4px; word-wrap: break-word;'>" . $row['quantity'] . '*' . strtoupper($row['productCode']) . "</td>
						</tr>
					</table>
				";
				$skynet[$id] =  "
					<table align='left' border='0' style='width:10.05cm; height:3.8cm;'>
						<tr>
							<td></td>
						</tr>
					</table>

					<table align='left' border='0' style='width: 5cm; height: 2.2cm; float: left; margin-left: 6.3cm;'>
						<tr>
							<td align='left' style='font-size:11.4px; vertical-align:top;'>".$row['quantity'].'*'.strtoupper($row['productCode'])."</td>
						</tr>
					</table>

					<table align='left' border='0' style='width: 8.5cm; height: 2.2cm; margin-left: 1cm; float: left;'>
						<tr>
							<td align='left' style='font-size:12px; vertical-align: top;'>".strtoupper($row['address'])."</td>
						</tr>
					</table>

					<table align='left' border='0' style='width: 4.8cm; height: 1cm; margin-left: 12cm; float: left;'>
						<tr>
							<td align='left' style='font-size: 11px;'>".strtoupper($row['name'])."</td>
						</tr>
					</table>

					<table align='left' border='0' style='width: 5cm; height: 1cm; float: left;'>
						<tr>
							<td align='center' style='font-size: 11px;'>".$row['contactNum']."</td>
						</tr>
					</table>
				";
				$gdex[$id] = "
				
					<table align='left' border='0' style='width:10.05cm; height:3.5cm;'>
						<tr>
							<td></td>
						</tr>
					</table>

					<table align='right' border='0' style='width: 5cm; height: 1.6cm; float: left; margin-left: 6.2cm;'>
						<tr>
							<td align='right' style='font-size:11.4px; vertical-align:center;'>".$row['quantity'].'*'.strtoupper($row['productCode'])."</td>
						</tr>
					</table>

					<table align='left' border='0' style='width: 8.5cm; height: 1.6cm; float: left; margin-left: 0.8cm;'>
						<tr>
							<td align='left' style='font-size:12px; vertical-align: top;'>".strtoupper($row['address'])."</td>
						</tr>
					</table>

					<table align='left' border='0' style='width: 4.7cm; height: 1cm; margin-left: 13cm; float: left;'>
						<tr>
							<td align='left' style='font-size: 11px; vertical-align: top;'>".strtoupper($row['name'])."</td>
						</tr>
						<tr>
							<td align='left' style='font-size: 11px; vertical-align: top;'>".$row['contactNum']."</td>
						</tr>
					</table>
				";

		}
		if($service == 'poslaju'){
			foreach($poslaju as $post){
				echo $post;
			}
		}else if($service == 'skynet'){
			foreach($skynet as $sky){
				echo $sky;
			}
		}else if($service == 'gdex'){
			foreach($gdex as $gd){
				echo $gd;
			}
		}else echo 'Service not selected';
	}
}else{
echo "<!DOCTYPE html>
<html>
	<head>
		<title>Inventory System v2.0</title>
			<meta charset='utf-8'>
			<meta http-equiv='X-UA-Compatible' content='IE=edge'>
			<meta name='viewport' content='width=device-width, inital-scale=1.0'>
			<link rel='stylesheet' href='css/bootstrap.css'>
			<link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css'>
			<link rel='stylesheet' href='css/style.css?7.3'>
			<link rel='shortcut icon' href='img/dd.ico' type='image/x-icon'>
			<style type='text/css' media='print'>
			html,body{
				font-famiily: 'Times New Roman';
			}
			@page {
			size: 21cm 10.15cm;
			}
			</style>
	</head>
	<body>
	<script src='js/jquery-3.1.1.js'></script>
	<script src='js/bootstrap.js'></script>
	<div id='printableData' class='col-xs-12'>
	<h1>Nothing to display, please select data and try again.</h1>
	</div>
	<script src='js/style.js?6.3'></script></body></html>";
}

?>