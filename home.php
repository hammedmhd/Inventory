<?php
include 'functions.php';
session_name('Store');
session_start();

if(isset($_POST['usern'])){ //updating account username and pass
	$currentuser = $_SESSION['user'];
	$currentpass = $_SESSION['pass'];
	$edituser = sanitizeString($_POST['usern']);
	$editpass = sanitizeString($_POST['passw']);

	$result = queryMysql("SELECT * FROM members WHERE Username='$currentuser' AND Password='$currentpass'");
	if($result->num_rows !== 0){
		queryMysql("UPDATE members SET Username='$edituser', Password='$editpass' WHERE Username='$currentuser'");
		echo "Account login details updated successfully, logging you out, please re-sign in to continue using app.";
	}

}else if(isset($_POST['deleteUser'])){
	$result = queryMysql("DELETE FROM members WHERE Username='" . $_SESSION['user'] . "'");
	$result = queryMysql("SELECT * FROM members WHERE Username='" . $_SESSION['user'] . "'");
	if($result->num_rows !== 0){
		echo "Error erasing account, please re-sign in and try again.";
	}else{
		echo "Logging out and deleting account data, please wait...";
	}
}else if(isset($_POST['newuser'])){ // registering new account.
	$user = $_POST['newuser'];
	$pass = $_POST['newpassw'];

	$result = queryMysql("INSERT INTO members VALUES('$user','$pass')");
	$result = queryMysql("SELECT * FROM members WHERE Username='$user' AND Password='$pass'");
	if($result->num_rows !== 0){
		echo ucfirst($user) . "'s account created successfully. Refreshing, please wait...";
	}else{
		echo "Error while creating" . ucfirst($user) . "'s account.";
	}
}else{// main BOARD view
 echo "<div style='color:darkgrey; border-bottom:1px solid grey; min-width:300px' class='col-xs-8 col-xs-offset-2 page-header text-center display'><h4>" . ucfirst($_SESSION['user']) . "'s area</h4></div>
 		<div style='margin-bottom:20px; min-width:300px' class='text-center col-xs-8 col-xs-offset-2'>
	 		<div class='w3-card-4 col-xs-2 col-xs-offset-2'>
	 			<header class='w3-container'>
				  <span id='editUserNav' class='fa fa-users fa-3x' title='Edit User Panel' onclick='viewUserEdit()'></span>
				</header>
				<footer class='w3-container'>
					<h5 style='color:darkgrey'>Users</h5>
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
				<span id='EditOrder' class='fa fa-exchange fa-3x' onclick='loadPage(&#39;orders.php&#39;)'></span>
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
	  <div id='targetEditUser' style='min-width:450px' class='col-xs-12'>
  		<div class='alert alert-warning'>
		<i class='fa fa-times-circle fa-2x close' onclick='hideme()'></i><br>
  		<i style='float:left; font-size:30px' class='fa fa-user-plus' onclick='viewAddUser()'></i>&nbsp;
  		<i style='float:right; font-size:30px' class='fa fa-user-times' onclick='viewDeleteUser()'></i>
  		<h4 class='text-center page-header'>User Panel</h4>
  			<form autocomplete='off' id='updateUserInfo' class='text-center' method='post'>
  				<input autofocus='on' class='fixInput text-center' type='text' id='usern' name='usern' placeholder='Update username'><br><br>
  				<input class='fixInput text-center' type='password' id='passw' name='passw' placeholder='Update or Enter current password'><br><br>
  				<input type='submit' value='Update' class='btn btn-primary' onclick='submitUserUpdate()'>
  			</form>
  		</div>
	  </div>
	  <div id='targetDeleteUser' style='min-width:450px' class='col-xs-12'>
  		<div class='alert alert-warning'>
  		<i class='fa fa-arrow-circle-left fa-2x' stle='float:left' onclick='showEditUser()'></i>
		<i class='fa fa-times-circle fa-2x close' onclick='hideme7()'></i><br>
  		<h4 class='text-center page-header'>Delete Account: " . $_SESSION['user'] . "?</h4>
	  		<div class='text-center'>
		  		<button style='min-width:80px' type='button' class='btn btn-lg btn-primary fa fa-check fa-2x' onclick='deleteUser()'></button>
		  		<button style='min-width:80px' type='button' class='btn btn-lg btn-primary fa fa-times fa-2x' onclick='showEditUser()'></button>
	  		</div>
  		</div>
	  </div>
	   <div id='targetAddUser' style='min-width:450px' class='col-xs-12'>
  		<div class='alert alert-warning'>
  		<i class='fa fa-arrow-circle-left fa-2x' stle='float:left' onclick='showEditUser()'></i>
		<i class='fa fa-times-circle fa-2x close' onclick='hideme6()'></i><br>
  		<h4 class='text-center page-header'>New User</h4>
  			<form autocomplete='off' id='newUser' class='text-center' method='post'>
  				<input autofocus='on' class='text-center fixInput' type='text' id='newuser2' name='newuser' placeholder='Username'><br><br>
  				<input class='fixInput text-center' type='password' id='newpass2' name='newpassw' placeholder='Password'><br><br>
  				<input type='submit' name='newUser' value='Add' class='btn btn-primary' onclick='submitNewUser()'>
  			</form>
  		</div>
	  </div>";
	}

	//<i style='font-size:20px; transform:translate(10px,-30px)' class='fa fa-trash' title='Stock Reset' onclick='resetStock()'></i>
?>
