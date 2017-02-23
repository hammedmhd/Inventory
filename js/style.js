function loadPage(e){
	$('#userView').load(e, function(){
		if(e.match(/(stock.php)/g)){
			$('#boardView').fadeIn(300);
		}else if(e.match(/(orders.php)/g)){
			$('#boardView2').fadeIn(300);
		}	
	});
	if(e.match(/(home.php)/g)){
		$('#header').html('Inventory System');
	}else if(e.match(/(orders.php)/g)){
		$('#header').html('Orders');
	}else if(e.match(/(stock.php)/g)){
		$('#header').html('Stock Items');
	}
}

$(document).ready(function(){
	if(window.location.href.match(/(print.php)/g)){
		getSelectedList();
	}else{		
		//localStorage.removeItem('selected');   //to disable remembering of print view data enable this.
	}
	loadPage('home.php');
	var user = $('#header').html();
	$('.linkme').on('click', function(e){
	e.preventDefault();
	loadPage(e.target.href);
	if(e.target.href.match(/(home.php)/g)){
		$('#header').html(user);
	}else if(e.target.href.match(/(orders.php)/g)){
		$('#header').html('Orders');
	}else if(e.target.href.match(/(stock.php)/g)){
		$('#header').html('Stock Items');
	}
	$('.active').attr('class','');
	var parent = $(this).parent();
	parent.attr('class','active');
	});
	//if(localStorage.getItem('reopen') == 'true'){
	//	console.log($('#userView').find('id','targetStock'));
	//	localStorage.removeItem('reopen');
	//};
});

function viewUserEdit(){
	if($('#targetEditUser').is(':visible')){
		$('#targetEditUser').fadeOut(500);
	}else {
		if($('#targetOrder').is(':visible')){
			$('#targetOrder').fadeOut(1);
		};
		if($('#targetStock').is(':visible')){
			$('#targetStock').fadeOut(1);
		};
		if($('#targetAddUser').is(':visible')){
			$('#targetAddUser').fadeOut(1);
		};
		if($('#targetDeleteUser').is(':visible')){
			$('#targetDeleteUser').fadeOut(1);
		};
		if($('#boardView').is(':visible')){
			$('#boardView').fadeOut(1);
		};
		if($('#boardView2').is(':visible')){
			$('#boardView2').fadeOut(1);
		}
		$('#targetEditUser').fadeIn(500);
	}
}

function viewOrderEdit(){
	if($('#targetOrder').is(':visible')){
		$('#targetOrder').fadeOut(500);
	}else {
		if($('#targetEditUser').is(':visible')){
			$('#targetEditUser').fadeOut(1);
		};
		if($('#targetStock').is(':visible')){
			$('#targetStock').fadeOut(1);
		};
		if($('#targetAddUser').is(':visible')){
			$('#targetAddUser').fadeOut(1);
		}
		if($('#targetDeleteUser').is(':visible')){
			$('#targetDeleteUser').fadeOut(1);
		}
		if($('#boardView').is(':visible')){
			$('#boardView').fadeOut(1);
		};
		if($('#boardView2').is(':visible')){
			$('#boardView2').fadeOut(1);
		}
		$('#targetOrder').fadeIn(500);	
	}
}

function viewStockPanel(){
	if($('#targetStock').is(':visible')){
		$('#targetStock').fadeOut(500);
	}else {
		if($('#targetEditUser').is(':visible')){
			$('#targetEditUser').fadeOut(1);
		};
		if($('#targetOrder').is(':visible')){
			$('#targetOrder').fadeOut(1);	
		};
		if($('#targetAddUser').is(':visible')){
			$('#targetAddUser').fadeOut(1);
		}
		if($('#targetDeleteUser').is(':visible')){
			$('#targetDeleteUser').fadeOut(1);
		}
		if($('#boardView').is(':visible')){
			$('#boardView').fadeOut(1);
		};
		if($('#boardView2').is(':visible')){
			$('#boardView2').fadeOut(1);
		}
		$('#targetStock').fadeIn(500);	
	}
}

function viewAddUser(){
	if($('#targetAddUser').is(':visible')){
		$('#targetAddUser').fadeOut(1);
	}else{
		if($('#targetEditUser').is(':visible')){
			$('#targetEditUser').fadeOut(1);
		}
	 $('#targetAddUser').fadeIn(1);
	}
}

function viewDeleteUser(){
	if($('#targetDeleteUser').is(':visible')){
		$('#targetDeleteUser').fadeOut(1);
	}else{
		if($('#targetEditUser').is(':visible')){
			$('#targetEditUser').fadeOut(1);
		}
	 $('#targetDeleteUser').fadeIn(1);
	}
}

function showBoardConsole(){
	if($('#boardView').is(':visible')){
		$('#boardView').fadeOut(500);
	}else{
		if($('#targetEditUser').is(':visible')){
			$('#targetEditUser').fadeOut(1);
		}
		if($('#targetOrder').is(':visible')){
			$('#targetOrder').fadeOut(1);
		};
		if($('#targetStock').is(':visible')){
			$('#targetStock').fadeOut(1);
		};
		if($('#targetAddUser').is(':visible')){
			$('#targetAddUser').fadeOut(1);
		};
		if($('#targetDeleteUser').is(':visible')){
			$('#targetDeleteUser').fadeOut(1);
		};
		if($('#boardView2').is(':visible')){
			$('#boardView2').fadeOut(1);
		}
		$('#boardView').fadeIn(500);
	}
}

function showBoardConsole2(){
	if($('#boardView2').is(':visible')){
		$('#boardView2').fadeOut(500);
	}else{
		if($('#targetEditUser').is(':visible')){
			$('#targetEditUser').fadeOut(1);
		}
		if($('#targetOrder').is(':visible')){
			$('#targetOrder').fadeOut(1);
		};
		if($('#targetStock').is(':visible')){
			$('#targetStock').fadeOut(1);
		};
		if($('#targetAddUser').is(':visible')){
			$('#targetAddUser').fadeOut(1);
		};
		if($('#targetDeleteUser').is(':visible')){
			$('#targetDeleteUser').fadeOut(1);
		};
		if($('#boardView').is(':visible')){
			$('#boardView').fadeOut(1);
		};
		$('#boardView2').fadeIn(500);
	}
}

function showEditUser(){
	$('#targetAddUser').fadeOut(500);
	$('#targetDeleteUser').fadeOut(500);
	$('#targetEditUser').slideDown(500);
}

function viewCsvUpload(){
	if($('#csv_upload').is(':visible')){
		$('#csv_upload').slideUp(500);
	}else $('#csv_upload').slideDown(500);
}

function viewCsvUpload2(){
	if($('#csv_upload2').is(':visible')){
		$('#csv_upload2').slideUp(500);
	}else $('#csv_upload2').slideDown(500);
}

function submitUserUpdate(){
	$('#updateUserInfo').submit(function(e){
	e.preventDefault();
	$('#lstatus').html('Loading, please wait...');
	$('#lstatus').fadeIn(500);
	$.ajax({
		url: 'home.php',
		type: 'post',
		data: $('#updateUserInfo').serialize(),
		success: function(response){
				$('#usern').val('');
				$('#passw').val('');
				if(response.match(/(Duplicate)/g)) response = 'Username exists, please use another username.';
				$('#lstatus').fadeOut(500,function(){
					$('#lstatus').html(response);
					$('#lstatus').fadeIn(500);
				});
				setTimeout(function(){
				$('#lstatus').fadeOut(500,function(){
					location.assign('index.php?logout');
				});
				}, 4000);
		}
	});
	});
}

function deleteUser(){
	$('#lstatus').html('Loading, please wait...');
	$('#lstatus').fadeIn(500);
	$.ajax({
		url: 'home.php',
		type: 'post',
		data: {deleteUser: true},
		success: function(response){
			$('#lstatus').fadeOut(500,function(){
				$('#lstatus').html(response);
				$('#lstatus').fadeIn(500);
			});
			setTimeout(function(){
					$('#lstatus').fadeOut(500,function(){
						$('#targetDeleteUser').fadeOut();
						if(response.match(/(Logging)/g)){
							location.assign('index.php?logout');
						}
					});
					}, 4000);
		}
	});
}

function submitNewUser(){
	$('#newUser').submit(function(e){
	e.preventDefault();
	$('#lstatus').html('Loading, please wait...');
	$('#lstatus').fadeIn(500);
	$.ajax({
		url: 'home.php',
		type: 'post',
		data: $('#newUser').serialize(),
		success: function(response){
			if(response.match(/(duplicate)/ig)){
				response = 'Sorry, username already exists, please wait...';
			}
				$('#newuser2').val('');
				$('#newpass2').val('');
				$('#lstatus').fadeOut(500,function(){
					$('#lstatus').html(response);
					$('#lstatus').fadeIn(500);
				});
				setTimeout(function(){
					$('#lstatus').fadeOut(500,function(){
						location.assign('index.php');
					});
				}, 4000);
		}
	});
	});
}

function submitNewStock(){
	$('#newStock').submit(function(e){
	e.preventDefault();
	$('#lstatus').html('Loading, please wait...');
	$('#lstatus').fadeIn(500);
	$.ajax({
		url: 'stock.php',
		type: 'post',
		data: $('#newStock').serialize(),
		success: function(response){
			if(response.match(/(error)/g)){
				response = 'Sorry, failed to process request, please re-enter.';
			}
			$('#lstatus').fadeOut(500,function(){
				$('#lstatus').html(response);
				$('#lstatus').fadeIn(500);
			});
			setTimeout(function(){
				$('#lstatus').fadeOut(500,function(){
					$('#targetStock').fadeOut(500,function(){	
					location.assign('index.php');
					});
				});
			}, 4000);
		}
	});
	});
}

function submitStockCSV(){
	$('#csv_upload').submit(function(e){
	e.preventDefault();
	$('#lstatus').html('Loading, please wait...');
		$('#lstatus').fadeIn(500);
		var fd = new FormData();
		fd.append('file', $('#file')[0].files[0]);
		$.ajax({
			url: 'stock.php',
			type: 'post',
			processData: false,
			contentType: false,
			data: fd,
			success: function(response){
				if(response.match(/(refreshing)/g)){
					response = "Stock items updating accordingly and refreshing page, please wait...";
				}else response = 'Sorry, failed to process request, please re-upload csv file.';
			$('#lstatus').fadeOut(500,function(){
				$('#lstatus').html(response);
				$('#lstatus').fadeIn(500);
			});
			setTimeout(function(){
				$('#lstatus').fadeOut(500,function(){
					if(response.match(/(updating)/g)){
					location.assign('index.php');
					}
				});
			}, 4000);
			}
		});
	});
}

function submitOrderCSV(){
	$('#csv_upload2').submit(function(e){
	e.preventDefault();
	$('#lstatus').html('Loading, please wait...');
		$('#lstatus').fadeIn(500);
		var fd = new FormData();
		fd.append('file', $('#file')[0].files[0]);
		$.ajax({
			url: 'orders.php',
			type: 'post',
			processData: false,
			contentType: false,
			data: fd,
			success: function(response){
				if(response.match(/(refreshing)/g)){
					response = "Orders updating accordingly and refreshing page, please wait...";
				}else response = 'Sorry, failed to process request, please re-upload csv file.';
			$('#lstatus').fadeOut(500,function(){
				$('#lstatus').html(response);
				$('#lstatus').fadeIn(500);
			});
			setTimeout(function(){
				$('#lstatus').fadeOut(500,function(){
					if(response.match(/(updating)/g)){
					location.assign('index.php');
					}
				});
			}, 4000);
			}
		});
	});
}

function submitNewOrder(){
	$('#newOrder').submit(function(e){
	e.preventDefault();
	$('#lstatus').html('Loading, please wait...');
	$('#lstatus').fadeIn(500);
	$.ajax({
		url: 'orders.php',
		type: 'post',
		data: $('#newOrder').serialize(),
		success: function(response){
			if(response.match(/(error)/g)){
				response = 'Sorry, some fields entered are invalid, please re-enter.';
			}
			$('#lstatus').fadeOut(500,function(){
				$('#lstatus').html(response);
				$('#lstatus').fadeIn(500);
			});
			setTimeout(function(){
				$('#lstatus').fadeOut(500,function(){
					location.assign('index.php');
				});
			}, 4000);
		}
	});
	});
}

function searchDatabase(){
	$('#searchDatabase').submit(function(e){
	e.preventDefault();
	$('#lstatus').html('Loading, please wait...');
	$('#lstatus').fadeIn(500);
	$.ajax({
		url: 'stock.php',
		type: 'post',
		data: $('#searchDatabase').serialize(),
		success: function(response){
			$('#lstatus').fadeOut(500,function(){
				$('#boardConsole').html(response);
			});
		}
	});
	});
}


function searchDatabase2(){
	$('#searchDatabase').submit(function(e){
	e.preventDefault();
	$('#lstatus').html('Loading, please wait...');
	$('#lstatus').fadeIn(500);
	$.ajax({
		url: 'orders.php',
		type: 'post',
		data: $('#searchDatabase').serialize(),
		success: function(response){
			$('#lstatus').fadeOut(500,function(){
				$('#boardConsole').html(response);
			});
		}
	});
	});
}

function resetStock(){
	var q = confirm("You're about to reset the entire stock database to initial state, are you sure?");
	if(q == true){
		$('#lstatus').html('Loading, please wait...');
		$('#lstatus').fadeIn(500);
	$.get('stock.php', {resetStock: 1}, function(data){
		//if($('#boardView').is(':visible')){
			//$('#boardView').fadeOut(500);
		//}
		$('#lstatus').fadeOut(500,function(){
			if(data.match(/(refreshing)/g)){
				location.assign('index.php');
			}
		});
	});
	}
}

function resetOrder(){
	var q = confirm("You're about to reset the entire order database to initial state, are you sure?");
	if(q == true){
		$('#lstatus').html('Loading, please wait...');
		$('#lstatus').fadeIn(500);
	$.get('orders.php', {resetOrder: 1}, function(data){
		//if($('#boardView').is(':visible')){
			//$('#boardView').fadeOut(500);
		//}
		$('#lstatus').fadeOut(500,function(){
				$('#lstatus').html(data);
				$('#lstatus').fadeIn(500);
			});
	$('#lstatus').fadeOut(500,function(){
			if(data.match(/(refreshing)/g)){
				location.assign('index.php');
			}
		});
	});
	}
}

function updateStock(){
	$('#stockDatabase').submit(function(e){
	if(confirm('Updating Stock items, are you sure?') == true){
		e.preventDefault();
		$('#lstatus').html('Loading, please wait...');
		$('#lstatus').fadeIn(500);
		//alert($('#stockDatabase').serialize());
		$.ajax({
			url: 'stock.php',
			type: 'post',
			data: $('#stockDatabase').serialize(),
			success: function(response){
				$('#lstatus').fadeOut(500, function(){
					location.assign('index.php');
				});
			}
		});
	}else{
		e.preventDefault();
	}
	});
}

function updateOrders(){
	$('#orderDatabase').submit(function(e){
	if(confirm('Updating Orders, are you sure?') == true){
		e.preventDefault();
		$('#lstatus').html('Loading, please wait...');
		$('#lstatus').fadeIn(500);
		$.ajax({
			url: 'orders.php',
			type: 'post',
			data: $('#orderDatabase').serialize(),
			success: function(reponse){
				$('#lstatus').fadeOut(500, function(){
					location.assign('index.php');
				})
			}
		});
	}else{
		e.preventDefault();
	}
	});
}

function updateStockBadge(){
	$.get('stock.php', {stockBadge: 1}, function(data){
		$('#lstatus').fadeOut(500,function(){
			$('.blue').html(data);
		});
			
	});
}

function deleteStockItemRow(id){
if(confirm('Permenantely deleting selected Stock item, are you sure?') == true){
		$('#lstatus').html('Loading, please wait...');
		$('#lstatus').fadeIn(500);
		$.ajax({
			url: 'stock.php',
			type: 'post',
			data: {eraseItemID: id},
			success: function(response){
			$('#lstatus').fadeOut(500, function(){
				updateStockBadge();
				//location.assign('index.php');
				$('#boardConsole').html(response);
			});
			}
		});
	}
}


function updateOrdersBadeg(e){
	$.get('orders.php', {statusBadge: e}, function(data){
		$('#lstatus').fadeOut(500, function(){
			if(e == 0){
				$('.red').html(data);
			}else if(e == 1){
				$('.gold').html(data);
			}else if(e == 2){
				$('.green').html(data);
			}
		});
	});
}


function deleteOrdeRow(id){
if(confirm('Permenantely deleting selected Order entry, are you sure?') == true){
		$('#lstatus').html('Loading, please wait...');
		$('#lstatus').fadeIn(500);
	var value = id.getAttribute('status');
		$.ajax({
			url: 'orders.php',
			type: 'post',
			data: {eraseItemID: id.id},
			success: function(response){
			$('#lstatus').fadeOut(1000, function(){
				updateOrdersBadeg(value);
				//location.assign('index.php');
				$('#boardConsole').html(response);
			});
			}
		});
	}
}

function selectMe(e){
	var e = e.getAttribute('table');
	var c = document.getElementsByClassName('getcheckbox');
	list = [];
	list.push(e);
	for(var i = 0; i < c.length; i++){
		if(c[i].checked == true){
			list.push(c[i].id);
		}
	}
	localStorage.selected = list;
}

function directToPrint(){
	location.assign('print.php');
}

function emptyConsole(){
	$('#boardConsole').fadeOut(300, function(){
	$('#boardConsole').html('');
	$('#lstatus').fadeIn(500, function(){
		loadPage('stock.php');
		$('#lstatus').fadeOut(500);
	});
	});
}

function emptyConsole2(){
	$('#boardConsole').fadeOut(300, function(){
	$('#boardConsole').html('');
	$('#lstatus').fadeIn(500, function(){
		loadPage('orders.php');
		$('#lstatus').fadeOut(500);
	});
	});
}

function selectall(s){
	var checkSelectAll = document.getElementById('selectAll');
	var tickthis = document.getElementsByClassName('getcheckbox');
	if(checkSelectAll.checked == true){
		for(var i = 0; i < tickthis.length; i++){
			tickthis[i].checked = true;
		}
	}else{
		for(var i = 0; i < tickthis.length; i++){
			tickthis[i].checked = false;
		}
	}
	selectMe(s);
}

function getSelectedList(){
	var slist = localStorage.selected.split(',');
	if(slist){
	var from = slist[0];
	slist.shift();
	console.log(slist);
	console.log(from);
	$.ajax({
			url: 'print.php',
			type: 'post',
			data: {slist: slist, from: from},
			success: function(response){
				$('#printableData').html(response);
			}
	});
	}
}

function selectStatus(e){
	//$('#lstatus').html('Loading, please wait...');
	//$('#lstatus').fadeIn(200);
	var newStatus;
	var prevStatus;
	var finalColor;
	var action = 'no action',
		pending = 'pending',
		complete = 'complete';
		var options = $('.options');
		for(var i = 0; i < options.length; i++){
			if($('.options').eq(i).attr('id') == e.id){
				$('.options').eq(i).fadeIn();
			}
		}
	/*if(e.getAttribute('status') == 'color:red'){
		newStatus = 1;
		prevStatus = 0;
		finalColor = 'gold';
	}else if(e.getAttribute('status') == 'color:gold'){
		prevStatus = 1;
		if(prevStatus == 0){

		}
			newStatus = 2;
			finalColor = 'limegreen';
		}else{
			newStatus = 0;
			prevStatus = 1;
			count1 = 0;
			finalColor = 'red';
		}
	}else if(e.getAttribute('status') == 'color:limegreen'){
		newStatus = 1;
		prevStatus = 2;
		count1++;
		finalColor = 'gold';
	}
	console.log('current status: ' + prevStatus + ' new status: ' + newStatus + ' NEWColor: ' + finalColor);
	*/	
}

function jobDone(){ // setting all statuseUpdate to COMPLETE
	var options = document.getElementsByClassName('options');
	//var bpx = $('#allofem').prop('checked');
		for(var i = 0; i < options.length; i++){
			options[i].children[0].removeAttribute('selected');
			options[i].children[1].removeAttribute('selected');
			options[i].children[2].removeAttribute('selected');
			options[i].children[0].setAttribute('selected','selected');
		}
}

function jobDone1(){ // setting all statuseUpdate to COMPLETE
	var options = document.getElementsByClassName('options');
	//var bpx = $('#allofem').prop('checked');
		for(var i = 0; i < options.length; i++){
			options[i].children[0].removeAttribute('selected');
			options[i].children[1].removeAttribute('selected');
			options[i].children[2].removeAttribute('selected');
			options[i].children[1].setAttribute('selected','selected');
		}
}

function jobDone2(){ // setting all statuseUpdate to COMPLETE
	var options = document.getElementsByClassName('options');
	//var bpx = $('#allofem').prop('checked');
		for(var i = 0; i < options.length; i++){
			options[i].children[0].removeAttribute('selected');
			options[i].children[1].removeAttribute('selected');
			options[i].children[2].removeAttribute('selected');
			options[i].children[2].setAttribute('selected','selected');
		}
}

function stockByAsc(id){
	$('#lstatus').html('Loading, please wait...');
	$('#lstatus').fadeIn(500);
	$.post('stock.php', {asc: id}, function(data){
		$('#lstatus').fadeOut(500, function(){
			$('#boardConsole').html(data);
		});
	}
	);	
}

function stockByDesc(id){
	$('#lstatus').html('Loading, please wait...');
	$('#lstatus').fadeIn(500);
	$.post('stock.php', {desc: id}, function(data){
		$('#lstatus').fadeOut(500, function(){
			$('#boardConsole').html(data);
		});
	}
	);	
}

function ordersByAsc(id){
	$('#lstatus').html('Loading, please wait...');
	$('#lstatus').fadeIn(500);
	$.post('orders.php', {asc: id}, function(data){
		$('#lstatus').fadeOut(500, function(){
			$('#boardConsole').html(data);
		});
	}
	);
}	

function ordersByDesc(id){
	$('#lstatus').html('Loading, please wait...');
	$('#lstatus').fadeIn(500);
	$.post('orders.php', {desc: id}, function(data){
		$('#lstatus').fadeOut(500, function(){
			$('#boardConsole').html(data);
		});
	}
	);
}	

function hideme(){
	$('#targetEditUser').fadeOut(300);
}

function hideme2(){
	$('#targetOrder').fadeOut(300);
}

function hideme3(){
	$('#targetStock').fadeOut(300);
}

function hideme4(){
		$('#csv_upload').fadeOut(300);
}

function hideme5(){
		$('#csv_upload2').fadeOut(300);
}

function hideme6(){
	$('#targetAddUser').fadeOut(300);
}

function hideme7(){
	$('#targetDeleteUser').fadeOut(300);
}

function hideme8(){
	$('#boardView').fadeOut(300);
}

function hideme9(){
	$('#boardView2').fadeOut(300);
}