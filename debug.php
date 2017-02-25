<?php
include 'functions.php';
//COMBINING CUSTOMER ORDER ON PRINT SOLUTION
/*$result = queryMysql("SELECT DISTINCT(name) FROM orders ORDER BY reportID");
$rows = $result->num_rows;
for($i = 0; $i < $rows; $i++){
	$result->data_seek($i);
	$row = $result->fetch_array(MYSQLI_ASSOC);
	$names = $row['name'];
	$name[$i] = array(
		'name' => $row['name']
		);
}

foreach($name as $id => $s){
	$result = queryMysql("SELECT * FROM orders WHERE name='".$name[$id]['name']."' ORDER BY reportID");
	$rows = $result->num_rows;
	$object = array(
		'name' => $name[$id]['name']
		);
	echo $s['name'] . ' ';
	for($j = 0; $j < $rows; $j++){
		$result->data_seek($j);
		$row = $result->fetch_array(MYSQLI_ASSOC);
		echo $row['productCode'].' ';
	}
	echo '<br>';
}
*/
print_r($_COOKIE);
?>