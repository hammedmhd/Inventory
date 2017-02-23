<?php
include 'functions.php';
session_name('Store');
session_start();
// add this to i element onclick='selectStatus(this)  // add this to select element for status change without submit onchange='updateStatus(this)
if(isset($_POST['search'])){//SEARCH ORDERS
	$date =  sanitizeString($_POST['date']);
	$search = sanitizeString($_POST['search']);
	$field = sanitizeString($_POST['field']);
	if($field == 'date'){
		$search = $date;
	}
	$_SESSION['field'] = $field;
	$_SESSION['search'] = $search;
	$result = queryMysql("SELECT * FROM orders WHERE $field='$search' ORDER BY reportID");
	if($result->num_rows !== 0){
		echo "<div style='background-color:#f5f5f5; padding-bottom:10px; margin-bottom:10px; min-width:100%; overflow:auto; border-radius:10px; transform:translate(0,25px)' class='col-xs-12'>
		<span style='transform:translate(5px,10px)' class='fa fa-times-circle fa-2x close' onclick='emptyConsole2()'></span>
		<form id='orderDatabase' method='post'>
		<table class='table table-striped'>
		<thead>
		<tr style='border-bottom:3px solid lightgrey'>
			<th class='text-center'><input type='checkbox' table='orders' id='selectAll' onchange='selectall(this)'></th>
			<th class='text-center'>ReportID<span id='reportID' style='cursor:pointer' onclick='ordersByDesc(this.id)'>&utrif;</span><span id='reportID' style='cursor:pointer' onclick='ordersByAsc(this.id)'>&dtrif;</span></th>
			<th class='text-center'>Name<span id='name' style='cursor:pointer' onclick='ordersByDesc(this.id)'>&utrif;</span><span id='name' style='cursor:pointer' onclick='ordersByAsc(this.id)'>&dtrif;</span></th>
			<th class='text-center'>Address<span id='address' style='cursor:pointer' onclick='ordersByDesc(this.id)'>&utrif;</span><span id='address' style='cursor:pointer' onclick='ordersByAsc(this.id)'>&dtrif;</span></th>
			<th class='text-center'>Phone Number<span id='contactNum' style='cursor:pointer' onclick='ordersByDesc(this.id)'>&utrif;</span><span id='contactNum' style='cursor:pointer' onclick='ordersByAsc(this.id)'>&dtrif;</span></th>
			<th class='text-center'>Item<span id='productCode' style='cursor:pointer' onclick='ordersByDesc(this.id)'>&utrif;</span><span id='productCode' style='cursor:pointer' onclick='ordersByAsc(this.id)'>&dtrif;</span></th>
			<th class='text-center'>Quantity<span id='quantity' style='cursor:pointer' onclick='ordersByDesc(this.id)'>&utrif;</span><span id='quantity' style='cursor:pointer' onclick='ordersByAsc(this.id)'>&dtrif;</span></th>
			<th class='text-center'>Tracking Number<span id='trackingNum' style='cursor:pointer' onclick='ordersByDesc(this.id)'>&utrif;</span><span id='trackingNum' style='cursor:pointer' onclick='ordersByAsc(this.id)'>&dtrif;</span></th>
			<th class='text-center'><i class='fa fa-circle' style='cursor:pointer; color:red' onclick='jobDone(this)'></i> <i class='fa fa-circle' style='cursor:pointer; color:gold' onclick='jobDone1(this)'></i> <i class='fa fa-circle' style='cursor:pointer; color:limegreen' onclick='jobDone2(this)'></i><br>Shipping Status<span id='shippingStatus' style='cursor:pointer' onclick='ordersByDesc(this.id)'>&utrif;</span><span id='shippingStatus' style='cursor:pointer' onclick='ordersByAsc(this.id)'>&dtrif;</span></th>
			<th class='text-center'>Bank<span id='bank' style='cursor:pointer' onclick='ordersByDesc(this.id)'>&utrif;</span><span id='bank' style='cursor:pointer' onclick='ordersByAsc(this.id)'>&dtrif;</span></th>
			<th class='text-center'>Remarks<span id='remarks' style='cursor:pointer' onclick='ordersByDesc(this.id)'>&utrif;</span><span id='remarks' style='cursor:pointer' onclick='ordersByAsc(this.id)'>&dtrif;</span></th>
			<th class='text-center'>Total Price<span id='totalPrice' style='cursor:pointer' onclick='ordersByDesc(this.id)'>&utrif;</span><span id='totalPrice' style='cursor:pointer' onclick='ordersByAsc(this.id)'>&dtrif;</span></th>
			<th class='text-center'>Date<span id='date' style='cursor:pointer' onclick='ordersByDesc(this.id)'>&utrif;</span><span id='date' style='cursor:pointer' onclick='ordersByAsc(this.id)'>&dtrif;</span></th>
		</tr>
		</thead>
		<tbody>";

		$num = $result->num_rows;
		for($i = 0; $i < $num; $i++){
		$result->data_seek($i);
		$select;
		$var;
		$row = $result->fetch_array(MYSQLI_ASSOC);
		if($row['shippingStatus'] == 0){
			$var = 0;
			$row['shippingStatus'] = 'color:red';
			$select = "<select class='options' id='".$row['reportID']."' name='editShippingstatus[]' status='" . $row['shippingStatus'] . "'><option selected='selected' value='0'>" . strtoupper('pending') . "</option><option value='1'>" . strtoupper('shipping') . "</option><option value='2'>" . strtoupper('completed') . "</option></select>";
		}else if($row['shippingStatus'] == 1){
			$var = 1;
			$row['shippingStatus'] = 'color:gold';
			$select = "<select class='options' id='".$row['reportID']."' name='editShippingstatus[]' status='" . $row['shippingStatus'] . "'><option value='0'>" . strtoupper('pending') . "</option><option selected='selected' value='1'>" . strtoupper('shipping') . "</option><option value='2'>" . strtoupper('completed') . "</option></select>";
		}else if($row['shippingStatus'] == 2){
			$var = 2;
			$row['shippingStatus'] = 'color:limegreen';
			$select = "<select class='options' id='".$row['reportID']."' name='editShippingstatus[]' status='" . $row['shippingStatus'] . "'><option value='0'>" . strtoupper('pending') . "</option><option selected='selected' value='1'>" . strtoupper('shipping') . "</option><option selected='selected' value='2'>" . strtoupper('completed') . "</option></select>";
		}
		echo "<tr id='" . $row['reportID'] . "' status='$var' ondblclick='deleteOrdeRow(this)'>
				<td id='" . $row['reportID'] . "'><input type='checkbox' id='" . $row['reportID'] . "' class='getcheckbox' onchange='selectMe(this)' table='orders' name='selectMe[]' value='" . $row['reportID'] . "'></td>
				<td>" . $row['reportID'] . "</td>
				<input type='hidden' name='reportID[]' value='" . $row['reportID'] . "'> 
				<td><input type='text' name='editName[]' id='editName' value='" . $row['name'] . "'></td>
				<td><input type='text' name='editAddress[]' id='editAddress' value='" . $row['address'] . "'></td>
				<td><input type='text' name='editContactNum[]' id='editcontactNum' value='" . $row['contactNum'] . "'</td>
				<td><input type='text' name='editProductCode[]' id='editproductcode' value='" . $row['productCode'] . "'</td>
				<td><input type='text' name='editQuantity[]' id='editquantityum' value='" . $row['quantity'] . "'</td>
				<td><input type='text' name='editTrackingnum[]' id='edittrackingnum' value='" . $row['trackingNum'] . "'</td>
				<td><i id='".$row['reportID']."' class='fa fa-circle statusChange' status='" . $row['shippingStatus'] . "' style='" . $row['shippingStatus'] . "; display:inline'></i>$select</td>
				<td><input type='text' name='editBank[]' id='editbank' value='" . $row['bank'] . "'</td>
				<td><input type='text' name='editRemarks[]' id='editremarks' value='" . $row['remarks'] . "'</td>
				<td>" . $row['totalPrice'] . "</td>
				<td>" . $row['date'] . "</td>
				</tr>";
		}
		echo "<tr><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td><input type='submit' value='Update' class='btn btn-primary' onclick='updateOrders()'></td></tr></tbody></table></form><button style='font-size:30px; color:black; transform:translate(0,-20px)' class='text-center btn btn-warning' onclick='directToPrint()'>Select Template</button></div>";
	}
}else if(isset($_POST['asc'])){
	$asc = $_POST['asc'];
	$field = $_SESSION['field'];
	$search = $_SESSION['search'];

 $result = queryMysql("SELECT * FROM orders WHERE $field='$search' ORDER BY $asc ASC");
	if($result->num_rows !== 0){
		echo "<div style='background-color:#f5f5f5; padding-bottom:10px; margin-bottom:10px; min-width:100%; overflow:auto; border-radius:10px; transform:translate(0,25px)' class='col-xs-12'>
		<span style='transform:translate(5px,10px)' class='fa fa-times-circle fa-2x close' onclick='emptyConsole2()'></span>
		<form id='orderDatabase' method='post'>
		<table class='table table-striped'>
		<thead>
		<tr style='border-bottom:3px solid lightgrey'>
			<th class='text-center'><input type='checkbox' table='orders' id='selectAll' onchange='selectall(this)'></th>
			<th class='text-center'>ReportID<span id='reportID' style='cursor:pointer' onclick='ordersByDesc(this.id)'>&utrif;</span><span id='reportID' style='cursor:pointer' onclick='ordersByAsc(this.id)'>&dtrif;</span></th>
			<th class='text-center'>Name<span id='name' style='cursor:pointer' onclick='ordersByDesc(this.id)'>&utrif;</span><span id='name' style='cursor:pointer' onclick='ordersByAsc(this.id)'>&dtrif;</span></th>
			<th class='text-center'>Address<span id='address' style='cursor:pointer' onclick='ordersByDesc(this.id)'>&utrif;</span><span id='address' style='cursor:pointer' onclick='ordersByAsc(this.id)'>&dtrif;</span></th>
			<th class='text-center'>Phone Number<span id='contactNum' style='cursor:pointer' onclick='ordersByDesc(this.id)'>&utrif;</span><span id='contactNum' style='cursor:pointer' onclick='ordersByAsc(this.id)'>&dtrif;</span></th>
			<th class='text-center'>Item<span id='productCode' style='cursor:pointer' onclick='ordersByDesc(this.id)'>&utrif;</span><span id='productCode' style='cursor:pointer' onclick='ordersByAsc(this.id)'>&dtrif;</span></th>
			<th class='text-center'>Quantity<span id='quantity' style='cursor:pointer' onclick='ordersByDesc(this.id)'>&utrif;</span><span id='quantity' style='cursor:pointer' onclick='ordersByAsc(this.id)'>&dtrif;</span></th>
			<th class='text-center'>Tracking Number<span id='trackingNum' style='cursor:pointer' onclick='ordersByDesc(this.id)'>&utrif;</span><span id='trackingNum' style='cursor:pointer' onclick='ordersByAsc(this.id)'>&dtrif;</span></th>
			<th class='text-center'><i class='fa fa-circle' style='cursor:pointer; color:red' onclick='jobDone(this)'></i> <i class='fa fa-circle' style='cursor:pointer; color:gold' onclick='jobDone1(this)'></i> <i class='fa fa-circle' style='cursor:pointer; color:limegreen' onclick='jobDone2(this)'></i><br>Shipping Status<span id='shippingStatus' style='cursor:pointer' onclick='ordersByDesc(this.id)'>&utrif;</span><span id='shippingStatus' style='cursor:pointer' onclick='ordersByAsc(this.id)'>&dtrif;</span></th>
			<th class='text-center'>Bank<span id='bank' style='cursor:pointer' onclick='ordersByDesc(this.id)'>&utrif;</span><span id='bank' style='cursor:pointer' onclick='ordersByAsc(this.id)'>&dtrif;</span></th>
			<th class='text-center'>Remarks<span id='remarks' style='cursor:pointer' onclick='ordersByDesc(this.id)'>&utrif;</span><span id='remarks' style='cursor:pointer' onclick='ordersByAsc(this.id)'>&dtrif;</span></th>
			<th class='text-center'>Total Price<span id='totalPrice' style='cursor:pointer' onclick='ordersByDesc(this.id)'>&utrif;</span><span id='totalPrice' style='cursor:pointer' onclick='ordersByAsc(this.id)'>&dtrif;</span></th>
			<th class='text-center'>Date<span id='date' style='cursor:pointer' onclick='ordersByDesc(this.id)'>&utrif;</span><span id='date' style='cursor:pointer' onclick='ordersByAsc(this.id)'>&dtrif;</span></th>
		</tr>
		</thead>
		<tbody>";

		$num = $result->num_rows;
		for($i = 0; $i < $num; $i++){
		$result->data_seek($i);
		$select;
		$var;
		$row = $result->fetch_array(MYSQLI_ASSOC);
		if($row['shippingStatus'] == 0){
			$var = 0;
			$row['shippingStatus'] = 'color:red';
			$select = "<select class='options' id='".$row['reportID']."' name='editShippingstatus[]' status='" . $row['shippingStatus'] . "'><option selected='selected' value='0'>" . strtoupper('pending') . "</option><option value='1'>" . strtoupper('shipping') . "</option><option value='2'>" . strtoupper('completed') . "</option></select>";
		}else if($row['shippingStatus'] == 1){
			$var = 1;
			$row['shippingStatus'] = 'color:gold';
			$select = "<select class='options' id='".$row['reportID']."' name='editShippingstatus[]' status='" . $row['shippingStatus'] . "'><option value='0'>" . strtoupper('pending') . "</option><option selected='selected' value='1'>" . strtoupper('shipping') . "</option><option value='2'>" . strtoupper('completed') . "</option></select>";
		}else if($row['shippingStatus'] == 2){
			$var = 2;
			$row['shippingStatus'] = 'color:limegreen';
			$select = "<select class='options' id='".$row['reportID']."' name='editShippingstatus[]' status='" . $row['shippingStatus'] . "'><option value='0'>" . strtoupper('pending') . "</option><option selected='selected' value='1'>" . strtoupper('shipping') . "</option><option selected='selected' value='2'>" . strtoupper('completed') . "</option></select>";
		}
		echo "<tr id='" . $row['reportID'] . "' status='$var' ondblclick='deleteOrdeRow(this)'>
				<td id='" . $row['reportID'] . "'><input type='checkbox' id='" . $row['reportID'] . "' class='getcheckbox' onchange='selectMe(this)' table='orders' name='selectMe[]' value='" . $row['reportID'] . "'></td>
				<td>" . $row['reportID'] . "</td>
				<input type='hidden' name='reportID[]' value='" . $row['reportID'] . "'> 
				<td><input type='text' name='editName[]' id='editName' value='" . $row['name'] . "'></td>
				<td><input type='text' name='editAddress[]' id='editAddress' value='" . $row['address'] . "'></td>
				<td><input type='text' name='editContactNum[]' id='editcontactNum' value='" . $row['contactNum'] . "'</td>
				<td><input type='text' name='editProductCode[]' id='editproductcode' value='" . $row['productCode'] . "'</td>
				<td><input type='text' name='editQuantity[]' id='editquantityum' value='" . $row['quantity'] . "'</td>
				<td><input type='text' name='editTrackingnum[]' id='edittrackingnum' value='" . $row['trackingNum'] . "'</td>
				<td><i id='".$row['reportID']."' class='fa fa-circle statusChange' status='" . $row['shippingStatus'] . "' style='" . $row['shippingStatus'] . "; display:inline'></i>$select</td>
				<td><input type='text' name='editBank[]' id='editbank' value='" . $row['bank'] . "'</td>
				<td><input type='text' name='editRemarks[]' id='editremarks' value='" . $row['remarks'] . "'</td>
				<td>" . $row['totalPrice'] . "</td>
				<td>" . $row['date'] . "</td>
				</tr>";
		}
		echo "<tr><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td><input type='submit' value='Update' class='btn btn-primary' onclick='updateOrders()'></td></tr></tbody></table></form><button style='font-size:30px; color:black; transform:translate(0,-20px)' class='text-center btn btn-warning' onclick='directToPrint()'>Select Template</button></div>";
	}
}else if(isset($_POST['desc'])){
	$desc = $_POST['desc'];
	$field = $_SESSION['field'];
	$search = $_SESSION['search'];

 $result = queryMysql("SELECT * FROM orders WHERE $field='$search' ORDER BY $desc DESC");
	if($result->num_rows !== 0){
		echo "<div style='background-color:#f5f5f5; padding-bottom:10px; margin-bottom:10px; min-width:100%; overflow:auto; border-radius:10px; transform:translate(0,25px)' class='col-xs-12'>
		<span style='transform:translate(5px,10px)' class='fa fa-times-circle fa-2x close' onclick='emptyConsole2()'></span>
		<form id='orderDatabase' method='post'>
		<table class='table table-striped'>
		<thead>
		<tr style='border-bottom:3px solid lightgrey'>
			<th class='text-center'><input type='checkbox' table='orders' id='selectAll' onchange='selectall(this)'></th>
			<th class='text-center'>ReportID<span id='reportID' style='cursor:pointer' onclick='ordersByDesc(this.id)'>&utrif;</span><span id='reportID' style='cursor:pointer' onclick='ordersByAsc(this.id)'>&dtrif;</span></th>
			<th class='text-center'>Name<span id='name' style='cursor:pointer' onclick='ordersByDesc(this.id)'>&utrif;</span><span id='name' style='cursor:pointer' onclick='ordersByAsc(this.id)'>&dtrif;</span></th>
			<th class='text-center'>Address<span id='address' style='cursor:pointer' onclick='ordersByDesc(this.id)'>&utrif;</span><span id='address' style='cursor:pointer' onclick='ordersByAsc(this.id)'>&dtrif;</span></th>
			<th class='text-center'>Phone Number<span id='contactNum' style='cursor:pointer' onclick='ordersByDesc(this.id)'>&utrif;</span><span id='contactNum' style='cursor:pointer' onclick='ordersByAsc(this.id)'>&dtrif;</span></th>
			<th class='text-center'>Item<span id='productCode' style='cursor:pointer' onclick='ordersByDesc(this.id)'>&utrif;</span><span id='productCode' style='cursor:pointer' onclick='ordersByAsc(this.id)'>&dtrif;</span></th>
			<th class='text-center'>Quantity<span id='quantity' style='cursor:pointer' onclick='ordersByDesc(this.id)'>&utrif;</span><span id='quantity' style='cursor:pointer' onclick='ordersByAsc(this.id)'>&dtrif;</span></th>
			<th class='text-center'>Tracking Number<span id='trackingNum' style='cursor:pointer' onclick='ordersByDesc(this.id)'>&utrif;</span><span id='trackingNum' style='cursor:pointer' onclick='ordersByAsc(this.id)'>&dtrif;</span></th>
			<th class='text-center'><i class='fa fa-circle' style='cursor:pointer; color:red' onclick='jobDone(this)'></i> <i class='fa fa-circle' style='cursor:pointer; color:gold' onclick='jobDone1(this)'></i> <i class='fa fa-circle' style='cursor:pointer; color:limegreen' onclick='jobDone2(this)'></i><br>Shipping Status<span id='shippingStatus' style='cursor:pointer' onclick='ordersByDesc(this.id)'>&utrif;</span><span id='shippingStatus' style='cursor:pointer' onclick='ordersByAsc(this.id)'>&dtrif;</span></th>
			<th class='text-center'>Bank<span id='bank' style='cursor:pointer' onclick='ordersByDesc(this.id)'>&utrif;</span><span id='bank' style='cursor:pointer' onclick='ordersByAsc(this.id)'>&dtrif;</span></th>
			<th class='text-center'>Remarks<span id='remarks' style='cursor:pointer' onclick='ordersByDesc(this.id)'>&utrif;</span><span id='remarks' style='cursor:pointer' onclick='ordersByAsc(this.id)'>&dtrif;</span></th>
			<th class='text-center'>Total Price<span id='totalPrice' style='cursor:pointer' onclick='ordersByDesc(this.id)'>&utrif;</span><span id='totalPrice' style='cursor:pointer' onclick='ordersByAsc(this.id)'>&dtrif;</span></th>
			<th class='text-center'>Date<span id='date' style='cursor:pointer' onclick='ordersByDesc(this.id)'>&utrif;</span><span id='date' style='cursor:pointer' onclick='ordersByAsc(this.id)'>&dtrif;</span></th>
		</tr>
		</thead>
		<tbody>";

		$num = $result->num_rows;
		for($i = 0; $i < $num; $i++){
		$result->data_seek($i);
		$select;
		$var;
		$row = $result->fetch_array(MYSQLI_ASSOC);
		if($row['shippingStatus'] == 0){
			$var = 0;
			$row['shippingStatus'] = 'color:red';
			$select = "<select class='options' id='".$row['reportID']."' name='editShippingstatus[]' status='" . $row['shippingStatus'] . "'><option selected='selected' value='0'>" . strtoupper('pending') . "</option><option value='1'>" . strtoupper('shipping') . "</option><option value='2'>" . strtoupper('completed') . "</option></select>";
		}else if($row['shippingStatus'] == 1){
			$var = 1;
			$row['shippingStatus'] = 'color:gold';
			$select = "<select class='options' id='".$row['reportID']."' name='editShippingstatus[]' status='" . $row['shippingStatus'] . "'><option value='0'>" . strtoupper('pending') . "</option><option selected='selected' value='1'>" . strtoupper('shipping') . "</option><option value='2'>" . strtoupper('completed') . "</option></select>";
		}else if($row['shippingStatus'] == 2){
			$var = 2;
			$row['shippingStatus'] = 'color:limegreen';
			$select = "<select class='options' id='".$row['reportID']."' name='editShippingstatus[]' status='" . $row['shippingStatus'] . "'><option value='0'>" . strtoupper('pending') . "</option><option selected='selected' value='1'>" . strtoupper('shipping') . "</option><option selected='selected' value='2'>" . strtoupper('completed') . "</option></select>";
		}
		echo "<tr id='" . $row['reportID'] . "' status='$var' ondblclick='deleteOrdeRow(this)'>
				<td id='" . $row['reportID'] . "'><input type='checkbox' id='" . $row['reportID'] . "' class='getcheckbox' onchange='selectMe(this)' table='orders' name='selectMe[]' value='" . $row['reportID'] . "'></td>
				<td>" . $row['reportID'] . "</td>
				<input type='hidden' name='reportID[]' value='" . $row['reportID'] . "'> 
				<td><input type='text' name='editName[]' id='editName' value='" . $row['name'] . "'></td>
				<td><input type='text' name='editAddress[]' id='editAddress' value='" . $row['address'] . "'></td>
				<td><input type='text' name='editContactNum[]' id='editcontactNum' value='" . $row['contactNum'] . "'</td>
				<td><input type='text' name='editProductCode[]' id='editproductcode' value='" . $row['productCode'] . "'</td>
				<td><input type='text' name='editQuantity[]' id='editquantityum' value='" . $row['quantity'] . "'</td>
				<td><input type='text' name='editTrackingnum[]' id='edittrackingnum' value='" . $row['trackingNum'] . "'</td>
				<td><i id='".$row['reportID']."' class='fa fa-circle statusChange' status='" . $row['shippingStatus'] . "' style='" . $row['shippingStatus'] . "; display:inline'></i>$select</td>
				<td><input type='text' name='editBank[]' id='editbank' value='" . $row['bank'] . "'</td>
				<td><input type='text' name='editRemarks[]' id='editremarks' value='" . $row['remarks'] . "'</td>
				<td>" . $row['totalPrice'] . "</td>
				<td>" . $row['date'] . "</td>
				</tr>";
		}
		echo "<tr><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td><input type='submit' value='Update' class='btn btn-primary' onclick='updateOrders()'></td></tr></tbody></table></form><button style='font-size:30px; color:black; transform:translate(0,-20px)' class='text-center btn btn-warning' onclick='directToPrint()'>Select Template</button></div>";
	}
}else if(isset($_POST['updateStatus'])){
	$status = $_POST['updateStatus'];
	$id = $_POST['idu'];
	if($status == 0){
		$result = queryMysql("UPDATE orders SET shippingStatus='$status' WHERE reportID='$id'");
	}else if($status == 1){
		$result = queryMysql("UPDATE orders SET shippingStatus='$status' WHERE reportID='$id'");
	}else if($status == 2){
		$result = queryMysql("UPDATE orders SET shippingStatus='$status' WHERE reportID='$id'");
	}
}else if(isset($_POST['reportID'])){
	$i = 0;
	while(!empty($_POST['reportID'])){
		$reportID = $_POST['reportID'][$i];
		echo $reportID . ' ';
		$name = $_POST['editName'][$i];
		$address = $_POST['editAddress'][$i];
		$phone = $_POST['editContactNum'][$i];
		$productCode = $_POST['editProductCode'][$i];
		$quantity = $_POST['editQuantity'][$i];
		$tn = $_POST['editTrackingnum'][$i];
		$shp = $_POST['editShippingstatus'][$i];
		$bank = $_POST['editBank'][$i];
		$remark = $_POST['editRemarks'][$i];
		if(isset($_POST['selectMe'])){
			$remove = $_POST['selectMe'][$i];
			$result = queryMysql("DELETE FROM orders WHERE reportID='$remove'");
		}else{
	 		$result = queryMysql("SELECT * FROM orders");
			$rows = $result->num_rows;
			for($j = 0; $j < $rows; $j++){
				$result->data_seek($j);
				$row = $result->fetch_array(MYSQLI_ASSOC);
				$orders[$j] = array(
				'reportID' => $row['reportID'],
				'name' => $row['productCode'],
				'address' => $row['quantity'],
				'phone' => $row['contactNum'],
				'productCode' => $row['productCode'],
				'quantity' => $row['quantity'],
				'trackingNumber' => $row['trackingNum'],
				'shippingStatus' => $row['shippingStatus'], 
				'bank' => $row['bank'],
				'remarks' => $row['remarks']
				);
			}
			foreach($orders as $id => $o){
				if($reportID == $o['reportID']){
					if($name !== $o['name']){
						$result = queryMysql("UPDATE orders SET name='$name' WHERE reportID='$reportID'");
					}
					if($address !== $o['address']){
						$result = queryMysql("UPDATE orders SET address='$address' WHERE reportID='$reportID'");
					}
					if($phone !== $o['phone']){
						$result = queryMysql("UPDATE orders SET contactNum='$phone' WHERE reportID='$reportID'");
					}
					if($productCode !== $o['productCode']){
						$result = queryMysql("UPDATE orders SET productCode='$productCode' WHERE reportID='$reportID'");
					}
					if($quantity !== $o['quantity']){
						//setcookie($cookieName, $cookieValue, time() + (86400 * 365), '/');
						$result = queryMysql("UPDATE orders SET quantity='$quantity' WHERE reportID='$reportID'");
					}				
					if($tn !== $o['trackingNumber']){
						$result = queryMysql("UPDATE orders SET trackingNum='$tn' WHERE reportID='$reportID'");
					}
					if($shp !== $o['shippingStatus']){
						$result = queryMysql("UPDATE orders SET shippingStatus='$shp' WHERE reportID='$reportID'");
					}
					if($bank !== $o['bank']){
						$result = queryMysql("UPDATE orders SET bank='$bank' WHERE reportID='$reportID'");
					}
					if($remark !== $o['remarks']){
						$result = queryMysql("UPDATE orders SET remarks='$remark' WHERE reportID='$reportID'");
					}		
				}
			}
		}
		$i++;
	}
}else if(isset($_FILES['file']['name'])){//CSV UPLOAD ENTRY
	$file = $_FILES['file']['tmp_name'];
	$handle = fopen($file, 'r');
	while(($data = fgetcsv($handle, 1000, ',')) !== false){
		$result = queryMysql("SELECT * FROM orders");
		$num = $result->num_rows;
		$reportID = $num + 1;
		$cusName = $data[0];
		$platform = $data[1];
		$address = $data[2];
		$contactNum = $data[3];
		$quantity = $data[4];
		$productCode = $data[5];
		$trackingNumber = $data[6];
		$shippingStatus = $data[7];
		$remarks = $data[8];
		$bank = $data[9];
		$stamp = date('Y-m-d');
		$totalPrice = 0;

		//$cookieName = $product;
		//$cookieValue = $quantity;
		//setcookie($cookieName, $cookieValue, time() + (86400 * 365), '/');
	$result = queryMysql("SELECT * FROM orders WHERE reportID='$reportID'");
		if($result->num_rows == 0){
		$result = queryMysql("INSERT INTO orders VALUES($reportID,'$platform','$cusName','$address','$contactNum','$quantity','$productCode','$stamp','$trackingNumber','$shippingStatus','$remarks','$bank','$totalPrice')");
		}
	}	echo "Orders updating accordingly and refreshing page, please wait...";
}else if(isset($_POST['trackingNumber'])){// regestering new manual order entry;
	$stamp = date('Y-m-d');
	$result = queryMysql("SELECT * FROM orders");
	$ordercount = $result->num_rows;
	$reportid = $ordercount + 1;
	$cusName = $_POST['cusName'];
	$platform = $_POST['platform'];
	$address = $_POST['address'];
	$contactNum = $_POST['contactNumber']; 
	$quantity = $_POST['quantity'];
	$productCode = $_POST['productCode'];
	$trackingnumber = $_POST['trackingNumber'];
	$shippingstatus = $_POST['shippingStatus'];
	$bank = $_POST['bank'];
	$remarks = $_POST['remarks'];
	$totalPrice = 0;

	$result = queryMysql("SELECT * FROM stock WHERE productCode='$productCode'");
	if($result->num_rows !== 0){
		$result = queryMysql("INSERT INTO orders VALUES ($reportid,'$platform','$cusName','$address','$contactNum',$quantity,'$productCode','$stamp','$trackingnumber',$shippingstatus,'$remarks','$bank','$totalPrice')");
		$result = queryMysql("SELECT * FROM orders WHERE reportID='$reportid'");
		$rows = $result->num_rows;
		if($rows == 0){
			echo 'Sorry, submitted values couldnt be registered, try again...';
		}else{
			echo 'Orders added successfully, refreshing please wait...';
		}
	}else{
		echo 'Sorry, submitted values couldnt be registered, try again...';
	}
}else if(isset($_GET['resetOrder'])){//RESET Order ITEMS
	$result = queryMysql('SELECT * FROM orders');
	$row = $result->num_rows;
	$i = 1;
	while($i <= $row){
		queryMysql("DELETE FROM orders WHERE reportID='$i'");
		$i++;
	}
	echo "Orders reset completed, refreshing page please wait...";
}else if(isset($_POST['eraseItemID'])){
	$id = $_POST['eraseItemID'];
	queryMysql("DELETE FROM orders WHERE reportID='$id'");

  $field = $_SESSION['field'];
  $search =	$_SESSION['search'];

	$result = queryMysql("SELECT * FROM orders WHERE $field='$search' ORDER BY reportID");
	if($result->num_rows !== 0){
		echo "<div style='background-color:#f5f5f5; padding-bottom:10px; margin-bottom:10px; min-width:100%; overflow:auto; border-radius:10px; transform:translate(0,25px)' class='col-xs-12'>
		<span style='transform:translate(5px,10px)' class='fa fa-times-circle fa-2x close' onclick='emptyConsole2()'></span>
		<form id='orderDatabase' method='post'>
		<table class='table table-striped'>
		<thead>
		<tr style='border-bottom:3px solid lightgrey'>
			<th class='text-center'><input type='checkbox' table='orders' id='selectAll' onchange='selectall(this)'></th>
			<th class='text-center'>ReportID</th>
			<th class='text-center'>Name</th>
			<th class='text-center'>Address</th>
			<th class='text-center'>Contact details</th>
			<th class='text-center'>Item</th>
			<th class='text-center'>Quantity</th>
			<th class='text-center'>Tracking Number</th>
			<th class='text-center'>Shipping Status<br><i class='fa fa-circle' style='cursor:pointer; color:red' onclick='jobDone(this)'></i> <i class='fa fa-circle' style='cursor:pointer; color:gold' onclick='jobDone1(this)'></i> <i class='fa fa-circle' style='cursor:pointer; color:limegreen' onclick='jobDone2(this)'></i></th>
			<th class='text-center'>Bank</th>
			<th class='text-center'>Remarks</th>
			<th class='text-center'>Total Price</th>
			<th class='text-center'>Date</th>
		</tr>
		</thead>
		<tbody>";

		$num = $result->num_rows;
		for($i = 0; $i < $num; $i++){
		$result->data_seek($i);
		$select;
		$var;
		$row = $result->fetch_array(MYSQLI_ASSOC);
		if($row['shippingStatus'] == 0){
			$var = 0;
			$row['shippingStatus'] = 'color:red';
			$select = "<select class='options' id='".$row['reportID']."' name='editShippingstatus[]' status='" . $row['shippingStatus'] . "'><option selected='selected' value='0'>" . strtoupper('pending') . "</option><option value='1'>" . strtoupper('shipping') . "</option><option value='2'>" . strtoupper('completed') . "</option></select>";
		}else if($row['shippingStatus'] == 1){
			$var = 1;
			$row['shippingStatus'] = 'color:gold';
			$select = "<select class='options' id='".$row['reportID']."' name='editShippingstatus[]' status='" . $row['shippingStatus'] . "'><option value='0'>" . strtoupper('pending') . "</option><option selected='selected' value='1'>" . strtoupper('shipping') . "</option><option value='2'>" . strtoupper('completed') . "</option></select>";
		}else if($row['shippingStatus'] == 2){
			$var = 2;
			$row['shippingStatus'] = 'color:limegreen';
			$select = "<select class='options' id='".$row['reportID']."' name='editShippingstatus[]' status='" . $row['shippingStatus'] . "'><option value='0'>" . strtoupper('pending') . "</option><option selected='selected' value='1'>" . strtoupper('shipping') . "</option><option selected='selected' value='2'>" . strtoupper('completed') . "</option></select>";
		}
		echo "<tr id='" . $row['reportID'] . "' status='$var' ondblclick='deleteOrdeRow(this)'>
				<td id='" . $row['reportID'] . "'><input type='checkbox' id='" . $row['reportID'] . "' class='getcheckbox' onchange='selectMe(this)' table='orders' name='selectMe[]' value='" . $row['reportID'] . "'></td>
				<td>" . $row['reportID'] . "</td>
				<input type='hidden' name='reportID[]' value='" . $row['reportID'] . "'> 
				<td><input type='text' name='editName[]' id='editName' value='" . $row['name'] . "'></td>
				<td><input type='text' name='editAddress[]' id='editAddress' value='" . $row['address'] . "'></td>
				<td><input type='text' name='editContactNum[]' id='editcontactNum' value='" . $row['contactNum'] . "'</td>
				<td><input type='text' name='editProductCode[]' id='editproductcode' value='" . $row['productCode'] . "'</td>
				<td><input type='text' name='editQuantity[]' id='editquantityum' value='" . $row['quantity'] . "'</td>
				<td><input type='text' name='editTrackingnum[]' id='edittrackingnum' value='" . $row['trackingNum'] . "'</td>
				<td><i id='".$row['reportID']."' class='fa fa-circle statusChange' status='" . $row['shippingStatus'] . "' style='" . $row['shippingStatus'] . "; display:inline'></i>$select</td>
				<td><input type='text' name='editBank[]' id='editbank' value='" . $row['bank'] . "'</td>
				<td><input type='text' name='editRemarks[]' id='editremarks' value='" . $row['remarks'] . "'</td>
				<td>" . $row['totalPrice'] . "</td>
				<td>" . $row['date'] . "</td>
				</tr>";
		}
		echo "<tr><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td><input type='submit' value='Update' class='btn btn-primary' onclick='updateOrders()'></td></tr></tbody></table></form><button style='font-size:30px; color:black; transform:translate(0,-20px)' class='text-center btn btn-warning' onclick='directToPrint()'>Select Template</button></div>";
	}
}else if(isset($_GET['statusBadge'])){
	$status = $_GET['statusBadge'];
	$result = queryMysql("SELECT * FROM orders WHERE shippingStatus='$status'");
	$value = $result->num_rows;
	echo $value;
}else{
 echo "<div style='color:darkgrey; border-bottom:1px solid grey; min-width:300px' class='col-xs-8 col-xs-offset-2 page-header text-center display'><h4>" . ucfirst($_SESSION['user']) . "'s area</h4></div>
 		<div style='margin-bottom:20px; min-width:300px' class='text-center col-xs-8 col-xs-offset-2'>
	 		<div class='w3-card-4 col-xs-2 col-xs-offset-2'>
			<header class='w3-container'>
			<span id='EditOrder' class='fa fa-user fa-3x' onclick='loadPage(&#39;home.php&#39;)'></span>
			</header>
			<footer class='w3-container'>
				<h5 style='color:darkgrey'>" . ucfirst($_SESSION['user']) . "'s area</h5>
			</footer>
		</div>
		<div class='w3-card-4 col-xs-2'>
			<header class='w3-container'>
				<span id='stockPanel' class='fa fa-database fa-3x' onclick='loadPage(&#39;stock.php&#39;)'>
			</header>
			<footer class='w3-container'>
				<h5 style='color:darkgrey'>Stock Items</h5>
			</footer>
		</div>
		<div class='w3-card-4 col-xs-2'>
			<header class='w3-container'>
			<span id='EditOrder' class='fa fa-exchange fa-3x' onclick='loadPage(&#39;orders.php&#39;)'></span><i style='font-size:20px; transform:translate(5px,-30px)' class='fa fa-trash' onclick='resetOrder()'></i>
			</header>
			<footer class='w3-container'>
				<h5 style='color:darkgrey'>Orders</h5>
			</footer>
		</div>
		<div class='w3-card-4 col-xs-2'>
				<header class='w3-container'>
				<span class='fa fa-print fa-3x' onclick='location.assign(&#39;print.php&#39;)'></span>
				</header>
				<footer class='w3-container'>
					<h5 style='color:darkgrey'>Print</h5>
				</footer>
	 	</div>
	  </div>
	   <div id='boardView2' class='text-center col-xs-12' style='min-width:500px'>
	  	<div class='alert alert-info boxgreen'>
	  	<i class='fa fa-times-circle fa-2x close' onclick='hideme9()'></i>
	  	<i class='fa fa-plus fa-2x' style='float:left' onclick='viewOrderEdit()'></i>
	  	<h4 class='text-center page-header'>Orders Database</h4>
	  	<form autocomplete='off' style='padding-bottom:20px' id='searchDatabase' method='post' enctype='multipart/form-data'>
	  	<input type='text' name='search' class='fixInput text-center' style='width:100%' placeholder='Search'>
	  	By: <select id='field' name='field'>
	  	<option value='reportID'>Report ID</option>
	  	<option value='trackingNum'>Tracking Number</option>
	  	<option value='name'>Customer Name</option>
	  	<option value='address'>Address</option>
	  	<option value='contactNum'>Contact Number</option>
	  	<option value='quantity'>Quantity</option>
	  	<option value='productCode'>Product Code</option>
	  	<option value='shippingStatus'>Shipping status</option>
	  	<option value='bank'>Bank</option>
	  	<option value='remarks'>Remarks</option>
	  	<option value='totalPrice'>Total Price</option>
	  	<option selected='selected' value='date'>Date</option>
	  	</select>
	  	<input type='date' name='date'>
	  	<input type='submit' style='float:right; z-index:1' value='Search' class='btn btn-primary' onclick='searchDatabase2()'>
	  	</form>
	  	<div id='boardConsole'>
	  	</div>
	  	</div>
	  </div>
	  <div id='targetOrder' class='col-xs-6 col-xs-offset-3'>
		<div class='alert alert-info boxgreen'>
			<i class='fa fa-times-circle fa-2x close' onclick='hideme2()'></i>
			<i class='fa fa-arrow-circle-left fa-2x' style='float:left' onclick='showBoardConsole2()'></i>
			<h4 class='text-center page-header'>Order Registration</h4>
			<button style='margin-bottom:5px' type='button' class='btn btn-lg btn-primary fa fa-file' onclick='viewCsvUpload2()'> Upload csv file</button>
			<form autocomple='off' id='csv_upload2' style='width:200px' enctype='multipart/form-data'>
			<i class='fa fa-times-circle close' onclick='hideme5()'></i>
			<input type='file' id='file' name='file'>
			<input style='margin-bottom:5px' type='submit' name='submitFile' value='Upload' class='btn btn-primary' onclick='submitOrderCSV()'>
			</form>
			<form id='newOrder' class='text-center'>
				<input class='fixInput' type='text' name='cusName' placeholder='Customer&#146s name'>
				<input class='fixInput' type='text' name='platform' placeholder='Platform'>
				<input class='fixInput' type='text' name='address' placeholder='Address'>
				<input class='fixInput' type='text' name='contactNumber' placeholder='Contact Number'>
				<input class='fixInput' type='text' name='productCode' placeholder='Product Code'>
				<input class='fixInput' type='text' name='quantity' placeholder='Quantity'>
				<input class='fixInput' type='text' name='trackingNumber' placeholder='Tracking Number'>
				<input class='fixInput' type='text' name='shippingStatus' placeholder='Shipping Status'>
				<input class='fixInput' type='text' name='bank' placeholder='Bank'>
				<input class='fixInput' type='text' name='remarks' placeholder='Remarks'><br><br>
				<input type='submit' value='Submit' class='btn btn-primary' onclick='submitNewOrder()'>
			</form>
		</div>
	  </div>";
	}
?>