<?php
require 'functions.php';
session_name('VirtualStockWarehouse');
session_start();
if(isset($_POST['del'])){
	$id = $_POST['del'];
	foreach($id as $i){
	$result = queryMysql("SELECT * FROM stock WHERE stockID='$i'");
	$row = $result->fetch_array(MYSQL_ASSOC);
	$sc = $row['serialCode'];
	$cookieName = $sc;
	setcookie($cookieName, '', time() - 3600);
	setcookie($cookieName, '', time() - 3600, '/');
	$result = queryMysql("DELETE FROM stock WHERE stockID='$i'");
	}
}

if(isset($_GET['newitem'])){
	$newitem = mysql_real_escape_string($_GET['newitem']);	
	$serialCode = mysql_real_escape_string($_GET['newserialcode']);
	$newquantity = mysql_real_escape_string($_GET['newquantity']);
	$price = mysql_real_escape_string($_GET['newprice']);
	$remarks = mysql_real_escape_string($_GET['newremarks']);
	$result = queryMysql("SELECT * FROM stock");
	$rows = $result->num_rows;
	$stockID = $rows + 1;
	$stamp = date('d.m.Y|h:ia');
	$cookieName = $serialCode;
	$cookieValue = $newquantity;
	setcookie($cookieName, $cookieValue, time() + (86400 * 365), '/');
		
	$result = queryMysql("SELECT * FROM stock WHERE name='$newitem'");
	if($result->num_rows == 0){
	$result = queryMysql("INSERT INTO stock VALUES('$stockID','$serialCode','$newitem','$newquantity','$price','$remarks','$stamp')");
	}
}

if(isset($_POST['stockID'])){
	$num = count($_POST);
	if($num <= 1){
		$name = $_POST['name'];
		$stockID = $_POST['stockID'];
		$serialCode = $_POST['serialCode'];
		$quantity = $_POST['quantity'];
		$price = $_POST['price'];
		$cookieName = $serialCode;
		$cookieValue = $quantity;
		$remarks = $_POST['remarks'];
		if(isset($_POST['remove'])){
			$remove = $_POST['remove'];
			$result = queryMysql("DELETE FROM stock WHERE stockID='$remove'");
			setcookie($cookieName, '', time() - 3600);
			setcookie($cookieName, '', time() - 3600, '/');
		}else {
			$result = queryMysql("SELECT * FROM stock");
			$rows = $result->num_rows;
			for($j = 0; $j < $rows; $j++){
				$result->data_seek($j);
				$row = $result->fetch_array(MYSQL_ASSOC);
				$stock[$j] = array(
				'stockID' => $row['stockID'],
				'serialCode' => $row['serialCode'],
				'name' => $row['name'],
				'quantity' => $row['quantity'],
				'price' => $row['price']
				);
			}
			foreach($stock as $s){
				if($stockID == $s['stockID']){
					if($name !== $s['name']){
						$result = queryMysql("UPDATE stock SET name='$name' WHERE stockID='$stockID'");
					}
					if($quantity !== $s['quantity']){
						setcookie($cookieName, $cookieValue, time() + (86400 * 365), '/');
						$result = queryMysql("UPDATE stock SET quantity='$quantity' WHERE stockID='$stockID'");
					}
					if($price !== $s['price']){ 
						$result = queryMysql("UPDATE stock SET price='$price' WHERE stockID='$stockID'");	
					}
					if($remarks !== $s['remarks']){
						$result = queryMysql("UPDATE stock SET remarks='$remarks' WHERE stockID='$stockID'");
					}
				}
			}
		}
	}else{
	$i = 0;
	while(!empty($_POST)){
		$stockID = $_POST['stockID'][$i];
		$name = $_POST['name'][$i];
		$serialCode = $_POST['serialCode'][$i];
		$quantity = $_POST['quantity'][$i];
		$price = $_POST['price'][$i];
		$remarks = $_POST['remarks'][$i];
		$cookieName = $serialCode;
		$cookieValue = $quantity;
		if(isset($_POST['remove'][$i])){
			$remove = $_POST['remove'][$i];
			setcookie($cookieName, '', time() - 3600);
			setcookie($cookieName, '', time() - 3600, '/');
			$result = queryMysql("DELETE FROM stock WHERE stockID='$remove'");
			$i++;
		}else {
			$result = queryMysql("SELECT * FROM stock");
			$rows = $result->num_rows;
			for($j = 0; $j < $rows; $j++){
				$result->data_seek($j);
				$row = $result->fetch_array(MYSQL_ASSOC);
				$stock[$j] = array(
				'stockID' => $row['stockID'],
				'serialCode' => $row['serialCode'],
				'name' => $row['name'],
				'quantity' => $row['quantity'],
				'price' => $row['price']
				);
			}
			foreach($stock as $s){
				if($stockID == $s['stockID']){
					if($name !== $s['name']){
						$result = queryMysql("UPDATE stock SET name='$name' WHERE stockID='$stockID'");
					}
					if($serialCode !== $s['serialCode']){
						$result = queryMysql("UPDATE stock SET serialCode='$serialCode' WHERE stockID='$stockID'");
					}
					if($quantity !== $s['quantity']){
						setcookie($cookieName, $cookieValue, time() + (86400 * 365), '/');
						$result = queryMysql("UPDATE stock SET quantity='$quantity' WHERE stockID='$stockID'");
					}
					if($price !== $s['price']){ 
						$result = queryMysql("UPDATE stock SET price='$price' WHERE stockID='$stockID'");	
					}
					if($remarks !== $s['remarks']){
						$result = queryMysql("UPDATE stock SET remarks='$remarks' WHERE stockID='$stockID'");
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
		$product = $data[0];
		$quantity = $data[1];
		$price = $data[2];
		$serialCode = $data[3];
		$remarks = $data[4];
		$result = queryMysql("SELECT * FROM stock");
		$num = $result->num_rows;
		$stockID = $num + 1;
		$stamp = date('d.m.Y|h:ia');
		$cookieName = $serialCode;
		$cookieValue = $quantity;
		setcookie($cookieName, $cookieValue, time() + (86400 * 365), '/');
	
	$result = queryMysql("SELECT * FROM stock WHERE serialCode='$serialCode'");
		if($result->num_rows == 0){
	$result = queryMysql("INSERT INTO stock VALUES('$stockID','$serialCode','$product','$quantity','$price','$remarks','$stamp')");
		}
	}
}

if(isset($_POST['asc'])){//filter display by choice ascending
	$asc = $_POST['asc'];
	
	$result = queryMysql("SELECT * FROM stock");
			$rows = $result->num_rows;
			for($j = 0; $j < $rows; $j++){
				$result->data_seek($j);
				$row = $result->fetch_array(MYSQL_ASSOC);
				$SC = $row['serialCode'];
				$stock[$j] = array(
				'stockID' => $row['stockID'],
				'serialCode' => $row['serialCode'],
				'name' => $row['name'],
				'quantity' => $row['quantity'],
				'originalquantity' => $_COOKIE[$SC],
				'price' => $row['price']
				);
			}
			
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
			'statusUpdate' => $row['shippingStatus']
			);
		}
		
		$got = false;
		$same = false;
		if(isset($stock)){
			foreach($stock as $s){
				$stockid = $s['stockID'];
				$serial = $s['serialCode'];
				if(isset($order)){
				foreach($order as $o){
					if($o['serialCode'] == $s['serialCode']){
						if($o['statusUpdate'] == 1){
							$got = true;
						$s['originalquantity'] = $s['originalquantity'] - $o['quantity'];
						}else $got = true;
					}
				}//order as o
				if($got == true){
					$result = queryMysql("UPDATE stock SET quantity='" . $s['originalquantity'] . "' WHERE stockID='$stockid'");
				}
				}else $result = queryMysql("UPDATE stock SET quantity='" . $s['originalquantity'] . "' WHERE stockID='$stockid'");
		}
		}
	$result = queryMysql("SELECT * FROM stock ORDER BY $asc ASC");
	$rows = $result->num_rows;
	
	echo "<span id='searchhide' onclick='hidesearch()'>&minus;</span><form id='searching2' method='post' enctype='multipart/form-data'>
			<table class='search'>
			<th style='text-align:left; color:rgba(255,255,255,0.8); text-shadow:1px 1px 20px rgba(255,255,255,0.8)'><span style='font-size:21px'>Search By:</span>
			<select name='field'>
			<option value='stockID'>Stock ID</option>
			<option value='name'>Item Name</option>
			<option value='quantity'>Quantity</option>
			<option value='price'>Price</option>
			<option value='serialCode'>Serial Code</option>
			<option value='remarks'>Remarks</option>
			<option value='stamp'>Stamp</option>
			</select>&nbsp;<input style='outline:none; box-shadow: 1px 1px 20px rgba(255,255,255,0.8); background-color:rgba(0, 204, 167, 1); width:130px' type='text' name='searchfield'>
			<input id='searchme' type='submit' name='filterme' value='Search' onclick='sendFiltered2()'>
			<span id='refreshp' url='items.php' name='Stock Items' style='color:rgba(255,255,255,0.8); text-shadow:1px 1px 20px rgba(255,255,255,0.8); cursor:pointer; float:right' onclick='loadthis(this)'>&#8475;efresh</span>
			</th></table></form>";
	echo "<div class='section'><span style='color:rgba(255,255,255,0.8); font-size:21px; text-shadow:1px 1px 20px rgba(255,255,255,0.8)'>Upload .CSV <span id='changes' onclick='displayUpload2()'>&plus;</span></span>
			<form id='csvupload2' method='post' enctype='multipart/form-data'>
				<table style='transform:translate(10px,0)' width='20%'><tr>
				<tr><td><input id='file' type='file' name='file'></td></tr>
				<tr><td style='text-align:right'><input style='width:100px' id='uploadcsv' type='submit' onclick='uploadCSV2()' value='Upload'></td></tr>
				</table>
			</form></div>";

	echo "<form id='updatestock' method='post' autocomplete='off'>";
	echo "<table class='stock' cellspacing='0'>
			<tr>
			<th><span id='addStock' onclick='addStock()'>&plus;</span><span id='stockID' onclick='stockByMe(this.id)'>Stock ID</span></th>
			<th><span id='name' onclick='stockByMe(this.id)'>Item Name</span></th>
			<th><span id='quantity' onclick='stockByMe(this.id)'>Quantity</span></th>
			<th><span id='price' onclick='stockByMe(this.id)'>Price</span></th>
			<th><span id='serialCode' onclick='stockByMe(this.id)'>Serial Code</span></th>
			<th><span id='remarks' onclick='stockByMe(this.id)'>Remarks</span></th>
			<th><span id='stamp' onclick='stockByMe(this.id)'>Stamp</span></th></tr>";

	for($i = 0; $i < $rows; $i++){
	$result->data_seek($i);
	$row = $result->fetch_array(MYSQL_ASSOC);
	echo "<tr id='" . $row['stockID'] . "delete' ondblclick='promptDelete2(this.id)'>
			<td><input id='remove' type='checkbox' name='remove[]' value='" . $row['stockID'] . "'>" . $row['stockID'] . "</td>
			<input type='hidden' name='stockID[]' value='" . $row['stockID'] . "'> 
			<td><input type='text' name='name[]' style='background-color:rgba(0,0,0,0)' value='" . $row['name'] . "'></td>
			<td><input type='text' name='quantity[]' style='background-color:rgba(0,0,0,0)' value='" . $row['quantity'] . "'></td>
			<td>$<input type='text' name='price[]' style='background-color:rgba(0,0,0,0)' value='" . $row['price'] . "'></td>
			<td><input type='text' name='serialCode[]' style='background-color:rgba(0,0,0,0)' value='" . $row['serialCode'] . "'></td>
			<td><input type='text' name='remarks[]' style='background-color:rgba(0,0,0,0)' value='" . $row['remarks'] . "'></td>
			<td><input type='text' style='background-color:rgba(0,0,0,0)' value='" . $row['stamp'] . "'></td>
		  </tr>";
}

echo "<td></td><td></td><td></td><td></td><td></td><td></td><td><input onclick='updateStock()' type='submit' value='&#10004;'></form></table>";
}
else if(isset($_POST['desc'])){//filter display by choice descending
	$desc = $_POST['desc'];
	$result = queryMysql("SELECT * FROM stock");
			$rows = $result->num_rows;
			for($j = 0; $j < $rows; $j++){
				$result->data_seek($j);
				$row = $result->fetch_array(MYSQL_ASSOC);
				$SC = $row['serialCode'];
				$stock[$j] = array(
				'stockID' => $row['stockID'],
				'serialCode' => $row['serialCode'],
				'name' => $row['name'],
				'quantity' => $row['quantity'],
				'originalquantity' => $_COOKIE[$SC],
				'price' => $row['price']
				);
			}
			
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
			'statusUpdate' => $row['shippingStatus']
			);
		}
		
		$got = false;
		$same = false;
		if(isset($stock)){
			foreach($stock as $s){
				$stockid = $s['stockID'];
				$serial = $s['serialCode'];
				if(isset($order)){
				foreach($order as $o){
					if($o['serialCode'] == $s['serialCode']){
						if($o['statusUpdate'] == 1){
							$got = true;
						$s['originalquantity'] = $s['originalquantity'] - $o['quantity'];
						}else $got = true;
					}
				}//order as o
				if($got == true){
					$result = queryMysql("UPDATE stock SET quantity='" . $s['originalquantity'] . "' WHERE stockID='$stockid'");
				}
				}else $result = queryMysql("UPDATE stock SET quantity='" . $s['originalquantity'] . "' WHERE stockID='$stockid'");
		}
		}
	$result = queryMysql("SELECT * FROM stock ORDER BY $desc DESC");
	$rows = $result->num_rows;
	echo "<span id='searchhide' onclick='hidesearch()'>&minus;</span><form id='searching2' method='post' enctype='multipart/form-data'>
			<table class='search'>
			<th style='text-align:left; color:rgba(255,255,255,0.8); text-shadow:1px 1px 20px rgba(255,255,255,0.8)'><span style='font-size:21px'>Search By:</span>
			<select name='field'>
			<option value='stockID'>Stock ID</option>
			<option value='name'>Item Name</option>
			<option value='quantity'>Quantity</option>
			<option value='price'>Price</option>
			<option value='serialCode'>Serial Code</option>
			<option value='remarks'>Remarks</option>
			<option value='stamp'>Stamp</option>
			</select>&nbsp;<input style='outline:none; box-shadow: 1px 1px 20px rgba(255,255,255,0.8); background-color:rgba(0, 204, 167, 1); width:130px' type='text' name='searchfield'>
			<input id='searchme' type='submit' name='filterme' value='Search' onclick='sendFiltered2()'>
			<span id='refreshp' url='items.php' name='Stock Items' style='color:rgba(255,255,255,0.8); text-shadow:1px 1px 20px rgba(255,255,255,0.8); cursor:pointer; float:right' onclick='loadthis(this)'>&#8475;efresh</span>
			</th></table></form>";
	echo "<div class='section'><span style='color:rgba(255,255,255,0.8); font-size:21px; text-shadow:1px 1px 20px rgba(255,255,255,0.8)'>Upload .CSV <span id='changes' onclick='displayUpload2()'>&plus;</span></span>
			<form id='csvupload2' method='post' enctype='multipart/form-data'>
				<table style='transform:translate(10px,0)' width='20%'><tr>
				<tr><td><input id='file' type='file' name='file'></td></tr>
				<tr><td style='text-align:right'><input style='width:100px' id='uploadcsv' type='submit' onclick='uploadCSV2()' value='Upload'></td></tr>
				</table>
			</form></div>";

	echo "<form id='updatestock' method='post' autocomplete='off'>";
	echo "<table class='stock' cellspacing='0'>
			<tr>
			<th><span id='addStock' onclick='addStock()'>&plus;</span><span id='stockID' onclick='stockByMe(this.id)'>Stock ID</span></th>
			<th><span id='name' onclick='stockByMe(this.id)'>Item Name</span></th>
			<th><span id='quantity' onclick='stockByMe(this.id)'>Quantity</span></th>
			<th><span id='price' onclick='stockByMe(this.id)'>Price</span></th>
			<th><span id='serialCode' onclick='stockByMe(this.id)'>Serial Code</span></th>
			<th><span id='remarks' onclick='stockByMe(this.id)'>Remarks</span></th>
			<th><span id='stamp' onclick='stockByMe(this.id)'>Stamp</span></th></tr>";

	for($i = 0; $i < $rows; $i++){
	$result->data_seek($i);
	$row = $result->fetch_array(MYSQL_ASSOC);
	echo "<tr id='" . $row['stockID'] . "delete' ondblclick='promptDelete2(this.id)'>
			<td><input id='remove' type='checkbox' name='remove[]' value='" . $row['stockID'] . "'>" . $row['stockID'] . "</td>
			<input type='hidden' name='stockID[]' value='" . $row['stockID'] . "'> 
			<td><input type='text' name='name[]' style='background-color:rgba(0,0,0,0)' value='" . $row['name'] . "'></td>
			<td><input type='text' name='quantity[]' style='background-color:rgba(0,0,0,0)' value='" . $row['quantity'] . "'></td>
			<td>$<input type='text' name='price[]' style='background-color:rgba(0,0,0,0)' value='" . $row['price'] . "'></td>
			<td><input type='text' name='serialCode[]' style='background-color:rgba(0,0,0,0)' value='" . $row['serialCode'] . "'></td>
			<td><input type='text' name='remarks[]' style='background-color:rgba(0,0,0,0)' value='" . $row['remarks'] . "'></td>
			<td><input type='text' style='background-color:rgba(0,0,0,0)' value='" . $row['stamp'] . "'></td>
		  </tr>";
}

echo "<td></td><td></td><td></td><td></td><td></td><td></td><td><input onclick='updateStock()' type='submit' value='&#10004;'></form></table>";
}
else if(isset($_POST['field'])){//filter by user search input
	$fieldSelection = $_POST['field'];	
	$field = $_POST['searchfield'];
	
	$result = queryMysql("SELECT * FROM stock");
			$rows = $result->num_rows;
			for($j = 0; $j < $rows; $j++){
				$result->data_seek($j);
				$row = $result->fetch_array(MYSQL_ASSOC);
				$SC = $row['serialCode'];
				$stock[$j] = array(
				'stockID' => $row['stockID'],
				'serialCode' => $row['serialCode'],
				'name' => $row['name'],
				'quantity' => $row['quantity'],
				'originalquantity' => $_COOKIE[$SC],
				'price' => $row['price']
				);
			}
			
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
			'statusUpdate' => $row['shippingStatus']
			);
		}
		
		$got = false;
		$same = false;
		if(isset($stock)){
			foreach($stock as $s){
				$stockid = $s['stockID'];
				$serial = $s['serialCode'];
				if(isset($order)){
				foreach($order as $o){
					if($o['serialCode'] == $s['serialCode']){
						if($o['statusUpdate'] == 1){
							$got = true;
						$s['originalquantity'] = $s['originalquantity'] - $o['quantity'];
						}else $got = true;
					}
				}//order as o
				if($got == true){
					$result = queryMysql("UPDATE stock SET quantity='" . $s['originalquantity'] . "' WHERE stockID='$stockid'");
				}
				}else $result = queryMysql("UPDATE stock SET quantity='" . $s['originalquantity'] . "' WHERE stockID='$stockid'");
		}
		}
		
	$result = queryMysql("SELECT * FROM stock WHERE $fieldSelection='$field'");
	$rows = $result->num_rows;
	
	echo "<span id='searchhide' onclick='hidesearch()'>&minus;</span><form id='searching2' method='post' enctype='multipart/form-data'>
			<table class='search'>
			<th style='text-align:left; color:rgba(255,255,255,0.8); text-shadow:1px 1px 20px rgba(255,255,255,0.8)'><span style='font-size:21px'>Search By:</span>
			<select name='field'>
			<option value='stockID'>Stock ID</option>
			<option value='name'>Item Name</option>
			<option value='quantity'>Quantity</option>
			<option value='price'>Price</option>
			<option value='serialCode'>Serial Code</option>
			<option value='remarks'>Remarks</option>
			<option value='stamp'>Stamp</option>
			</select>&nbsp;<input style='outline:none; box-shadow: 1px 1px 20px rgba(255,255,255,0.8); background-color:rgba(0, 204, 167, 1); width:130px' type='text' name='searchfield'>
			<input id='searchme' type='submit' name='filterme' value='Search' onclick='sendFiltered2()'>
			<span id='refreshp' url='items.php' name='Stock Items' style='color:rgba(255,255,255,0.8); text-shadow:1px 1px 20px rgba(255,255,255,0.8); cursor:pointer; float:right' onclick='loadthis(this)'>&#8475;efresh</span>
			</th></table></form>";
	echo "<div class='section'><span style='color:rgba(255,255,255,0.8); font-size:21px; text-shadow:1px 1px 20px rgba(255,255,255,0.8)'>Upload .CSV <span id='changes' onclick='displayUpload2()'>&plus;</span></span>
			<form id='csvupload2' method='post' enctype='multipart/form-data'>
				<table style='transform:translate(10px,0)' width='20%'><tr>
				<tr><td><input id='file' type='file' name='file'></td></tr>
				<tr><td style='text-align:right'><input style='width:100px' id='uploadcsv' type='submit' onclick='uploadCSV2()' value='Upload'></td></tr>
				</table>
			</form></div>";

	echo "<form id='updatestock' method='post' autocomplete='off'>";
	echo "<table class='stock' cellspacing='0'>
			<tr>
			<th><span id='addStock' onclick='addStock()'>&plus;</span><span id='stockID' onclick='stockByMe(this.id)'>Stock ID</span></th>
			<th><span id='name' onclick='stockByMe(this.id)'>Item Name</span></th>
			<th><span id='quantity' onclick='stockByMe(this.id)'>Quantity</span></th>
			<th><span id='price' onclick='stockByMe(this.id)'>Price</span></th>
			<th><span id='serialCode' onclick='stockByMe(this.id)'>Serial Code</span></th>
			<th><span id='remarks' onclick='stockByMe(this.id)'>Remarks</span></th>
			<th><span id='stamp' onclick='stockByMe(this.id)'>Stamp</span></th></tr>";

	for($i = 0; $i < $rows; $i++){
	$result->data_seek($i);
	$row = $result->fetch_array(MYSQL_ASSOC);
	echo "<tr id='" . $row['stockID'] . "delete' ondblclick='promptDelete2(this.id)'>
			<td><input id='remove' type='checkbox' name='remove[]' value='" . $row['stockID'] . "'>" . $row['stockID'] . "</td>
			<input type='hidden' name='stockID[]' value='" . $row['stockID'] . "'> 
			<td><input type='text' name='name[]' style='background-color:rgba(0,0,0,0)' value='" . $row['name'] . "'></td>
			<td><input type='text' name='quantity[]' style='background-color:rgba(0,0,0,0)' value='" . $row['quantity'] . "'></td>
			<td>$<input type='text' name='price[]' style='background-color:rgba(0,0,0,0)' value='" . $row['price'] . "'></td>
			<td><input type='text' name='serialCode[]' style='background-color:rgba(0,0,0,0)' value='" . $row['serialCode'] . "'></td>
			<td><input type='text' name='remarks[]' style='background-color:rgba(0,0,0,0)' value='" . $row['remarks'] . "'></td>
			<td><input type='text' style='background-color:rgba(0,0,0,0)' value='" . $row['stamp'] . "'></td>
		  </tr>";
}

echo "<td></td><td></td><td></td><td></td><td></td><td></td><td><input onclick='updateStock()' type='submit' value='&#10004;'></form></table>";
}
else{//main display

$result = queryMysql("SELECT * FROM stock");
			$rows = $result->num_rows;
			for($j = 0; $j < $rows; $j++){
				$result->data_seek($j);
				$row = $result->fetch_array(MYSQL_ASSOC);
				$SC = $row['serialCode'];
				$stock[$j] = array(
				'stockID' => $row['stockID'],
				'serialCode' => $row['serialCode'],
				'name' => $row['name'],
				'quantity' => $row['quantity'],
				'originalquantity' => $_COOKIE[$SC],
				'price' => $row['price']
				);
			}
			
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
			'statusUpdate' => $row['shippingStatus']
			);
		}
		
		$got = false;
		$same = false;
		if(isset($stock)){
			foreach($stock as $s){
				$stockid = $s['stockID'];
				$serial = $s['serialCode'];
				if(isset($order)){
				foreach($order as $o){
					if($o['serialCode'] == $s['serialCode']){
						if($o['statusUpdate'] == 1){
							$got = true;
						$s['originalquantity'] = $s['originalquantity'] - $o['quantity'];
						}else $got = true;
					}
				}//order as o
				if($got == true){
					$result = queryMysql("UPDATE stock SET quantity='" . $s['originalquantity'] . "' WHERE stockID='$stockid'");
				}
				}else $result = queryMysql("UPDATE stock SET quantity='" . $s['originalquantity'] . "' WHERE stockID='$stockid'");
		}
		}
		
	$result = queryMysql("SELECT * FROM stock ORDER BY stockID");
	$rows = $result->num_rows;
		
	echo "<span id='searchhide' onclick='hidesearch()'>&minus;</span><form id='searching2' method='post' enctype='multipart/form-data'>
			<table class='search'>
			<th style='text-align:left; color:rgba(255,255,255,0.8); text-shadow:1px 1px 20px rgba(255,255,255,0.8)'><span style='font-size:21px'>Search By:</span>
			<select name='field'>
			<option value='stockID'>Stock ID</option>
			<option value='name'>Item Name</option>
			<option value='quantity'>Quantity</option>
			<option value='price'>Price</option>
			<option value='serialCode'>Serial Code</option>
			<option value='remarks'>Remarks</option>
			<option value='stamp'>Stamp</option>
			</select>&nbsp;<input style='outline:none; box-shadow: 1px 1px 20px rgba(255,255,255,0.8); background-color:rgba(0, 204, 167, 1); width:130px' type='text' name='searchfield'>
			<input id='searchme' type='submit' name='filterme' value='Search' onclick='sendFiltered2()'>
			<span id='refreshp' url='items.php' name='Stock Items' style='color:rgba(255,255,255,0.8); text-shadow:1px 1px 20px rgba(255,255,255,0.8); cursor:pointer; float:right' onclick='loadthis(this)'>&#8475;efresh</span>
			</th></table></form>";
	echo "<div class='section'><span style='color:rgba(255,255,255,0.8); font-size:21px; text-shadow:1px 1px 20px rgba(255,255,255,0.8)'>Upload .CSV <span id='changes' onclick='displayUpload2()'>&plus;</span></span>
			<form id='csvupload2' method='post' enctype='multipart/form-data'>
				<table style='transform:translate(10px,0)' width='20%'><tr>
				<tr><td><input id='file' type='file' name='file'></td></tr>
				<tr><td style='text-align:right'><input style='width:100px' id='uploadcsv' type='submit' onclick='uploadCSV2()' value='Upload'></td></tr>
				</table>
			</form></div>";

	echo "<form id='updatestock' method='post' autocomplete='off'>";
	echo "<table class='stock' cellspacing='0'>
			<tr>
			<th><span id='addStock' onclick='addStock()'>&plus;</span><span id='stockID' onclick='stockByMe(this.id)'>Stock ID</span></th>
			<th><span id='name' onclick='stockByMe(this.id)'>Item Name</span></th>
			<th><span id='quantity' onclick='stockByMe(this.id)'>Quantity</span></th>
			<th><span id='price' onclick='stockByMe(this.id)'>Price</span></th>
			<th><span id='serialCode' onclick='stockByMe(this.id)'>Serial Code</span></th>
			<th><span id='remarks' onclick='stockByMe(this.id)'>Remarks</span></th>
			<th><span id='stamp' onclick='stockByMe(this.id)'>Stamp</span></th></tr>";

	for($i = 0; $i < $rows; $i++){
	$result->data_seek($i);
	$row = $result->fetch_array(MYSQL_ASSOC);
	echo "<tr id='" . $row['stockID'] . "delete' ondblclick='promptDelete2(this.id)'>
			<td><input id='remove" . $row['stockID'] . "' type='checkbox' name='remove[]' value='" . $row['stockID'] . "'><label class='checkbox' for='remove" . $row['stockID'] . "'></label>&nbsp;" . $row['stockID'] . "</td>
			<input type='hidden' name='stockID[]' value='" . $row['stockID'] . "'> 
			<td><input type='text' name='name[]' style='background-color:rgba(0,0,0,0)' value='" . $row['name'] . "'></td>
			<td><input type='text' name='quantity[]' style='background-color:rgba(0,0,0,0)' value='" . $row['quantity'] . "'></td>
			<td>$<input type='text' name='price[]' style='background-color:rgba(0,0,0,0)' value='" . $row['price'] . "'></td>
			<td><input type='text' name='serialCode[]' style='background-color:rgba(0,0,0,0)' value='" . $row['serialCode'] . "'></td>
			<td><input type='text' name='remarks[]' style='background-color:rgba(0,0,0,0)' value='" . $row['remarks'] . "'></td>
			<td><input type='text' style='background-color:rgba(0,0,0,0)' value='" . $row['stamp'] . "'></td>
		  </tr>";
}

echo "<td></td><td></td><td></td><td></td><td></td><td></td><td><input onclick='updateStock()' type='submit' value='&#10004;'></form></table>";
}
?>