<?php
include ('functions.php');
session_name('Store');
session_start();
$appname = 'Inventory System v2.0';

if(isset($_POST['user'])){
	$user = sanitizeString($_POST['user']);
	$pass = sanitizeString($_POST['pass']);

	$result = queryMysql("SELECT * FROM members WHERE Username='$user' AND Password='$pass'");
	if($result->num_rows !== 0){
		$loggedIn = true;
		$_SESSION['user'] = $user;
		$_SESSION['pass'] = $pass;
	}else{
		$loggedIn = false;
	}
}

if(isset($_GET['logout'])){
	destroySession();
}

if(isset($_SESSION['user'])){
$result = queryMysql("SELECT * FROM orders WHERE shippingStatus='0'");
$orders = $result->num_rows;
$result = queryMysql("SELECT * FROM orders WHERE shippingStatus='1'");
$shipping = $result->num_rows;
$result = queryMysql("SELECT * FROM orders WHERE shippingStatus='2'");
$complete = $result->num_rows;
$result = queryMysql("SELECT * FROM stock");
$stock = $result->num_rows;
echo "<!DOCTYPE html>
<html>
	<head>
		<title>$appname</title>
			<meta charset='utf-8'>
			<meta http-equiv='X-UA-Compatible' content='IE=edge'>
			<meta name='viewport' content='width=device-width, inital-scale=1.0'>
			<link rel='stylesheet' href='css/bootstrap.css'>
			<link rel='stylesheet' href='css/style.css?7.3'>
			<link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css'>
			<link rel='shortcut icon' href='img/dd.ico' type='image/x-icon'>
	</head>
	<body style='padding-top:60px'>
	<script src='js/jquery-3.1.1.js'></script>
	<script src='js/bootstrap.js'></script>
		<nav class='navbar navbar-default navbar-fixed-top special'>
			<div class='navbar-header'>
				<button type='button' class='navbar-toggle collapsed' data-toggle='collapse' data-target='#dropmenu' aria-expanded='false'>
				<span class='icon-bar'></span>
				<span class='icon-bar'></span>
				<span class='icon-bar'></span>
				</button>
				<p class='navbar-brand'><span id='header'>Inventory System</p>
			</div>
			<div class='collapse navbar-collapse' id='dropmenu'>
				<ul class='nav navbar-nav navbar-right'>
					<li class='active'><a href='index.php' class='fa fa-user-circle fa-2x'><span style='font-size:15px'>" . ucfirst($_SESSION['user']) . "</span></a></li>
					<li><a href='stock.php' class='linkme fa fa-database fa-2x'>&nbsp;<span class='badge blue'>$stock</span></a></li>
					<li><a href='orders.php' class='linkme fa fa-exchange fa-2x'>&nbsp;<span class='badge green'>$complete</span>&nbsp;<span class='badge gold'>$shipping</span>&nbsp;<span class='badge red'>$orders</span></a></li>
					<li><a class='fa fa-sign-out fa-2x' href='index.php?logout'></a></li>
				</ul>
			</div>
		</nav>
		<img id='backgroundpic' src='img/b.jpg'>
		<p style='width:100%; display:none; position:fixed; z-index:1' class='text-center alert alert-danger' id='lstatus'></p>
		 <div id='userView'>
	 	 </div>
		<script src='js/style.js?7.2'></script></body></html>";
}else{
echo "<!DOCTYPE html>
<html>
	<head>
		<title>$appname</title>
			<meta charset='utf-8'>
			<meta http-equiv='X-UA-Compatible' content='IE=edge'>
			<meta name='viewport' content='width=device-width, inital-scale=1.0'>
			<link rel='stylesheet' href='css/bootstrap.css'>
			<link rel='stylesheet' href='css/style.css?7.3'>
			<link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css'>
			<link rel='shortcut icon' href='img/dd.ico' type='image/x-icon'>
	</head>
	<body>
	<script src='js/jquery-3.1.1.js'></script>
	<script src='js/bootstrap.js'></script>
		<nav class='navbar navbar-default'>
			<div class='navbar-header'>
				<button type='button' class='navbar-toggle collapsed' data-toggle='collapse' data-target='#dropmenu' aria-expanded='false'>
				<span class='icon-bar'></span>
				<span class='icon-bar'></span>
				<span class='icon-bar'></span>
				</button>
				<p class='navbar-brand'>Store</p>
			</div>
			<div class='collapse navbar-collapse' id='dropmenu'>
				<ul class='nav navbar-nav navbar-right'>
					<li>Sign In to Access App</li>
				</ul>
			</div>
		</nav>
		<img id='backgroundpic' src='img/Background-black.jpg'>
		<div class='container header'>
		<section class='col-xs-6 col-xs-offset-3 login'>
			<form autocomplete='off' id='logmein' method='post' action='index.php' class='text-center col-xs-12' style='margin-top:40px'>
			<input autofocus='on' class='text-center box-me' style='padding:6px 10px 6px 10px; width:100%; border-radius:3px; border:1px solid lightgrey' type='text' name='user' placeholder='Username'><br><br>
			<input class='text-center box-me' style='padding:6px 10px 6px 10px; width:100%; border-radius:3px; border:1px solid lightgrey' type='password' name='pass' placeholder='Password'>
			<input type='submit' name='logIn' value='Sign In' class='btn btn-default submitlogin'>
			</form>
		</section>
		</div>
		<script src='js/style.js?4.'></script></body></html>";
}


?>