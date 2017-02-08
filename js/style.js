function ajaxRequest(){
        try
        {
          var request = new XMLHttpRequest()
        }
        catch(e1)
        {
          try
          {
            request = new ActiveXObject("Msxml2.XMLHTTP")
          }
          catch(e2)
          {
            try
            {
              request = new ActiveXObject("Microsoft.XMLHTTP")
            }
            catch(e3)
            {
              request = false
            }
          }
        }
        return request
}

function menuToggle(){
		//$('.list').slideToggle();
		if($('.list').is(':visible')){
			$('.navbar').animate({right: '0'});
			$('.list').animate({width: 'toggle'});
			$('.back').animate({right: '0'});
			$('#try').animate({right: '0'});
			$('#menu').css('color','rgba(255,255,255,0.8)');
		}else {
		$('.navbar').animate({right: '145px'});
		$('.list').animate({width: 'toggle'});
		$('#try').animate({right: '140px'});
		$('.back').animate({right: '140px'});
		$('#menu').css('color','rgba(71, 255, 221, 1)');
		}
}

window.onload = function(){
	var startUrl = 'home.php';
	loadPage(startUrl);
};
	
function progressBar(){
	var dest = document.getElementById('try');
	var para = document.getElementsByClassName('progress')[0];
	if(para == undefined){
	var table = document.createElement('TABLE');
	table.style.width = '100%';
	table.style.tableLayout = 'fixed';
	table.style.height = '50px'; 
	table.style.position = 'fixed';
	table.style.margin = '170px auto';
	table.className = 'progress';
	var th = document.createElement('TH');
	var span = document.createElement('SPAN');
	span.style.padding = '20px 30px 20px 40px';
	span.style.backgroundColor = 'rgba(0, 204, 167, 1)';
	span.style.color = 'rgba(255,255,255,0.8)';
	span.style.borderRadius = '10px';
	span.style.textShadow = '1px 1px 20px rgba(255,255,255,0.8)';
	span.style.boxShadow = '1px 1px 20px rgba(255,255,255,0.8)';
	span.innerHTML = 'Loading, please wait...';
	th.append(span);
	table.append(th);
	dest.prepend(table);
	}else {
		dest.removeChild(para);
	}
}

function loadthis(e){//main index page load functions onclick of button
	progressBar();
	$('#try').load(e.getAttribute('url'), function(){
		document.getElementById('header').innerHTML = e.getAttribute('name');
	});
}	
	
function loadPage(url){
	progressBar();
	$('#try').load(url);
	$('.list a').click(function(e){//sidebar links
		progressBar();
		menuToggle();
		var newtitle = e.target.text;
		if($('.list').is(':visible')){
			$('.list').slideUp();
		}
		$('#menu').css('color','skyblue');
		e.preventDefault();
		$('#try').load(e.target.href, function(){
			document.getElementById('header').innerHTML = newtitle;
		});
	});
	
	$('#Home a').click(function(e){
		progressBar();
		var t = e.currentTarget.href;
		var newtitle = 'Inventory System';
		if($('.list').is(':visible')){
			$('.list').slideUp();
		}
		e.preventDefault();
		//if(/[index.php]$/.test(t) == true){
		//}
		$('#try').load(t, function(){
			document.getElementById('header').innerHTML = 'Inventory System';
		});
	});	
}

function updateStock(){
	$('#updatestock').submit(function(e){
	if(confirm('Confirm Stock Update?') == true){
		progressBar();
		e.preventDefault();
		$.ajax({
			url: 'items.php',
			type: 'post',
			data: $('#updatestock').serialize(),
			success: function(){
					if($('.list').is(':visible')){
						$('.list').slideUp();
					}
						$('#try').load('items.php');
				$('#message').html('Updated Stock Successfully.');
				$('#message').fadeIn();
				setTimeout(function(){ $('#message').fadeOut(); }, 3000);
			}
		});
	} else{e.preventDefault();}
})
}

function addStockSubmit(){
	$('#addnewstock').submit(function(e){
		progressBar();
		e.preventDefault();
		$('#addS').slideUp();
		$.ajax({
			url: 'items.php',
			type: 'get',
			data: $('#addnewstock').serialize(),
			success: function(){
					if($('.list').is(':visible')){
						$('.list').slideUp();
					}
				$('#message').html('Appended New Stock Successfully');
				$('#try').load('items.php');
				$('#message').fadeIn();
				setTimeout(function(){ $('#message').fadeOut(); }, 3000);
			}
		});
})
}

function updateOrder(){
	$('#orderlist').submit(function(e){
	if(confirm('Confirm Orders Update?') == true){
		progressBar();
		e.preventDefault();
		$.ajax({
			url: 'orders.php',
			type: 'post',
			data: $('#orderlist').serialize(),
			success: function(){
					if($('.list').is(':visible')){
						$('.list').slideUp();
					}
					$('#message').html('Updated Orders Successfully.');
					$('#try').load('orders.php');
				$('#message').fadeIn();
				setTimeout(function(){ $('#message').fadeOut(); }, 3000);
			}
		});
	} else{e.preventDefault();}
})
}

function addOrderSubmit(){
	$('#addneworder').submit(function(e){
		progressBar();
		e.preventDefault();
		$('#addS2').slideUp();
		$.ajax({
			url: 'orders.php',
			type: 'get',
			data: $('#addneworder').serialize(),
			success: function(){
					if($('.list').is(':visible')){
						$('.list').slideUp();
					}
				$('#message').html('Appended New Order (if Exists) Successfully');
				$('#try').load('orders.php');
				$('#message').fadeIn();
				shippingfunction();
				setTimeout(function(){ $('#message').fadeOut(); }, 3000);
			}
		});
})
}

function uploadCSV(){
	$('#csvupload').submit(function(e){
		progressBar();
		e.preventDefault();
		var fd = new FormData();
		fd.append('file', $('#file')[0].files[0]);
		$.ajax({
			url: 'orders.php',
			type: 'post',
			processData: false,
			contentType: false,
			data: fd,
			success: function(){
					if($('.list').is(':visible')){
						$('.list').slideUp();
					}
				$('#message').html('Orders Added Successfully.');
					$('#try').load('orders.php');
				$('#message').fadeIn();
				setTimeout(function(){ $('#message').fadeOut(); }, 3000);
			}
		});
})
}

function uploadCSV2(){
	$('#csvupload2').submit(function(e){
		progressBar();
		e.preventDefault();
		var fd = new FormData();
		fd.append('file', $('#file')[0].files[0]);
		$.ajax({
			url: 'items.php',
			type: 'post',
			processData: false,
			contentType: false,
			data: fd,
			success: function(){
					if($('.list').is(':visible')){
						$('.list').slideUp();
					}
				$('#message').html('Stock Added Successfully.');
					$('#try').load('items.php');
				$('#message').fadeIn();
				setTimeout(function(){ $('#message').fadeOut(); }, 3000);
			}
		});
})
}

function addStock(){
	$('#addS').slideDown();
}

function closeMe(){
	$('#addS').slideUp();
}

function addOrder(){
	$('#addS2').slideDown();
}

function closeMe2(){
	$('#addS2').slideUp();
}

function promptDelete(del){
	var dela = del.match(/\d+/);
	if(confirm('Delete row specified?') == true){
		progressBar();
		$.ajax({
			url: 'orders.php',
			type: 'post',
			data: {del: dela},
			success: function(data){
				$('#try').load('orders.php');
					if($('.list').is(':visible')){
						$('.list').slideUp();
					}
				$('#message').html('Order Removed Successfully.');
				$('#message').fadeIn;
				setTimeout(function(){ $('#message').fadeOut(); }, 3000);
			}
		});
	}
}

function promptDelete2(del){
	var del = del.match(/\d+/);
	if(confirm('Delete row specified?') == true){
		progressBar();
		$.ajax({
			url: 'items.php',
			type: 'post',
			data: {del: del},
			success: function(){
				$('#try').load('items.php');
					if($('.list').is(':visible')){
						$('.list').slideUp();
					}
				$('#message').html('Stock Item Removed Successfully.');
				$('#message').fadeIn();
				setTimeout(function(){ $('#message').fadeOut(); }, 3000);
			}
		});
	}
}

function highlight(id){
//	document.getElementById(id).style.backgroundColor = 'rgba(0, 204, 167, 1)';	
}

/* to add onmouseover and out for &times; span to be inserted on hover of row.
function askDelete(e){
	var dest = document.getElementsByClassName(e)[0];
	var div = document.getElementsByClassName('delete')[0];
	if(div == undefined){
	var d = document.createElement('SPAN');
	d.className = 'delete';
	d.innerHTML = '&#10008;';
	dest.prepend(d);
	}else {
		dest.removeChild(div);
	}
}
*/

function displayUpload(){
	if($('#csvupload').is(':visible')){
	$('#csvupload').slideUp();
	$('#change').html('&plus;');
	}else{
	$('#csvupload').slideDown();
	$('#change').html('&minus;');
	}
}

function displayUpload2(){
	if($('#csvupload2').is(':visible')){
	$('#csvupload2').slideUp();
	$('#changes').html('&plus;');
	}else{
	$('#csvupload2').slideDown();
	$('#changes').html('&minus;');
	}
}

function statusUpdate(row){
	var id = row.name;
	var output = document.getElementById('output' + id);
	var chnge = document.getElementById('lab' + id);
	var statusUpdate;
	if(output.innerHTML == 'IN PROGRESS'){
		statusUpdate = 1;
		output.innerHTML = 'DELIVERED';
		output.style.color = 'limegreen';
		output.style.textShadow = '0 0 20px limegreen';
		chnge.style.backgroundColor = 'limegreen';
		} else {
		statusUpdate = 0;
		output.innerHTML = 'IN PROGRESS';
		output.style.color = 'rgba(209, 0, 0, 1)';
		output.style.textShadow = '0 0 20px rgba(255,255,255,0.8)';
		chnge.style.backgroundColor = 'red';
	}
		$.ajax({
			url: 'orders.php',
			type: 'post',
			data:  {statusUpdate: statusUpdate, id: id},
			success: function(){
					if($('.list').is(':visible')){
						$('.list').slideUp();
					}
				if(statusUpdate == 0){
					$('#message').html('Order yet to Ship..');
				}else if(statusUpdate == 1){
					$('#message').html('Order Delivered &#9787;');
				}
				$('#message').fadeIn();
				setTimeout(function(){ $('#message').fadeOut(); }, 3000);
			}
		});
}

function myAnim(place){
	var id = place[0];
	var turn = 0;
	var timer = setInterval(checker, 1);
	function checker(){
		if(turn == 90){
			clearInterval(timer);
		}else{
			turn++;
			id.style.transform = 'rotate(' + turn + 'deg)';
		}
	}
}

function myAnim2(place){
	var id = place[0];
	var turn = 90;
	var timer = setInterval(checker, 1);
	function checker(){
		if(turn == -90){
			clearInterval(timer);
		}else{
			turn--;
			id.style.transform = 'rotate(' + turn + 'deg)';
		}
	}
}

var count = 0;
var view;
function orderByMe(id){
	progressBar();
	if(count == 1){
		if(view == id){
			$.ajax({
			url: 'orders.php',
			type: 'post',
			data: {desc: id},
			success: function(data){
				if($('.list').is(':visible')){
					$('.list').slideUp();
				}
				$('#try').html(data);
				var ele = document.createElement('SPAN');
				ele.className = 'spinAgain';
				var selectem = document.getElementsByClassName('spinAgain');
				document.getElementById(id).appendChild(ele);
				selectem[0].innerHTML = '&nbsp;&#10151;';
				selectem[0].style.textShadow = '1px 1px 20px rgba(255,255,255,0.8)';
				selectem[0].style.color = 'rgba(255,255,255,0.8)';
				selectem[0].style.display = 'inline-block';
				selectem[0].style.transform = 'rotate(90deg)';
				myAnim2(selectem);
			}
			});
			count = 0;
		}else{ 
		count = 0;
		view = id;
		$.ajax({
			url: 'orders.php',
			type: 'post',
			data: {asc: id},
			success: function(data){
				if($('.list').is(':visible')){
					$('.list').slideUp();
				}
				$('#try').html(data);
				var ele = document.createElement('SPAN');
				ele.className = 'spinAgain';
				var selectem = document.getElementsByClassName('spinAgain');
				document.getElementById(id).appendChild(ele);
				selectem[0].innerHTML = '&nbsp;&#10151;';
				selectem[0].style.textShadow = '1px 1px 20px rgba(255,255,255,0.8)';
				selectem[0].style.color = 'rgba(255,255,255,0.8)';
				selectem[0].style.display = 'inline-block';
				myAnim(selectem);
			}
		});
		count++;	
		}
	}else{
	view = id;
	$.ajax({
		url: 'orders.php',
		type: 'post',
		data: {asc: id},
		success: function(data){
			if($('.list').is(':visible')){
				$('.list').slideUp();
			}
			$('#try').html(data);
			var ele = document.createElement('SPAN');
			ele.className = 'spinAgain';
			var selectem = document.getElementsByClassName('spinAgain');
			document.getElementById(id).appendChild(ele);
			selectem[0].innerHTML = '&nbsp;&#10151;';
			selectem[0].style.color = 'rgba(255,255,255,0.8)';
			selectem[0].style.textShadow = '1px 1px 20px rgba(255,255,255,0.8)';
			selectem[0].style.display = 'inline-block';
			myAnim(selectem);
		}
	});
	count++;
}
}

var secondcount = 0;
var viewstock;
function stockByMe(id){
	progressBar();
if(secondcount == 1){
		if(viewstock == id){
			$.ajax({
			url: 'items.php',
			type: 'post',
			data: {desc: id},
			success: function(data){
				if($('.list').is(':visible')){
					$('.list').slideUp();
				}
				$('#try').html(data);
				var ele = document.createElement('SPAN');
				ele.className = 'spinAgain';
				var selectem = document.getElementsByClassName('spinAgain');
				document.getElementById(id).appendChild(ele);
				selectem[0].innerHTML = '&nbsp;&#10151;';
				selectem[0].style.textShadow = '1px 1px 20px rgba(255,255,255,0.8)';
				selectem[0].style.color = 'rgba(255,255,255,0.8)';
				selectem[0].style.display = 'inline-block';
				selectem[0].style.transform = 'rotate(90deg)';
				myAnim2(selectem);
			}
			});
			secondcount = 0;
		}else{ 
		secondcount = 0;
		viewstock = id;
		$.ajax({
			url: 'items.php',
			type: 'post',
			data: {asc: id},
			success: function(data){
				if($('.list').is(':visible')){
					$('.list').slideUp();
				}
				$('#try').html(data);
				var ele = document.createElement('SPAN');
				ele.className = 'spinAgain';
				var selectem = document.getElementsByClassName('spinAgain');
				document.getElementById(id).appendChild(ele);
				selectem[0].innerHTML = '&nbsp;&#10151;';
				selectem[0].style.textShadow = '1px 1px 20px rgba(255,255,255,0.8)';
				selectem[0].style.color = 'rgba(255,255,255,0.8)';
				selectem[0].style.display = 'inline-block';
				myAnim(selectem);
			}
		});
		secondcount++;	
		}
	}else{
	viewstock = id;
	$.ajax({
		url: 'items.php',
		type: 'post',
		data: {asc: id},
		success: function(data){
			if($('.list').is(':visible')){
				$('.list').slideUp();
			}
			$('#try').html(data);
			var ele = document.createElement('SPAN');
			ele.className = 'spinAgain';
			var selectem = document.getElementsByClassName('spinAgain');
			document.getElementById(id).appendChild(ele);
			selectem[0].innerHTML = '&nbsp;&#10151;';
			selectem[0].style.color = 'rgba(255,255,255,0.8)';
			selectem[0].style.textShadow = '1px 1px 20px rgba(255,255,255,0.8)';
			selectem[0].style.display = 'inline-block';
			myAnim(selectem);
		}
	});
	secondcount++;
}	
}

function sendFiltered(){
	$('#searching').submit(function(e){
		progressBar();
		e.preventDefault();
		$.ajax({
			url: 'orders.php',
			type: 'POST',
			data: $('#searching').serialize(),
			success: function(data){
				$('#try').html(data);
				shippingfunction();
			}
		});
	});
}

function sendFiltered2(){
	$('#searching2').submit(function(e){
		progressBar();
		e.preventDefault();
		$.ajax({
			url: 'items.php',
			type: 'POST',
			data: $('#searching2').serialize(),
			success: function(data){
				$('#try').html(data);
			}
		});
	});
}


function hidesearch(){
	if($('#searching').is(':visible')){
	$('#searching').slideUp();
	$('#searchhide').html('&plus;');
	}else {
		$('#searching').slideDown();
		$('#searchhide').html('&minus;');
	}
	
	if($('#searching2').is(':visible')){
	$('#searching2').slideUp();
	$('#searchhide').html('&plus;');
	}else {
		$('#searching2').slideDown();
		$('#searchhide').html('&minus;');
	}
}

function displayMessage(){
	$('#message').html('Search for data required and hold down Ctrl button + P. (Landscape Recommended)');
	$('#message').fadeIn();
	setTimeout(function(){ $('#message').fadeOut(); }, 6000);
}
