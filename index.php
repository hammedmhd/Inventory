<?php

$appname = 'Inventory System v1.0';

$header = <<<HTML
<!DOCTYPE html>
<html>
	<head>
		<title>$appname</title>
			<meta charset='utf-8'>
			<meta http-equiv='X-UA-Compatible' content='IE=edge'>
			<meta name='viewport' content='width=device-width, inital-scale=1.0'>
			<link rel='stylesheet' href='css/style.css?10.2'>
			<link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css'>
			<link href="https://fonts.googleapis.com/css?family=Tenali+Ramakrishna" rel="stylesheet">
	</head>
	<body>
		<script src='js/jquery-3.1.1.js'></script>
		<script src='js/style.js?15.3'></script>
		<div id='addS2'>
		<span id='close2' onclick='closeMe2()'>&#10008;</span>
		<p id='titl'>Place a new Order</p>
		<form id='addneworder' method='get' autocomplete='off'>
		<table id='table2'>
		<tr><td style='float:right'>Customer's Name: </td><td><input style='max-width:300px' type='text' name='neworder'></td></tr>
		<tr><td style='float:right'>Product Name: </td><td><input style='max-width:300px' type='text' name='newproduct'></td></tr>
		<tr><td style='float:right'>Quantity: </td><td><input style='max-width:300px' type='text' name='newquantity2'></td></tr>
		<tr><td style='float:right'>Serial Code: </td><td><input style='max-width:300px' type='text' name='newserial'></td></tr>
		<tr><td style='float:right'>Remarks: </td><td><input style='max-width:300px' type='text' name='newremarks'></td></tr>
		<tr><td></td><td><input class='me' onclick='addOrderSubmit()' type='submit'></td></tr>
		</table></form></div>
		<div id='addS'>
		<span id='close' onclick='closeMe()'>&#10008;</span>
		<p id='titl'>Appending New Stock Item</p>
		<form id='addnewstock' method='get' autocomplete='off'>
		<table id='table2'>
		<tr><td style='float:right'>Item name: </td><td><input autofocus='on' style='max-width:300px' type='text' name='newitem'></td></tr>
		<tr><td style='float:right'>Serial Code: </td><td><input style='max-width:300px' type='text' name='newserialcode'></td></tr>
		<tr><td style='float:right'>Item quantity: </td><td><input style='max-width:300px' type='text' name='newquantity'></td></tr>
		<tr><td style='float:right'>Item Price: </td><td><input style='max-width:300px' type='text' name='newprice'></td></tr>
		<tr><td style='float:right'>Item Remarks: </td><td><input style='max-width:300px' type='text' name='newremarks'></td></tr>
		<tr><td></td><td><input class='me' onclick='addStockSubmit()' type='submit'></td></tr>
		</table></form></div>
		<table class='navbar'>
			<tr>
			<td id='Home' style='width:40px'><a href='home.php'><span class='fa fa-home'></span></a></td>
			<td style='text-align:center'><span id='header'>Inventory System</span></td>
			<td style='width:40px'><span id='menu' onclick='menuToggle()'>&#9776;</span></td>
			</tr>
		</table>
		<ul class='list'>
				<li><a href='items.php'>Stock Items</a></li>
				<li><a href='orders.php'>Orders</a></li>
				<li><span onclick='displayMessage()'>Print Data</span></li>
		</ul>
		<table id='signs' style='width:100%; position:absolute; table-layout:fixed'><th><span id='message'></span></th></table>
		<div id='try' class='container' style='position:relative; margin:70px auto'>
HTML;

$footer = <<<HTML
</div>
</body>
</html>
HTML;

echo $header;

echo $footer;
/*<img class='back' src='warehouse.jpg'>*/
?>