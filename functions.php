<?php
date_default_timezone_set("Asia/Kuala_Lumpur");
$dbhost = 'localhost';
$dbuser = 'root';
$dbpass = '';

$connection = new mysqli($dbhost, $dbuser, $dbpass);

if($connection->connect_error) die($connection->connection_error);

function createTable($name, $query)
{
	queryMysql("CREATE TABLE IF NOT EXISTS $name($query)");
}

function queryMysql($query)
{
   global $connection;
   $result = $connection->query($query);
   if(!$result) die($connection->error);
   return $result;
}
//setup of Stock and Orders table
$result = queryMysql("SHOW DATABASES LIKE 'InventorySystem'");
$rows = $result->num_rows;
if($rows == 0){
	$result = queryMysql("CREATE DATABASE InventorySystem");
	$result = queryMysql("USE InventorySystem");
	$result = queryMysql("SHOW TABLES LIKE 'orders'");
	if($result->num_rows == 0){
		createTable('orders', '
		orderID bigint(20) NOT NULL,
		customer varchar(200) DEFAULT NULL,
		product varchar(255) DEFAULT NULL,
		quantity bigint(20) NOT NULL,
		serialCode varchar(100) DEFAULT NULL,
		shippingStatus int(1) NOT NULL,
		remarks varchar(255) DEFAULT NULL,
		stamp varchar(100) DEFAULT NULL,
		PRIMARY KEY (orderID)'
		);
			
		createTable('stock', '
	    stockID bigint(20) NOT NULL,
	    serialCode varchar(100) NOT NULL,
	    name varchar(255) DEFAULT NULL,
	    quantity bigint(20) DEFAULT NULL,
	    price double NOT NULL,
	    remarks varchar(255) DEFAULT NULL,
	    stamp varchar(100) DEFAULT NULL,
	    PRIMARY KEY (stockID),
		UNIQUE KEY (stockID),
	    UNIQUE KEY (serialCode)'
		);
}
}else {
	$result = queryMysql("USE InventorySystem");
}


function destroySession()
{
	$_SESSION=array();

	if (session_id() != "" || isset($_COOKIE[session_name()]))
	setcookie(session_name(), '', time()-2592000, '/');

	session_destroy();
}

  function sanitizeString($var)
  {
    global $connection;
    $var = strip_tags($var);
    $var = htmlentities($var);
    $var = stripslashes($var);
    return $connection->real_escape_string($var);
  }


?>