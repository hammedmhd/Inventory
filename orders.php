<?php
require 'functions.php';

if(isset($_POST['statusUpdate'])){
	$status = $_POST['statusUpdate'];
	$id = $_POST['id'];
	if($status == 0){
		$result = queryMysql("UPDATE orders SET shippingStatus='$status' WHERE orderID='$id'");
	}else if($status == 1){
		$result = queryMysql("UPDATE orders SET shippingStatus='$status' WHERE orderID='$id'");
	}
}

if(isset($_POST['del'])){
	$id = $_POST['del'];
	foreach($id as $i){
	$result = queryMysql("DELETE FROM orders WHERE orderID='$i'");
	}
}
	
if(isset($_GET['neworder'])){
	$result = queryMysql("SELECT * FROM orders");
	$rows = $result->num_rows;
		$orderID = $rows + 1;
		$status = 0;
		$customer = mysql_real_escape_string($_GET['neworder']);
		$productname = mysql_real_escape_string($_GET['newproduct']);
		$quantity = mysql_real_escape_string($_GET['newquantity2']);
		$serialcode = mysql_real_escape_string($_GET['newserial']);
		$remarks = mysql_real_escape_string($_GET['newremarks']);
	
	$stamp = date('d.m.Y|h:ia');

	$result = queryMysql("SELECT * FROM stock WHERE serialCode='$serialcode'");
	if($result->num_rows !== 0){
	$result = queryMysql("SELECT * FROM orders WHERE orderID='$orderID'");
	if($result->num_rows == 0){
		$result = queryMysql("INSERT INTO orders VALUES('$orderID','$customer','$productname','$quantity','$serialcode','$status','$remarks','$stamp')");
	}
	}
}
	
if(isset($_POST['customer'])){
$num = count($_POST);
if($num <= 1){
	$orderID = $_POST['orderID'];
	$customer = $_POST['customer'];
	$product = $_POST['product'];
	$quantity = $_POST['quantity'];
	$serialCode = $_POST['serialCode'];
	$remarks = $_POST['remarks'];
	if(isset($_POST['remove'])){
		$remove = $_POST['remove'];
		$result = queryMysql("DELETE FROM orders WHERE orderID='$remove'");
	}else{
	$result = queryMysql("UPDATE orders SET customer='$customer' WHERE orderID='$orderID'");
	$result = queryMysql("UPDATE orders SET product='$product' WHERE orderID='$orderID'");
	$result = queryMysql("UPDATE orders SET quantity='$quantity' WHERE orderID='$orderID'");
	$result = queryMysql("UPDATE orders SET serialCode='$serialCode' WHERE orderID='$orderID'");
	$result = queryMysql("UPDATE orders SET remarks='$remarks' WHERE orderID='$orderID'");	
	}
}else{
	$i = 0;
	while(!empty($_POST)){
		$orderID = $_POST['orderID'][$i];
		$customer = $_POST['customer'][$i];
		$product = $_POST['product'][$i];
		$serialCode = $_POST['serialCode'][$i];	
		$quantity = $_POST['quantity'][$i];
		$remarks = $_POST['remarks'][$i];
		if(isset($_POST['remove'][$i])){
			$remove = $_POST['remove'][$i];
			$result = queryMysql("DELETE FROM orders WHERE orderID='$remove'");
			$i++;
		}else {
			$result = queryMysql("SELECT * FROM orders");
			$rows = $result->num_rows;
			for($j = 0; $j < $rows; $j++){
				$result->data_seek($j);
				$row = $result->fetch_array(MYSQL_ASSOC);
				$order[$j] = array(
				'orderID' => $row['orderID'],
				'customer' => $row['customer'],
				'product' => $row['product'],
				'quantity' => $row['quantity'],
				'serialCode' => $row['serialCode'],
				'remarks' => $row['remarks'],
				'status' => $row['shippingStatus']
				);
			}
			foreach($order as $o){
				if($orderID == $o['orderID']){
					if($customer !== $o['customer']){
						$result = queryMysql("UPDATE orders SET customer='$customer' WHERE orderID='$orderID'");
					}
					if($product !== $o['product']){
						$result = queryMysql("UPDATE orders SET product='$product' WHERE orderID='$orderID'");
					}
					if($quantity !== $o['quantity']){
						$result = queryMysql("UPDATE orders SET quantity='$quantity' WHERE orderID='$orderID'");
					}	
					if($serialCode !== $o['serialCode']){
						$result = queryMysql("UPDATE orders SET serialCode='$serialCode' WHERE orderID='$orderID'");
					}
					if($remarks !== $o['remarks']){
						$result = queryMysql("UPDATE orders SET remarks='$remarks' WHERE orderID='$orderID'");	
					}
				}
			}
		$i++;
		}
	}
}
}

if(isset($_FILES['file']['name'])){
	//csv file content 
	$file = $_FILES['file']['tmp_name'];
	$handle = fopen($file, 'r');
	while(($data = fgetcsv($handle, 1000, ',')) !== false){
		$result = queryMysql("SELECT * FROM orders");
		$num = $result->num_rows;
		$orderID = $num + 1;
		$customer = $data[0];
		$product = $data[1];
		$quantity = $data[2];
		$serialCode = $data[3];
		$remarks = $data[4];
		$status = 0;
		$stamp = date('d.m.Y|h:ia');
	
	$result = queryMysql("SELECT * FROM stock WHERE serialCode='$serialCode'");
		if($result->num_rows !== 0){
	$result = queryMysql("INSERT INTO orders VALUES('$orderID','$customer','$product','$quantity','$serialCode','$status','$remarks','$stamp')");
		}
		
	}
	
	
		
}

if(isset($_POST['asc'])){//filter display by choice ascending
	$asc = $_POST['asc'];
	$result = queryMysql("SELECT * FROM orders ORDER BY $asc ASC");
	$rows = $result->num_rows;
echo "<span id='searchhide' onclick='hidesearch()'>&minus;</span><form id='searching' method='post' enctype='multipart/form-data'>
		<table class='search'>
		<th style='text-align:left; color:rgba(255,255,255,0.8); text-shadow:1px 1px 20px rgba(255,255,255,0.8)'><span style='font-size:21px'>Search By:</span>	
		<select name='field'>
		<option value='orderID'>Order ID</option>
		<option value='customer'>Customer Name</option>
		<option value='product'>Product Name</option>
		<option value='quantity'>Quantity</option>
		<option value='serialCode'>Serial Code</option>
		<option value='shippingStatus'>Shipping Status</option>
		<option value='remarks'>Remarks</option>
		<option value='stamp'>Stamp</option>
		</select>&nbsp;<input style='outline:none; box-shadow: 1px 1px 20px rgba(255,255,255,0.8); background-color:rgba(0, 204, 167, 1); width:130px' type='text' name='searchfield'>
		<input id='searchme' type='submit' name='filterme' value='Search' onclick='sendFiltered()'>
		<span id='refreshp' url='orders.php' name='Orders' style='color:rgba(255,255,255,0.8); text-shadow:1px 1px 20px rgba(255,255,255,0.8); cursor:pointer; float:right' onclick='loadthis(this)'>&#8475;efresh</span>
		</th></table></form>";
echo "<div class='section'><span style='color:rgba(255,255,255,0.8); font-size:21px; text-shadow:1px 1px 20px rgba(255,255,255,0.8)'>Upload .CSV <span id='change' style='cursor:pointer; font-size:21px; color:white' onclick='displayUpload()'>&plus;</span></span>
		<form id='csvupload' method='post' enctype='multipart/form-data'>
			<table style='transform:translate(10px,0)' width='20%'><tr>
			<tr><td><input id='file' type='file' name='file'></td></tr>
			<tr><td style='text-align:right'><input style='width:100px' id='uploadcsv' type='submit' onclick='uploadCSV()' value='Upload'></td></tr>
			</table>
		</form></div>";

echo "<form id='orderlist' method='post' autocomplete='off'>";
echo "<table class='stock' cellspacing='0'>
		<tr>
		<th><span id='addStock' onclick='addOrder()'>&plus;</span><span id='orderID' onclick='orderByMe(this.id)'>Order ID</span></th>
		<th><span id='customer' onclick='orderByMe(this.id)'>Customer Name</span></th>
		<th><span id='product' onclick='orderByMe(this.id)'>Product Name</span></th>
		<th><span id='quantity' onclick='orderByMe(this.id)'>Quantity</span></th>
		<th><span id='serialCode' onclick='orderByMe(this.id)'>Serial Code</span></th>
		<th><span id='shippingStatus' onclick='orderByMe(this.id)'>Shipping Status</span></th>
		<th><span id='remarks' onclick='orderByMe(this.id)'>Remarks</span></th>
		<th><span id='stamp' onclick='orderByMe(this.id)'>Stamp</span></th></tr>";
		
for($i = 0; $i < $rows; $i++){
	$result->data_seek($i);
	$row = $result->fetch_array(MYSQL_ASSOC);
	if($row['shippingStatus'] == 0){
		$color = 'color:rgba(209, 0, 0, 1)';
		$tshadow = 'text-shadow:0 0 20px rgba(255,255,255,0.8)';
		$background = 'background-color:red';
		$row['shippingStatus'] = 'IN PROGRESS';
	}else if($row['shippingStatus'] == 1){
		$color = 'color:limegreen';
		$tshadow = 'text-shadow:0 0 20px limegreen';
		$background = 'background-color:limegreen';
		$row['shippingStatus'] = 'DELIVERED';
	}
	echo "<tr id='" . $row['orderID'] . "delete' ondblclick='promptDelete(this.id)'>
			<td><input id='remove" . $row['orderID'] . "' type='checkbox' name='remove[]' value='" . $row['orderID'] . "'><label class='checkbox' for='remove" . $row['orderID'] . "'></label>&nbsp;" . $row['orderID'] . "</td>
			<input type='hidden' name='orderID[]' value='" . $row['orderID'] . "'> 
			<td><input type='text' name='customer[]' style='background-color:rgba(0,0,0,0)' value='" . $row['customer'] . "'></td>
			<td><input type='text' name='product[]' style='background-color:rgba(0,0,0,0)' value='" . $row['product'] . "'></td>
			<td><input type='text' name='quantity[]' style='background-color:rgba(0,0,0,0)' value='" . $row['quantity'] . "'></td>
			<td><input type='text' name='serialCode[]' style='background-color:rgba(0,0,0,0)' value='" . $row['serialCode'] . "'></td>
			<td><input id='check" . $row['orderID'] . "' name='" . $row['orderID'] . "' status='" . $row['shippingStatus'] . "' value='ok' type='checkbox' onchange='statusUpdate(this)' style='background-color:rgba(0,0,0,0)'><label id='lab" . $row['orderID'] . "' style='$background' for='check" . $row['orderID'] . "'></label>&nbsp;<span id='output" . $row['orderID'] . "' style='font-weight:bold; font-size:20px; $color; $tshadow'>" . $row['shippingStatus'] . "</span></td>
			<td><input type='text' name='remarks[]' style='background-color:rgba(0,0,0,0)' value='" . $row['remarks'] . "'></td>
			<td><input type='text' style='background-color:rgba(0,0,0,0)' value='" . $row['stamp'] . "'></td>
		  </tr>";
}

echo "<td></td><td></td><td></td><td></td><td></td><td></td><td></td><td><input onclick='updateOrder()' type='submit' value='&#10004;'></td></form></table>";
}
else if(isset($_POST['desc'])){//filter display by choice descending
	$desc = $_POST['desc'];
	$result = queryMysql("SELECT * FROM orders ORDER BY $desc DESC");
	$rows = $result->num_rows;
	echo "<span id='searchhide' onclick='hidesearch()'>&minus;</span><form id='searching' method='post' enctype='multipart/form-data'>
		<table class='search'>
		<th style='text-align:left; color:rgba(255,255,255,0.8); text-shadow:1px 1px 20px rgba(255,255,255,0.8)'><span style='font-size:21px'>Search By:</span>	
		<select name='field'>
		<option value='orderID'>Order ID</option>
		<option value='customer'>Customer Name</option>
		<option value='product'>Product Name</option>
		<option value='quantity'>Quantity</option>
		<option value='serialCode'>Serial Code</option>
		<option value='shippingStatus'>Shipping Status</option>
		<option value='remarks'>Remarks</option>
		<option value='stamp'>Stamp</option>
		</select>&nbsp;<input style='outline:none; box-shadow: 1px 1px 20px rgba(255,255,255,0.8); background-color:rgba(0, 204, 167, 1); width:130px' type='text' name='searchfield'>
		<input id='searchme' type='submit' name='filterme' value='Search' onclick='sendFiltered()'>
		<span id='refreshp' url='orders.php' name='Orders' style='color:rgba(255,255,255,0.8); text-shadow:1px 1px 20px rgba(255,255,255,0.8); cursor:pointer; float:right' onclick='loadthis(this)'>&#8475;efresh</span>
		</th></table></form>";
echo "<div class='section'><span style='color:rgba(255,255,255,0.8); font-size:21px; text-shadow:1px 1px 20px rgba(255,255,255,0.8)'>Upload .CSV <span id='change' style='cursor:pointer; font-size:21px; color:white' onclick='displayUpload()'>&plus;</span></span>
		<form id='csvupload' method='post' enctype='multipart/form-data'>
			<table style='transform:translate(10px,0)' width='20%'><tr>
			<tr><td><input id='file' type='file' name='file'></td></tr>
			<tr><td style='text-align:right'><input style='width:100px' id='uploadcsv' type='submit' onclick='uploadCSV()' value='Upload'></td></tr>
			</table>
		</form></div>";

echo "<form id='orderlist' method='post' autocomplete='off'>";
echo "<table class='stock' cellspacing='0'>
		<tr>
		<th><span id='addStock' onclick='addOrder()'>&plus;</span><span id='orderID' onclick='orderByMe(this.id)'>Order ID</span></th>
		<th><span id='customer' onclick='orderByMe(this.id)'>Customer Name</span></th>
		<th><span id='product' onclick='orderByMe(this.id)'>Product Name</span></th>
		<th><span id='quantity' onclick='orderByMe(this.id)'>Quantity</span></th>
		<th><span id='serialCode' onclick='orderByMe(this.id)'>Serial Code</span></th>
		<th><span id='shippingStatus' onclick='orderByMe(this.id)'>Shipping Status</span></th>
		<th><span id='remarks' onclick='orderByMe(this.id)'>Remarks</span></th>
		<th><span id='stamp' onclick='orderByMe(this.id)'>Stamp</span></th></tr>";
		
for($i = 0; $i < $rows; $i++){
	$result->data_seek($i);
	$row = $result->fetch_array(MYSQL_ASSOC);
	if($row['shippingStatus'] == 0){
		$color = 'color:rgba(209, 0, 0, 1)';
		$tshadow = 'text-shadow:0 0 20px rgba(255,255,255,0.8)';
		$background = 'background-color:red';
		$row['shippingStatus'] = 'IN PROGRESS';
	}else if($row['shippingStatus'] == 1){
		$color = 'color:limegreen';
		$tshadow = 'text-shadow:0 0 20px limegreen';
		$background = 'background-color:limegreen';
		$row['shippingStatus'] = 'DELIVERED';
	}
	echo "<tr id='" . $row['orderID'] . "delete' ondblclick='promptDelete(this.id)'>
			<td><input id='remove" . $row['orderID'] . "' type='checkbox' name='remove[]' value='" . $row['orderID'] . "'><label class='checkbox' for='remove" . $row['orderID'] . "'></label>&nbsp;" . $row['orderID'] . "</td>
			<input type='hidden' name='orderID[]' value='" . $row['orderID'] . "'> 
			<td><input type='text' name='customer[]' style='background-color:rgba(0,0,0,0)' value='" . $row['customer'] . "'></td>
			<td><input type='text' name='product[]' style='background-color:rgba(0,0,0,0)' value='" . $row['product'] . "'></td>
			<td><input type='text' name='quantity[]' style='background-color:rgba(0,0,0,0)' value='" . $row['quantity'] . "'></td>
			<td><input type='text' name='serialCode[]' style='background-color:rgba(0,0,0,0)' value='" . $row['serialCode'] . "'></td>
			<td><input id='check" . $row['orderID'] . "' name='" . $row['orderID'] . "' status='" . $row['shippingStatus'] . "' value='ok' type='checkbox' onchange='statusUpdate(this)' style='background-color:rgba(0,0,0,0)'><label id='lab" . $row['orderID'] . "' style='$background' for='check" . $row['orderID'] . "'></label>&nbsp;<span id='output" . $row['orderID'] . "' style='font-weight:bold; font-size:20px; $color; $tshadow'>" . $row['shippingStatus'] . "</span></td>
			<td><input type='text' name='remarks[]' style='background-color:rgba(0,0,0,0)' value='" . $row['remarks'] . "'></td>
			<td><input type='text' style='background-color:rgba(0,0,0,0)' value='" . $row['stamp'] . "'></td>
		  </tr>";
}

echo "<td></td><td></td><td></td><td></td><td></td><td></td><td></td><td><input onclick='updateOrder()' type='submit' value='&#10004;'></td></form></table>";
}
else if(isset($_POST['field'])){
	$fieldSelection = $_POST['field'];	
	$field = strtoupper($_POST['searchfield']);
	
	if($fieldSelection == 'shippingStatus'){
		if($field == 'IN PROGRESS'){
			$field = 0;
		}else if($field == 'DELIVERED'){
			$field = 1;
		}
	}
	
	$result = queryMysql("SELECT * FROM orders WHERE $fieldSelection='$field'");
	$rows = $result->num_rows;
echo "<span id='searchhide' onclick='hidesearch()'>&minus;</span><form id='searching' method='post' enctype='multipart/form-data'>
		<table class='search'>
		<th style='text-align:left; color:rgba(255,255,255,0.8); text-shadow:1px 1px 20px rgba(255,255,255,0.8)'><span style='font-size:21px'>Search By:</span>	
		<select name='field'>
		<option value='orderID'>Order ID</option>
		<option value='customer'>Customer Name</option>
		<option value='product'>Product Name</option>
		<option value='quantity'>Quantity</option>
		<option value='serialCode'>Serial Code</option>
		<option value='shippingStatus'>Shipping Status</option>
		<option value='remarks'>Remarks</option>
		<option value='stamp'>Stamp</option>
		</select>&nbsp;<input style='outline:none; box-shadow: 1px 1px 20px rgba(255,255,255,0.8); background-color:rgba(0, 204, 167, 1); width:130px' type='text' name='searchfield'>
		<input id='searchme' type='submit' name='filterme' value='Search' onclick='sendFiltered()'>
		<span id='refreshp' url='orders.php' name='Orders' style='color:rgba(255,255,255,0.8); text-shadow:1px 1px 20px rgba(255,255,255,0.8); cursor:pointer; float:right' onclick='loadthis(this)'>&#8475;efresh</span>
		</th></table></form>";
echo "<div class='section'><span style='color:rgba(255,255,255,0.8); font-size:21px; text-shadow:1px 1px 20px rgba(255,255,255,0.8)'>Upload .CSV <span id='change' style='cursor:pointer; font-size:21px; color:white' onclick='displayUpload()'>&plus;</span></span>
		<form id='csvupload' method='post' enctype='multipart/form-data'>
			<table style='transform:translate(10px,0)' width='20%'><tr>
			<tr><td><input id='file' type='file' name='file'></td></tr>
			<tr><td style='text-align:right'><input style='width:100px' id='uploadcsv' type='submit' onclick='uploadCSV()' value='Upload'></td></tr>
			</table>
		</form></div>";

echo "<form id='orderlist' method='post' autocomplete='off'>";
echo "<table class='stock' cellspacing='0'>
		<tr>
		<th><span id='addStock' onclick='addOrder()'>&plus;</span><span id='orderID' onclick='orderByMe(this.id)'>Order ID</span></th>
		<th><span id='customer' onclick='orderByMe(this.id)'>Customer Name</span></th>
		<th><span id='product' onclick='orderByMe(this.id)'>Product Name</span></th>
		<th><span id='quantity' onclick='orderByMe(this.id)'>Quantity</span></th>
		<th><span id='serialCode' onclick='orderByMe(this.id)'>Serial Code</span></th>
		<th><span id='shippingStatus' onclick='orderByMe(this.id)'>Shipping Status</span></th>
		<th><span id='remarks' onclick='orderByMe(this.id)'>Remarks</span></th>
		<th><span id='stamp' onclick='orderByMe(this.id)'>Stamp</span></th></tr>";
		
for($i = 0; $i < $rows; $i++){
	$result->data_seek($i);
	$row = $result->fetch_array(MYSQL_ASSOC);
	if($row['shippingStatus'] == 0){
		$color = 'color:rgba(209, 0, 0, 1)';
		$tshadow = 'text-shadow:0 0 20px rgba(255,255,255,0.8)';
		$background = 'background-color:red';
		$row['shippingStatus'] = 'IN PROGRESS';
	}else if($row['shippingStatus'] == 1){
		$color = 'color:limegreen';
		$tshadow = 'text-shadow:0 0 20px limegreen';
		$background = 'background-color:limegreen';
		$row['shippingStatus'] = 'DELIVERED';
	}
	echo "<tr id='" . $row['orderID'] . "delete' ondblclick='promptDelete(this.id)'>
			<td><input id='remove" . $row['orderID'] . "' type='checkbox' name='remove[]' value='" . $row['orderID'] . "'><label class='checkbox' for='remove" . $row['orderID'] . "'></label>&nbsp;" . $row['orderID'] . "</td>
			<input type='hidden' name='orderID[]' value='" . $row['orderID'] . "'> 
			<td><input type='text' name='customer[]' style='background-color:rgba(0,0,0,0)' value='" . $row['customer'] . "'></td>
			<td><input type='text' name='product[]' style='background-color:rgba(0,0,0,0)' value='" . $row['product'] . "'></td>
			<td><input type='text' name='quantity[]' style='background-color:rgba(0,0,0,0)' value='" . $row['quantity'] . "'></td>
			<td><input type='text' name='serialCode[]' style='background-color:rgba(0,0,0,0)' value='" . $row['serialCode'] . "'></td>
			<td><input id='check" . $row['orderID'] . "' name='" . $row['orderID'] . "' status='" . $row['shippingStatus'] . "' value='ok' type='checkbox' onchange='statusUpdate(this)' style='background-color:rgba(0,0,0,0)'><label id='lab" . $row['orderID'] . "' style='$background' for='check" . $row['orderID'] . "'></label>&nbsp;<span id='output" . $row['orderID'] . "' style='font-weight:bold; font-size:20px; $color; $tshadow'>" . $row['shippingStatus'] . "</span></td>
			<td><input type='text' name='remarks[]' style='background-color:rgba(0,0,0,0)' value='" . $row['remarks'] . "'></td>
			<td><input type='text' style='background-color:rgba(0,0,0,0)' value='" . $row['stamp'] . "'></td>
		  </tr>";
}

echo "<td></td><td></td><td></td><td></td><td></td><td></td><td></td><td><input onclick='updateOrder()' type='submit' value='&#10004;'></td></form></table>";
}
else{//main display

$result = queryMysql("SELECT * FROM orders ORDER BY orderID, shippingStatus");
$rows = $result->num_rows;

echo "<span id='searchhide' onclick='hidesearch()'>&minus;</span><form id='searching' method='post' enctype='multipart/form-data'>
		<table class='search'>
		<th style='text-align:left; color:rgba(255,255,255,0.8); text-shadow:1px 1px 20px rgba(255,255,255,0.8)'><span style='font-size:21px'>Search By:</span>	
		<select name='field'>
		<option value='orderID'>Order ID</option>
		<option value='customer'>Customer Name</option>
		<option value='product'>Product Name</option>
		<option value='quantity'>Quantity</option>
		<option value='serialCode'>Serial Code</option>
		<option value='shippingStatus'>Shipping Status</option>
		<option value='remarks'>Remarks</option>
		<option value='stamp'>Stamp</option>
		</select>&nbsp;<input style='outline:none; box-shadow: 1px 1px 20px rgba(255,255,255,0.8); background-color:rgba(0, 204, 167, 1); width:130px' type='text' name='searchfield'>
		<input id='searchme' type='submit' name='filterme' value='Search' onclick='sendFiltered()'>
		<span id='refreshp' url='orders.php' name='Orders' style='color:rgba(255,255,255,0.8); text-shadow:1px 1px 20px rgba(255,255,255,0.8); cursor:pointer; float:right' onclick='loadthis(this)'>&#8475;efresh</span>
		</th></table></form>";
echo "<div class='section'><span style='color:rgba(255,255,255,0.8); font-size:21px; text-shadow:1px 1px 20px rgba(255,255,255,0.8)'>Upload .CSV <span id='change' style='cursor:pointer; font-size:21px; color:white' onclick='displayUpload()'>&plus;</span></span>
		<form id='csvupload' method='post' enctype='multipart/form-data'>
			<table style='transform:translate(10px,0)' width='20%'><tr>
			<tr><td><input id='file' type='file' name='file'></td></tr>
			<tr><td style='text-align:right'><input style='width:100px' id='uploadcsv' type='submit' onclick='uploadCSV()' value='Upload'></td></tr>
			</table>
		</form></div>";

echo "<form id='orderlist' method='post' autocomplete='off'>";
echo "<table class='stock' cellspacing='0'>
		<tr>
		<th><span id='addStock' onclick='addOrder()'>&plus;</span><span id='orderID' onclick='orderByMe(this.id)'>Order ID</span></th>
		<th><span id='customer' onclick='orderByMe(this.id)'>Customer Name</span></th>
		<th><span id='product' onclick='orderByMe(this.id)'>Product Name</span></th>
		<th><span id='quantity' onclick='orderByMe(this.id)'>Quantity</span></th>
		<th><span id='serialCode' onclick='orderByMe(this.id)'>Serial Code</span></th>
		<th><span id='shippingStatus' onclick='orderByMe(this.id)'>Shipping Status</span></th>
		<th><span id='remarks' onclick='orderByMe(this.id)'>Remarks</span></th>
		<th><span id='stamp' onclick='orderByMe(this.id)'>Stamp</span></th></tr>";
		
for($i = 0; $i < $rows; $i++){
	$result->data_seek($i);
	$row = $result->fetch_array(MYSQL_ASSOC);
	if($row['shippingStatus'] == 0){
		$color = 'color:rgba(209, 0, 0, 1)';
		$tshadow = 'text-shadow:0 0 20px rgba(255,255,255,0.8)';
		$background = 'background-color:red';
		$row['shippingStatus'] = 'IN PROGRESS';
	}else if($row['shippingStatus'] == 1){
		$color = 'color:limegreen';
		$tshadow = 'text-shadow:0 0 20px limegreen';
		$background = 'background-color:limegreen';
		$row['shippingStatus'] = 'DELIVERED';
	}
	echo "<tr id='" . $row['orderID'] . "delete' ondblclick='promptDelete(this.id)'>
			<td><input id='remove" . $row['orderID'] . "' type='checkbox' name='remove[]' value='" . $row['orderID'] . "'><label class='checkbox' for='remove" . $row['orderID'] . "'></label>&nbsp;" . $row['orderID'] . "</td>
			<input type='hidden' name='orderID[]' value='" . $row['orderID'] . "'> 
			<td><input type='text' name='customer[]' style='background-color:rgba(0,0,0,0)' value='" . $row['customer'] . "'></td>
			<td><input type='text' name='product[]' style='background-color:rgba(0,0,0,0)' value='" . $row['product'] . "'></td>
			<td><input type='text' name='quantity[]' style='background-color:rgba(0,0,0,0)' value='" . $row['quantity'] . "'></td>
			<td><input type='text' name='serialCode[]' style='background-color:rgba(0,0,0,0)' value='" . $row['serialCode'] . "'></td>
			<td><input id='check" . $row['orderID'] . "' name='" . $row['orderID'] . "' status='" . $row['shippingStatus'] . "' value='ok' type='checkbox' onchange='statusUpdate(this)' style='background-color:rgba(0,0,0,0)'><label id='lab" . $row['orderID'] . "' style='$background' for='check" . $row['orderID'] . "'></label>&nbsp;<span id='output" . $row['orderID'] . "' style='font-weight:bold; font-size:20px; $color; $tshadow'>" . $row['shippingStatus'] . "</span></td>
			<td><input type='text' name='remarks[]' style='background-color:rgba(0,0,0,0)' value='" . $row['remarks'] . "'></td>
			<td><input type='text' style='background-color:rgba(0,0,0,0)' value='" . $row['stamp'] . "'></td>
		  </tr>";
}

echo "<td></td><td></td><td></td><td></td><td></td><td></td><td></td><td><input onclick='updateOrder()' type='submit' value='&#10004;'></td></form></table>";
}
?>