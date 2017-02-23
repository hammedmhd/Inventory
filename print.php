<?php
include 'functions.php';
session_name('Store');
session_start();

if(isset($_POST['slist'])){
	$table = $_POST['from'];
	if($table == 'stock'){
		echo  "<p class='text-center'><a class='btn btn-primary' href='index.php' style='width:30%'>Back</a>&nbsp;<a style='width:30%' class='btn btn-primary' href='javascript:window.print()'>Print</a></p>
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
		echo "<p class='text-center'><a class='btn btn-primary' href='index.php' style='width:30%'>Back</a>&nbsp;<a style='width:30%' class='btn btn-primary' href='javascript:window.print()'>Print</a></p>";
		foreach($_POST['slist'] as $p){
		$result = queryMysql("SELECT * FROM orders WHERE reportID='$p'");
		$row = $result->fetch_array(MYSQLI_ASSOC);
		if($result->num_rows !== 0){
		echo "<table border='0' style='width:100%; height:6.5cm;'>
					<tr>
						<td></td>
					</tr>
				</table>

				<table align='left' border='0' style='width:10.8cm; height:2.5cm; margin-left: 2.5cm; table-layout: fixed;'>
					<tr>
						<td align='left' style='height:15%; vertical-align:top; font-size:11px;'>".strtoupper($row['name'])."</td>
					</tr>
					<tr>
						<td align='left' style='height:40%; vertical-align:top; font-size:12px;'>".strtoupper($row['address'])."</td>
					</tr>
				</table>

				<table align='left' border='0' style='width:15cm; height:0.5cm; margin-left: 5.6cm;'>
					<tr>
						<td align='left' style='font-size:18px; vertical-align:bottom;'>".$row['contactNum']."</td>
					</tr>
				</table>

				<table align='left' border='0' style='width:7cm; height:1.7cm; margin-left: 16.5cm; table-layout: fixed;'>
					<tr>
						<td align='left' style='vertical-align:top; font-size:11px; word-wrap: break-word;'>".$row['quantity'].'*'.$row['productCode']."</td>
					</tr>
				</table>
			";
			}
			}
		/*echo "<table class='table table-striped'>
		<thead><tr style='font-weight:bold'>
		<th class='text-center'>ReportID</th>
		<th class='text-center'>Import Date</th>			
		<th class='text-center'>Name</th>
		<th class='text-center'>Address</th>
		<th class='text-center'>Phone</th>
		<th class='text-center'>Sales Date</th>
		<th class='text-center'>Item</th>
		<th class='text-center'>Source</th>
		<th class='text-center'>Payment</th>
		</tr></thead>
		<tbody>";
		foreach($_POST['slist'] as $p){
			$result = queryMysql("SELECT * FROM orders WHERE reportID='$p'");
			if($result->num_rows !== 0){
			$row = $result->fetch_array(MYSQLI_ASSOC);
			echo "<tr>
				<td class='text-center'>" . $row['reportID'] . "</td>
				<td class='text-center'>" . $row['date'] ."</td>
				<td class='text-center'>" .  strtoupper($row['name']) . "</td>
				<td class='text-center'>" . strtoupper($row['address']) . "</td>
				<td class='text-center'>" . $row['contactNum'] . "</td>
				<td class='text-center'>" . $row['date'] . "</td>
				<td class='text-center'>" . strtoupper($row['productCode']) . "</td>
				<td class='text-center'>" . strtoupper($row['bank']) . "</td>
				<td class='text-center'>" . $row['totalPrice'] . "</td>
				</tr>";
			}
		}
		echo "</tbody></table><p class='text-center'><a class='fa fa-print fa-2x' href='javascript:window.print()'></a><a class='fa fa-home fa-2x' href='index.php'></a></p>";
	}*/
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