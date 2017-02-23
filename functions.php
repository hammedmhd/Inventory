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
		reportID BIGINT NOT NULL,
		platform VARCHAR(100),
		name VARCHAR(250) NOT NULL,
		address VARCHAR(250) NOT NULL,
		contactNum VARCHAR(50) NOT NULL,
		quantity BIGINT NOT NULL,
		productCode VARCHAR(200) NOT NULL,
		date VARCHAR(100) NOT NULL,
		trackingNum VARCHAR(100) NOT NULL,
		shippingStatus INT(1) NOT NULL,
		remarks VARCHAR(200),
		bank VARCHAR(100),
		totalPrice VARCHAR(100) NOT NULL,
		UNIQUE KEY (reportID)'
		);
			
		createTable('stock', '
		stockID BIGINT NOT NULL,
	    productCode varchar(200) NOT NULL,
	    quantity bigint(20) DEFAULT NULL,
	    price VARCHAR(50),
	    stamp VARCHAR(50) NOT NULL,
	    UNIQUE KEY (productCode),
	    UNIQUE KEY (stockID)'
		);

		createTable('members', '
		Username varchar(50) NOT NULL UNIQUE KEY,
		Password varchar(10) NOT NULL'
		);

		$result = queryMysql("INSERT INTO members VALUES('admin','admin')");
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