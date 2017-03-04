<?php
include 'functions.php';
session_name('Store');
session_start();
//STOCK COUNT BASIC ALGO
$result = queryMysql("SELECT * FROM stock");
			$rows = $result->num_rows;
			for($j = 0; $j < $rows; $j++){
				$result->data_seek($j);
				$row = $result->fetch_array(MYSQLI_ASSOC);
				$SC = $row['productCode'];
				$stock[$j] = array(
				'stockID' => $row['stockID'],
				'productCode' => $row['productCode'],
				'quantity' => $row['quantity'],
				'originalquantity' => $_COOKIE[$SC],
				'price' => $row['price']
				);
			} //get all stock
			
	$result = queryMysql("SELECT * FROM orders");
		$rows = $result->num_rows;
		for($j = 0; $j < $rows; $j++){
			$result->data_seek($j);
			$row = $result->fetch_array(MYSQLI_ASSOC);
			$order[$j] = array(
			'reportID' => $row['reportID'],
			'name' => $row['name'],
			'quantity' => $row['quantity'],
			'productCode' => $row['productCode'],
			'statusUpdate' => $row['shippingStatus']
			);
		}
		
		$got = false;
		$same = false;
		if(isset($stock)){
			foreach($stock as $s){
				$stockid = $s['stockID'];
				$serial = $s['productCode'];
				if(isset($order)){
				foreach($order as $o){
					if($o['productCode'] == $s['productCode']){
						if($o['statusUpdate'] >= 0){
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
		} //get all orders


if(isset($_POST['searchD'])){//SEARCH STOCK ITEMS
	$variable = false;
	$date = sanitizeString($_POST['date']);
	$search = sanitizeString($_POST['searchD']);
	$field = sanitizeString($_POST['field']);
	if($field == 'stamp'){
		$search = $date;
	}else if($field == 'All'){
		$variable = true;
	}
	$_SESSION['fieldstock'] = $field;
	$_SESSION['searchstock'] = $search;
	if($variable == true){
		$result = queryMysql("SELECT * FROM stock ORDER BY stockID");
	}else{
		$result = queryMysql("SELECT * FROM stock WHERE $field='$search' ORDER BY stockID");
	}
	if($result->num_rows !== 0){
		echo "<div style='background-color:#f5f5f5; padding-bottom:10px; margin-bottom:10px; min-width:100%; overflow:auto; border-radius:10px; transform:translate(0,25px)' class='col-xs-12'>
		<span style='transform:translate(5px,10px)' class='fa fa-times-circle fa-2x close' onclick='emptyConsole()'></span>
		<form id='stockDatabase' method='post'>
		<table class='table table-striped'>
		<thead>
		<tr style='border-bottom:3px solid lightgrey'>
			<th class='text-center'><input type='checkbox' table='stock' id='selectAll' onchange='selectall(this)'></th>
			<th class='text-center'>Stock ID<span id='stockID' style='cursor:pointer' onclick='stockByDesc(this.id)'>&utrif;</span><span id='stockID' style='cursor:pointer' onclick='stockByAsc(this.id)'>&dtrif;</span></th>
			<th class='text-center'>Product Code<span id='productCode' style='cursor:pointer' onclick='stockByDesc(this.id)'>&utrif;</span><span id='productCode' style='cursor:pointer' onclick='stockByAsc(this.id)'>&dtrif;</span></th>
			<th class='text-center'>Quantity<span id='quantity'  style='cursor:pointer' onclick='stockByDesc(this.id)'>&utrif;</span><span id='quantity' style='cursor:pointer' onclick='stockByAsc(this.id)'>&dtrif;</span></th>
			<th class='text-center'>Price<span id='price' style='cursor:pointer' onclick='stockByDesc(this.id)'>&utrif;</span><span id='price' style='cursor:pointer' onclick='stockByAsc(this.id)'>&dtrif;</span></th>
			<th class='text-center'>Date<span id='stamp' style='cursor:pointer' onclick='stockByDesc(this.id)'>&utrif;</span><span id='stamp' style='cursor:pointer' onclick='stockByAsc(this.id)'>&dtrif;</span></th>
		</tr>
		</thead>
		<tbody>";

		$num = $result->num_rows;
		for($i = 0; $i < $num; $i++){
		$result->data_seek($i);
		$row = $result->fetch_array(MYSQLI_ASSOC);
		echo "<tr id='" . $row['stockID'] . "' ondblclick='deleteStockItemRow(this.id)'>
				<td id='" . $row['stockID'] . "'><input type='checkbox' name='selectMe[]' onchange='selectMe(this)' table='stock' value='" . $row['stockID'] . "' id='" . $row['stockID'] . "' class='getcheckbox'></td>
				<td>" . $row['stockID'] . "</td>
				<input type='hidden' name='stockID[]' value='" . $row['stockID'] . "'> 
				<td><input type='text' name='editproductcode[]' id='productCode' value='" . $row['productCode'] . "'></td>
				<td><input type='text' name='editquantity[]' id='quantity' value='" . $row['quantity'] . "'></td>
				<td><span>$</span><input type='text' name='editprice[]' id='price' value='" . $row['price'] . "'></td>
				<td>" . $row['stamp'] . '</td></tr>';
		}
		echo "<tr><td></td><td></td><td></td><td></td><td></td><td><input type='submit' value='Update' class='btn btn-primary' onclick='updateStock()'></td></tbody></table></form><button style='font-size:25px; color:black' class='text-center btn btn-warning' onclick='directToPrint()'>Select Template</button></div>";
	}
}else if(isset($_POST['asc'])){
	$asc = $_POST['asc'];
	if(isset($_SESSION['fieldstock'])){
		$field = $_SESSION['fieldstock'];
		$search = $_SESSION['searchstock'];
		if($field == 'All'){
			$result = queryMysql("SELECT * FROM stock ORDER BY $asc ASC");	
		}else{
			$result = queryMysql("SELECT * FROM stock WHERE $field='$search' ORDER BY $asc ASC");
		}
	}else{
		$result = queryMysql("SELECT * FROM stock ORDER BY $asc ASC");
	}
	if($result->num_rows !== 0){
	echo "<div style='background-color:#f5f5f5; padding-bottom:10px; margin-bottom:10px; min-width:100%; overflow:auto; border-radius:10px; transform:translate(0,25px)' class='col-xs-12'>
		<span style='transform:translate(5px,10px)' class='fa fa-times-circle fa-2x close' onclick='emptyConsole()'></span>
		<form id='stockDatabase' method='post'>
		<table class='table table-striped'>
		<thead>
		<tr style='border-bottom:3px solid lightgrey'>
			<th class='text-center'><input type='checkbox' table='stock' id='selectAll' onchange='selectall(this)'></th>
			<th class='text-center'>Stock ID<span id='stockID' style='cursor:pointer' onclick='stockByDesc(this.id)'>&utrif;</span><span id='stockID' style='cursor:pointer' onclick='stockByAsc(this.id)'>&dtrif;</span></th>
			<th class='text-center'>Product Code<span id='productCode' style='cursor:pointer' onclick='stockByDesc(this.id)'>&utrif;</span><span id='productCode' style='cursor:pointer' onclick='stockByAsc(this.id)'>&dtrif;</span></th>
			<th class='text-center'>Quantity<span id='quantity'  style='cursor:pointer' onclick='stockByDesc(this.id)'>&utrif;</span><span id='quantity' style='cursor:pointer' onclick='stockByAsc(this.id)'>&dtrif;</span></th>
			<th class='text-center'>Price<span id='price' style='cursor:pointer' onclick='stockByDesc(this.id)'>&utrif;</span><span id='price' style='cursor:pointer' onclick='stockByAsc(this.id)'>&dtrif;</span></th>
			<th class='text-center'>Date<span id='stamp' style='cursor:pointer' onclick='stockByDesc(this.id)'>&utrif;</span><span id='stamp' style='cursor:pointer' onclick='stockByAsc(this.id)'>&dtrif;</span></th>
		</tr>
		</thead>
		<tbody>";

		$num = $result->num_rows;
		for($i = 0; $i < $num; $i++){
		$result->data_seek($i);
		$row = $result->fetch_array(MYSQLI_ASSOC);
		echo "<tr id='" . $row['stockID'] . "' ondblclick='deleteStockItemRow(this.id)'>
				<td id='" . $row['stockID'] . "'><input type='checkbox' name='selectMe[]' onchange='selectMe(this)' table='stock' value='" . $row['stockID'] . "' id='" . $row['stockID'] . "' class='getcheckbox'></td>
				<td>" . $row['stockID'] . "</td>
				<input type='hidden' name='stockID[]' value='" . $row['stockID'] . "'> 
				<td><input type='text' name='editproductcode[]' id='productCode' value='" . $row['productCode'] . "'></td>
				<td><input type='text' name='editquantity[]' id='quantity' value='" . $row['quantity'] . "'></td>
				<td><span>$</span><input type='text' name='editprice[]' id='price' value='" . $row['price'] . "'></td>
				<td>" . $row['stamp'] . '</td></tr>';
		}
		echo "<tr><td></td><td></td><td></td><td></td><td></td><td><input type='submit' value='Update' class='btn btn-primary' onclick='updateStock()'></td></tbody></table></form><button style='font-size:25px; color:black' class='text-center btn btn-warning' onclick='directToPrint()'>Select Template</button></div>";
	}
}else if(isset($_POST['desc'])){
	$desc = $_POST['desc'];
	if(isset($_SESSION['fieldstock'])){
		$field = $_SESSION['fieldstock'];
		$search = $_SESSION['searchstock'];
		if($field == 'All'){
			$result = queryMysql("SELECT * FROM stock ORDER BY $desc DESC");	
		}else{
			$result = queryMysql("SELECT * FROM stock WHERE $field='$search' ORDER BY $desc DESC");
		}
	}else{
		$result = queryMysql("SELECT * FROM stock ORDER BY $desc DESC");
	}
	if($result->num_rows !== 0){
	echo "<div style='background-color:#f5f5f5; padding-bottom:10px; margin-bottom:10px; min-width:100%; overflow:auto; border-radius:10px; transform:translate(0,25px)' class='col-xs-12'>
		<span style='transform:translate(5px,10px)' class='fa fa-times-circle fa-2x close' onclick='emptyConsole()'></span>
		<form id='stockDatabase' method='post'>
		<table class='table table-striped'>
		<thead>
		<tr style='border-bottom:3px solid lightgrey'>
			<th class='text-center'><input type='checkbox' table='stock' id='selectAll' onchange='selectall(this)'></th>
			<th class='text-center'>Stock ID<span id='stockID' style='cursor:pointer' onclick='stockByDesc(this.id)'>&utrif;</span><span id='stockID' style='cursor:pointer' onclick='stockByAsc(this.id)'>&dtrif;</span></th>
			<th class='text-center'>Product Code<span id='productCode' style='cursor:pointer' onclick='stockByDesc(this.id)'>&utrif;</span><span id='productCode' style='cursor:pointer' onclick='stockByAsc(this.id)'>&dtrif;</span></th>
			<th class='text-center'>Quantity<span id='quantity'  style='cursor:pointer' onclick='stockByDesc(this.id)'>&utrif;</span><span id='quantity' style='cursor:pointer' onclick='stockByAsc(this.id)'>&dtrif;</span></th>
			<th class='text-center'>Price<span id='price' style='cursor:pointer' onclick='stockByDesc(this.id)'>&utrif;</span><span id='price' style='cursor:pointer' onclick='stockByAsc(this.id)'>&dtrif;</span></th>
			<th class='text-center'>Date<span id='stamp' style='cursor:pointer' onclick='stockByDesc(this.id)'>&utrif;</span><span id='stamp' style='cursor:pointer' onclick='stockByAsc(this.id)'>&dtrif;</span></th>
		</tr>
		</thead>
		<tbody>";

		$num = $result->num_rows;
		for($i = 0; $i < $num; $i++){
		$result->data_seek($i);
		$row = $result->fetch_array(MYSQLI_ASSOC);
		echo "<tr id='" . $row['stockID'] . "' ondblclick='deleteStockItemRow(this.id)'>
				<td id='" . $row['stockID'] . "'><input type='checkbox' name='selectMe[]' onchange='selectMe(this)' table='stock' value='" . $row['stockID'] . "' id='" . $row['stockID'] . "' class='getcheckbox'></td>
				<td>" . $row['stockID'] . "</td>
				<input type='hidden' name='stockID[]' value='" . $row['stockID'] . "'> 
				<td><input type='text' name='editproductcode[]' id='productCode' value='" . $row['productCode'] . "'></td>
				<td><input type='text' name='editquantity[]' id='quantity' value='" . $row['quantity'] . "'></td>
				<td><span>$</span><input type='text' name='editprice[]' id='price' value='" . $row['price'] . "'></td>
				<td>" . $row['stamp'] . '</td></tr>';
		}
		echo "<tr><td></td><td></td><td></td><td></td><td></td><td><input type='submit' value='Update' class='btn btn-primary' onclick='updateStock()'></td></tbody></table></form><button style='font-size:25px; color:black' class='text-center btn btn-warning' onclick='directToPrint()'>Select Template</button></div>";
	}
}else if(isset($_POST['stockID'])){//EDIT MULTIPLE STOCK ITEMS
	$i = 0;
	while(!empty($_POST['stockID'])){
		$stockID = $_POST['stockID'][$i];
		$productCode = $_POST['editproductcode'][$i];
		$quantity = $_POST['editquantity'][$i];
		$price = $_POST['editprice'][$i];
		$cookieName = $productCode;
		$cookieValue = $quantity;
		if(isset($_POST['selectMe'])){
			$remove = $_POST['selectMe'][$i];
			$result = queryMysql("DELETE FROM stock WHERE stockID='$remove'");
			setcookie($cookieName, '', time() - 3600, '/');
		}else {
			$result = queryMysql("SELECT * FROM stock");
			$rows = $result->num_rows;
			for($j = 0; $j < $rows; $j++){
				$result->data_seek($j);
				$row = $result->fetch_array(MYSQLI_ASSOC);
				$stock[$j] = array(
				'stockID' => $row['stockID'],
				'productCode' => $row['productCode'],
				'quantity' => $row['quantity'],
				'price' => $row['price']
				);
			}
			foreach($stock as $s){
				if($stockID == $s['stockID']){
					if($productCode !== $s['productCode']){
						$result = queryMysql("UPDATE stock SET productCode='$productCode' WHERE stockID='$stockID'");
					}
					if($quantity !== $s['quantity']){
						setcookie($cookieName, $cookieValue, time() + (86400 * 365), '/');
						$result = queryMysql("UPDATE stock SET quantity='$quantity' WHERE stockID='$stockID'");
					}
					if($price !== $s['price']){
						$result = queryMysql("UPDATE stock SET price='$price' WHERE stockID='$stockID'");
					}
				}
			}
		}
		$i++;
	}
}else if(isset($_POST['newproduct'])){// REGISTERING STOCK ITEM Manually
	$product = $_POST['newproduct'];
	$price = $_POST['newprice'];
	$quantity = $_POST['newquantity'];
	$result = queryMysql("SELECT * FROM stock");
	$stockcount = $result->num_rows;
	$stockID = $stockcount + 1;
	$stamp = date('Y-m-d');
	$cookieName = $product;
	$cookieValue = $quantity;
	setcookie($cookieName, $cookieValue, time() + (86400 * 365), '/');
	$result = queryMysql("INSERT INTO stock VALUES ($stockID,'$product',$quantity,'$price','$stamp')");
	$result = queryMysql("SELECT * FROM stock WHERE productCode='$product'");
	$rows = $result->num_rows;
	if($rows == 0){
		echo 'Sorry, submitted values couldnt be registered, try again...';
	}else{
		echo 'Stock item added successfully, refreshing please wait...';
	}
}else if(isset($_FILES['file']['name'])){// UPLOAD OF CSV FILE
	$file = $_FILES['file']['tmp_name'];
	$handle = fopen($file, 'r');
	while(($data = fgetcsv($handle, 1000, ',')) !== false){
		$result = queryMysql("SELECT * FROM stock");
		$num = $result->num_rows;
		$stockID = $num + 1;
		$product = $data[0];
		$quantity = $data[1];
		$price = $data[2];
		$stamp = date('Y-m-d');
		$cookieName = $product;
		$cookieValue = $quantity;
		setcookie($cookieName, $cookieValue, time() + (86400 * 365), '/');
	$result = queryMysql("SELECT * FROM stock WHERE productCode='$product'");
		if($result->num_rows == 0){
		$result = queryMysql("INSERT INTO stock VALUES ($stockID,'$product',$quantity,'$price','$stamp')");
		}
	}
	echo "Stock items updating accordingly and refreshing page, please wait...";
}else if(isset($_GET['resetStock'])){//RESET STOCK ITEMS
	$result = queryMysql('SELECT * FROM stock');
	$rows = $result->num_rows;
	for($i = 0; $i < $rows; $i++){
		$result->data_seek($i);
		$row = $result->fetch_array(MYSQLI_ASSOC); 
		setcookie($row['productCode'], '', time() - 3600, '/');
	}
	$result = queryMysql("DROP TABLE stock");
	$result = queryMysql("SHOW TABLES LIKE 'stock'");
	if($result->num_rows == 0){
		createTable('stock', '
		stockID BIGINT NOT NULL,
	    productCode varchar(200) NOT NULL,
	    quantity bigint(20) DEFAULT NULL,
	    price VARCHAR(50),
	    stamp VARCHAR(50) NOT NULL,
	    UNIQUE KEY (productCode),
	    UNIQUE KEY (stockID)'
		);
	}
	echo "Stock reset completed, refreshing page please wait...";
}else if(isset($_GET['stockBadge'])){
	$result = queryMysql('SELECT * FROM stock');
	$value = $result->num_rows;
	echo $value;
}else if(isset($_POST['eraseItemID'])){//DELETE SINGLE ITEM
	$id = $_POST['eraseItemID'];
	$result = queryMysql("SELECT * FROM stock WHERE stockID='$id'");
	$row = $result->fetch_array(MYSQLI_ASSOC);
	setcookie($row['productCode'], '', time() - 3600, '/');
	queryMysql("DELETE FROM stock WHERE stockID='$id'");

	if(isset($_SESSION['fieldstock'])){
		$field = $_SESSION['fieldstock'];
		$search = $_SESSION['searchstock'];
		$result = queryMysql("SELECT * FROM stock WHERE $field='$search' ORDER BY stockID ASC");
	}else{
		$result = queryMysql("SELECT * FROM stock ORDER BY stockID ASC");
	}
	if($result->num_rows !== 0){
		echo "<div style='background-color:#f5f5f5; padding-bottom:10px; margin-bottom:10px; min-width:100%; overflow:auto; border-radius:10px; transform:translate(0,25px)' class='col-xs-12'>
		<span style='transform:translate(5px,10px)' class='fa fa-times-circle fa-2x close' onclick='emptyConsole()'></span>
		<form id='stockDatabase' method='post'>
		<table class='table table-striped'>
		<thead>
		<tr style='border-bottom:3px solid lightgrey'>
			<th class='text-center'><input type='checkbox' table='stock' id='selectAll' onchange='selectall(this)'></th>
			<th class='text-center'>Stock ID<span id='stockID' style='cursor:pointer' onclick='stockByDesc(this.id)'>&utrif;</span><span id='stockID' style='cursor:pointer' onclick='stockByAsc(this.id)'>&dtrif;</span></th>
			<th class='text-center'>Product Code<span id='productCode' style='cursor:pointer' onclick='stockByDesc(this.id)'>&utrif;</span><span id='productCode' style='cursor:pointer' onclick='stockByAsc(this.id)'>&dtrif;</span></th>
			<th class='text-center'>Quantity<span id='quantity'  style='cursor:pointer' onclick='stockByDesc(this.id)'>&utrif;</span><span id='quantity' style='cursor:pointer' onclick='stockByAsc(this.id)'>&dtrif;</span></th>
			<th class='text-center'>Price<span id='price' style='cursor:pointer' onclick='stockByDesc(this.id)'>&utrif;</span><span id='price' style='cursor:pointer' onclick='stockByAsc(this.id)'>&dtrif;</span></th>
			<th class='text-center'>Date<span id='stamp' style='cursor:pointer' onclick='stockByDesc(this.id)'>&utrif;</span><span id='stamp' style='cursor:pointer' onclick='stockByAsc(this.id)'>&dtrif;</span></th>
		</tr>
		</thead>
		<tbody>";

		$num = $result->num_rows;
		for($i = 0; $i < $num; $i++){
		$result->data_seek($i);
		$row = $result->fetch_array(MYSQLI_ASSOC);
		echo "<tr id='" . $row['stockID'] . "' ondblclick='deleteStockItemRow(this.id)'>
				<td id='" . $row['stockID'] . "'><input type='checkbox' name='selectMe[]' onchange='selectMe(this)' table='stock' value='" . $row['stockID'] . "' id='" . $row['stockID'] . "' class='getcheckbox'></td>
				<td>" . $row['stockID'] . "</td>
				<input type='hidden' name='stockID[]' value='" . $row['stockID'] . "'> 
				<td><input type='text' name='editproductcode[]' id='productCode' value='" . $row['productCode'] . "'></td>
				<td><input type='text' name='editquantity[]' id='quantity' value='" . $row['quantity'] . "'></td>
				<td><span>$</span><input type='text' name='editprice[]' id='price' value='" . $row['price'] . "'></td>
				<td>" . $row['stamp'] . '</td></tr>';
		}
		echo "<tr><td></td><td></td><td></td><td></td><td></td><td><input type='submit' value='Update' class='btn btn-primary' onclick='updateStock()'></td></tbody></table></form><button style='font-size:25px; color:black' class='text-center btn btn-warning' onclick='directToPrint()'>Select Template</button></div>";
	}
}else{//USER VIEW
 echo "<div style='color:darkgrey; border-bottom:1px solid grey; min-width:300px' class='col-xs-8 col-xs-offset-2 page-header text-center display'><h5>" . ucfirst($_SESSION['user']) . "'s Stock Items area</h5></div>
 		<div style='margin-bottom:10px; min-width:300px' class='text-center col-xs-8 col-xs-offset-2'>
	 		<div class='w3-card-4 col-xs-2 col-xs-offset-2'>
			<header class='w3-container'>
			<span id='EditOrder' class='fa fa-user fa-3x' onclick='loadPage(&#39;home.php&#39;)'></span>
			</header>
			<footer class='w3-container'>
				<h6 style='color:darkgrey'>" . ucfirst($_SESSION['user']) . "'s area</h6>
			</footer>
		</div>
		<div class='w3-card-4 col-xs-2'>
			<header class='w3-container'>
				<span id='stockPanel' class='fa fa-database fa-3x' onclick='loadPage(&#39;stock.php&#39;)' style='transform:translate(5px,0)'><i style='font-size:20px; transform:translate(5px,-30px)' class='fa fa-trash' title='Stock Reset' onclick='resetStock()'></i>
			</header>
			<footer class='w3-container'>
				<h6 style='color:darkgrey'>Stock Items</h6>
			</footer>
		</div>
		<div class='w3-card-4 col-xs-2'>
			<header class='w3-container'>
			<span id='EditOrder' class='fa fa-exchange fa-3x' onclick='loadPage(&#39;orders.php&#39;)'></span>
			</header>
			<footer class='w3-container'>
				<h6 style='color:darkgrey'>Orders</h6>
			</footer>
		</div>
		<div class='w3-card-4 col-xs-2'>
				<header class='w3-container'>
				<span class='fa fa-print fa-3x' onclick='location.assign(&#39;print.php&#39;)'></span>
				</header>
				<footer class='w3-container'>
					<h6 style='color:darkgrey'>Print</h6>
				</footer>
	 		</div>
	  </div>
	   <div id='boardView' class='text-center col-xs-12' style='min-width:500px'>
	  	<div class='alert alert-info skyblue'  id='pageview'>
	  	<i class='fa fa-times-circle fa-2x close' onclick='hideme8()'></i>
	  	<i class='fa fa-plus fa-2x' style='float:left' onclick='viewStockPanel()'></i>
	  	<h4 class='text-center page-header'>Stock Items Database</h4>
	  	<form autocomplete='off' style='padding-bottom:20px' id='searchDatabase' method='post' enctype='multipart/form-data'>
	  	<input type='text' name='searchD' class='fixInput text-center' style='width:100%' placeholder='Search'>
	  	By: <select id='field' name='field'>
	  	<option selected='selected' value='All'>All</option>
	  	<option value='stockID'>Stock ID</option>
	  	<option value='productCode'>Product Code</option>
	  	<option value='quantity'>Quantity</option>
	  	<option value='price'>Price</option>
	  	<option value='stamp'>Date</option>
	  	</select>
	  	<input type='date' name='date'>
	  	<input type='submit' id='searchem' style='float:right; z-index:1; transform:translate(0,10px)' value='Search' class='btn btn-primary' onclick='searchDatabase()'>
	  	</form>
	  	<div id='boardConsole'>
	  	</div>
	  	</div>
	  	</div>
	  	<div id='targetStock' class='col-xs-6 col-xs-offset-3'>
		<div class='alert alert-info skyblue'>
			<i class='fa fa-arrow-circle-left fa-2x' style='float:left' onclick='showBoardConsole()'></i>
			<i class='fa fa-times-circle fa-2x close' onclick='hideme3()'></i>
			<h4 class='text-center page-header'>Stock Item Registration</h4>
			<button style='margin-bottom:5px' type='button' class='btn btn-lg btn-primary fa fa-file' onclick='viewCsvUpload()'> Upload csv file</button>
			<form autocomple='off' id='csv_upload' style='width:200px'>
			<i class='fa fa-times-circle close' onclick='hideme4()'></i>
			<input type='file' id='file' name='file'>
			<input style='margin-bottom:5px' type='submit' name='submitFile' value='Upload' class='btn btn-primary' onclick='submitStockCSV()'>
			</form>
			<form id='newStock' autocomplete='off' class='text-center'>
				<input class='fixInput text-center' type='text' id='productCode' name='newproduct' placeholder='Product Code'>
				<input class='fixInput text-center' type='text' id='price' name='newprice' placeholder='Price'>
				<input class='fixInput text-center' type='text' id='quantity' name='newquantity' placeholder='Quantity'><br><br>
				<input type='submit' value='Submit' id='addem' class='btn btn-primary' onclick='submitNewStock()'>
			</form>
		</div>
	  </div>
	  ";
}
?>