window.onload=start;
var input = new Array();
var dial;
var registerPopupDialog;
var loginNewOrderDialog;
var itemPopupDialog;
var editItemPopupDialog;
var editOrderPopupDialog;

function start()
{
	/*
	var backgrounds = ['background1.jpg', 'background2.jpg', 'background3.jpg', 'background4.jpg', 'background5.jpg'];
	$('body').css({'background-image': 'url(../img/' + backgrounds[Math.floor(Math.random() * backgrounds.length)] + ')'});
	*/
}

function loadRegDiv()
{
	start();
	checkToEnable();
	$(document).ajaxComplete(function()
	{
		checkToEnable();
	});
}

function loadMenuDiv()
{
	alert(1);
	
	$('#mainleft').load('../php/' + page);
	
	$(document).ajaxComplete(function()
	{
		alert(2);
		$('.sorted_table').sortable({
		  containerSelector: 'table',
		  itemPath: '> tbody',
		  itemSelector: 'tr',
		  placeholder: '<tr class="placeholder"/>'
		});
	});
}

function loadMenuEdit()
{	
	/*
	$('#mainleft').load('../php/leftsorttest.php', function()
	{
	*/
		categorySortable();
		subcategorySortable();
		itemSortable();
		
		alert(3);
	/*
	});
	*/
}

function registerPage()
{
	$('#topbar').load('../php/registerpage.php #top');
	$('#mainbody').load('../php/registerpage.php #main', function()
	{
		checkToEnable();
	});
}

function restaurantRegisterPage()
{
	$('#topbar').load('../php/restaurantregisterpage.php #top');
	$('#mainbody').load('../php/restaurantregisterpage.php #main', function()
	{
		checkToEnable();
	});
}

function changeURLVariable(inputText)
{
	var newVariableArray = [['restaurant',''],['loc_id',''],['rest_id',''],['page','']];
	
	var currentHREF = location.href;
	
	var HREFArray = currentHREF.split('?');
	
	var currentPage = HREFArray[0];
	
	if(HREFArray[1])
	{
		var variableArray = HREFArray[1].split('&');
	}
	else
	{
		var variableArray = [''];
	}
	
	var inputVariableArray = inputText.split('&');
	
	for (var i = 0; i < newVariableArray.length; i++)
	{
		if(!newVariableArray[i][1])
		{
			for (var j = 0; j < inputVariableArray.length; j++)
			{
				jArray = inputVariableArray[j].split('=');
				if(newVariableArray[i][0]==jArray[0])
				{
					newVariableArray[i][1]=jArray[1];
				}
			}
		}
		
		if(!newVariableArray[i][1])
		{
			for (var k = 0; k < variableArray.length; k++)
			{
				kArray = variableArray[k].split('=');
				if(newVariableArray[i][0]==kArray[0])
				{
					newVariableArray[i][1]=kArray[1];
				}
			}
		}
	}
	
	var newVariables = '';
	
	for (var i = 0; i < newVariableArray.length; i++)
	{	
		if(newVariableArray[i][1]&&(newVariableArray[i][1]!='false'))
		{
			if(newVariables)
			{
				newVariables += "&" + newVariableArray[i][0] + "=" + newVariableArray[i][1];
			}
			else
			{
				newVariables = newVariableArray[i][0] + "=" + newVariableArray[i][1];
			}
		}
	}
	
	var newHREF = '';
	
	if(newVariables)
	{
		newHREF = currentPage + "?" + newVariables;
	}
	else
	{
		newHREF = currentPage;
	}
	
	history.pushState('', '', newHREF);
}

function checkToEnable()
{
	var usernamefilled = (document.getElementById("usernametest").className == "optional");
	var emailfilled = (document.getElementById("emailtest").className == "optional");
	var confirmemailfilled = (document.getElementById("confirmemailtest").className == "optional");
	var phonenumberfilled = (document.getElementById("phonenumbertest").className == "optional");
	var passwordfilled = (document.getElementById("passwordtest").className == "optional");
	var confirmpasswordfilled = (document.getElementById("confirmpasswordtest").className == "optional");
	
	if (document.contains(document.getElementById("restaurantnametest")))
	{
		var restaurantnamefilled = (document.getElementById("restaurantnametest").className == "optional");
	}
	else
	{
		var restaurantnamefilled = true;
	}
	
	if (document.contains(document.getElementById("addresstest")))
	{
		var addressfilled = (document.getElementById("addresstest").className == "optional");
	}
	else
	{
		var addressfilled = true;
	}
	
	if (document.contains(document.getElementById("citytest")))
	{
		var cityfilled = (document.getElementById("citytest").className == "optional");
	}
	else
	{
		var cityfilled = true;
	}
	
	if (document.contains(document.getElementById("statetest")))
	{
		var statefilled = (document.getElementById("statetest").className == "optional");
	}
	else
	{
		var statefilled = true;
	}
	
	if (document.contains(document.getElementById("zipcodetest")))
	{
		var zipcodefilled = (document.getElementById("zipcodetest").className == "optional");
	}
	else
	{
		var zipcodefilled = true;
	}
	
	if (document.contains(document.getElementById("deliverypickuptest")))
	{
		var deliverypickupfilled = (document.getElementById("deliverypickuptest").className == "optional");
	}
	else
	{
		var deliverypickupfilled = true;
	}
	
	if (document.contains(document.getElementById("termsofuse")))
	{
		var termsofusechecked = document.getElementById("termsofuse").checked;
	}
	else
	{
		var termsofusechecked = true;
	}
	
	if (document.contains(document.getElementById("currentpasswordtest")))
	{
		var currentpasswordfilled = (document.getElementById("currentpasswordtest").className == "optional");
	}
	else
	{
		var currentpasswordfilled = true;
	}
	
	if(usernamefilled && emailfilled && confirmemailfilled && phonenumberfilled && passwordfilled && confirmpasswordfilled && termsofusechecked && restaurantnamefilled && addressfilled && cityfilled && statefilled && zipcodefilled && deliverypickupfilled && currentpasswordfilled)
	{
		enableButton();
	}
	else
	{
		disableButton();
	}
}

function checkNewOrder()
{
	var emailfilled = (document.getElementById("emailtest").className == "optional");
	var phonenumberfilled = (document.getElementById("phonenumbertest").className == "optional");
	var firstnamefilled = (document.getElementById("firstnametest").className == "optional");
	var lastnamefilled = (document.getElementById("lastnametest").className == "optional");
	
	var deliveryorpickup = document.getElementById("newdelivery").value;
	
	if(deliveryorpickup == "delivery")
	{
		var addressfilled = (document.getElementById("addresstest").className == "optional");
		var cityfilled = (document.getElementById("citytest").className == "optional");
		var statefilled = (document.getElementById("statetest").className == "optional");
		var zipcodefilled = (document.getElementById("zipcodetest").className == "optional");
	}
	else
	{
		var addressfilled = true;
		var cityfilled = true;
		var statefilled = true;
		var zipcodefilled = true;
	}
	
	if(emailfilled && phonenumberfilled && firstnamefilled && lastnamefilled && addressfilled && cityfilled && statefilled && zipcodefilled)
	{
		enableButton();
	}
	else
	{
		disableButton();
	}
}

function disableButton()
{
	document.getElementById("registerbutton").disabled = true;
	document.getElementById("registerbutton").title = "Disabled";
}

function enableButton()
{
	document.getElementById("registerbutton").disabled = false;
	document.getElementById("registerbutton").title = "Register";
}

function getXMLhttp()
{
	var xmltemp;
	if (window.XMLHttpRequest)
	{// code for IE7+, Firefox, Chrome, Opera, Safari
		xmltemp = new XMLHttpRequest();
	}
	else
	{// code for IE6, IE5
		xmlteml = new ActiveXObject("Microsoft.XMLHTTP");
	}
	return xmltemp;
}

function login()
{
	var postdata = {};
	postdata['username'] = document.getElementById('username').value;
	postdata['password'] = document.getElementById('password').value;
	$.post('../php/login.php', postdata, function(responseTxt)
	{
    	// do something here with the returnedData
    	document.getElementById("topnavcontent").innerHTML=responseTxt;
    	var postdata2 = {};
		$.post('../php/sessionusername.php', postdata2, function(responseTxt)
		{
			u = responseTxt;
			$.post('../php/sessionsignin.php', postdata2, function(responseTxt)
			{
				s = responseTxt;
				$.cookie("u", u, { expires: 3, domain: '.gottanom.com'});
				$.cookie("s", s, { expires: 3, domain: '.gottanom.com'});
				location.reload();
			});
		});
	});
}

function loginwithoutreload(loc_id,rest_id,item_id)
{
	var postdata = {};
	postdata['username'] = document.getElementById('username').value;
	postdata['password'] = document.getElementById('password').value;
	//alert(postdata['username']);
	//alert(postdata['password']);
	$.post('../php/loginneworderpopup.php?loc_id=' + loc_id + '&rest_id=' + rest_id + '&item_id=' + item_id, postdata, function(responseTxt)
	{
    	// do something here with the returnedData
		document.getElementById("loginNewOrderDialogMsg").innerHTML=responseTxt;
    	
    	var postdata2 = {};
		$.post('../php/sessionusername.php', postdata2, function(responseTxt)
		{
			u = responseTxt;
			$.post('../php/sessionsignin.php', postdata2, function(responseTxt)
			{
				s = responseTxt;
				$.cookie("u", u, { expires: 3, domain: '.gottanom.com'});
				$.cookie("s", s, { expires: 3, domain: '.gottanom.com'});
			});
		});
	});
}

function loginneworder()
{
	var postdata = {};
	postdata['username'] = document.getElementById('username').value;
	postdata['password'] = document.getElementById('password').value;
	//alert(postdata['username']);
	//alert(postdata['password']);
	$.post('../php/loginneworderpopup.php?loc_id=' + loc_id + '&rest_id=' + rest_id + '&item_id=' + item_id, postdata, function(responseTxt)
	{
    	// do something here with the returnedData
		document.getElementById("loginNewOrderDialogMsg").innerHTML=responseTxt;
    	
    	var postdata2 = {};
		$.post('../php/sessionusername.php', postdata2, function(responseTxt)
		{
			u = responseTxt;
			$.post('../php/sessionsignin.php', postdata2, function(responseTxt)
			{
				s = responseTxt;
				$.cookie("u", u, { expires: 3, domain: '.gottanom.com'});
				$.cookie("s", s, { expires: 3, domain: '.gottanom.com'});
			});
		});
	});
}

function loginrestaurant()
{
	var postdata = {};
	postdata['username'] = document.getElementById('username').value;
	postdata['password'] = document.getElementById('password').value;
	$.post('../php/loginrestaurant.php', postdata, function(responseTxt)
	{
    	// do something here with the returnedData
    	document.getElementById("topnavcontent").innerHTML=responseTxt;
    	var postdata2 = {};
		$.post('../php/sessionusername.php', postdata2, function(responseTxt)
		{
			u = responseTxt;
			$.post('../php/sessionsignin.php', postdata2, function(responseTxt)
			{
				s = responseTxt;
				$.cookie("u", u, { expires: 3, domain: '.gottanom.com'});
				$.cookie("s", s, { expires: 3, domain: '.gottanom.com'});
				location.reload();
			});
		});
	});
}

function logout()
{
	$.cookie("u", null, { domain: '.gottanom.com'});
	$.cookie("s", null, { domain: '.gottanom.com'});
	var postdata = {};
	$.post('../php/logout.php', postdata, function(responseTxt)
	{
		location.href='http://gottanom.com/page.php';
	});
}

function cookielogin(i,s)
{
	var ns = makecode();
	var xmlhttp;
	xmlhttp = getXMLhttp();
	xmlhttp.open("POST","../php/login.php",true);
	xmlhttp.setRequestHeader("Content-type","application/x-www-form-urlencoded");
	xmlhttp.send("i="+i+"&s="+s+"&ns="+ns);
	xmlhttp.onreadystatechange=function()
	{
		if (xmlhttp.readyState==4 && xmlhttp.status==200)
		{
			document.getElementById("welcome").innerHTML=xmlhttp.responseText;
		}
	}
	$.cookie("i", i, { expires: 3, domain: '.gottanom.com'});
	$.cookie("s", ns, { expires: 3, domain: '.gottanom.com'});
}

function makecode()
{
    var code = "";
    var possible = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";

    for( var i=0; i < 32; i++ )
        code += possible.charAt(Math.floor(Math.random() * possible.length));

    return code;
}

function popupLogin()
{
	var postdata = {};
	$.post('../php/logindiv.php', postdata, function(responseTxt)
	{
    	// do something here with the returnedData
		loginPopupDialog = $('<div id="dialogDiv" title="Login"><span id="dialogMsg">'+responseTxt+'</span></div>');
	
		loginPopupDialog.dialog({
			modal: true,
			draggable: false,
			resizable: false,
			width: 620
		});
	
		loginPopupDialog.dialog("open");
	});
}

function popupLoginNewOrder()
{
	var postdata = {};
	$.post('../php/loginneworderdiv.php', postdata, function(responseTxt)
	{
    	// do something here with the returnedData
		loginNewOrderDialog = $('<div id="dialogDiv" title="Login"><span id="loginNewOrderDialogMsg">'+responseTxt+'</span></div>');
	
		loginNewOrderDialog.dialog({
			modal: true,
			draggable: false,
			resizable: false,
			width: 620
		});
	
		loginNewOrderDialog.dialog("open");
	});
}

function popupItem(loc_id,rest_id,item_id,name)
{
	var postdata = {};
	$.post('../php/itempopup.php?loc_id=' + loc_id + '&rest_id=' + rest_id + '&item_id=' + item_id, postdata, function(itemPopupResponseTxt)
	{
    	// do something here with the returnedData
		itemPopupDialog = $('<div id="dialogDiv" title="'+name+'"><span id="itemPopupDialogMsg">'+itemPopupResponseTxt+'</span></div>');
	
		itemPopupDialog.dialog({
			modal: true,
			draggable: false,
			resizable: false,
			width: 640
		});
	
		itemPopupDialog.dialog("open");
	});
}

function popupEditItem(loc_id,rest_id,item_id,name)
{
	var postdata = {};
	$.post('../php/edititempopup.php?loc_id=' + loc_id + '&rest_id=' + rest_id + '&item_id=' + item_id, postdata, function(editItemPopupResponseTxt)
	{
    	// do something here with the returnedData
		editItemPopupDialog = $('<div id="dialogDiv" title="'+name+'"><span id="itemPopupDialogMsg">'+editItemPopupResponseTxt+'</span></div>');
	
		editItemPopupDialog.dialog({
			modal: true,
			draggable: false,
			resizable: false,
			width: 640
		});
	
		editItemPopupDialog.dialog("open");
	});
}

function popupEditOrder()
{
	var postdata = {};
	$.post('../php/editorderpopup.php', postdata, function(editOrderResponseTxt)
	{
    	// do something here with the returnedData
		if(!editOrderPopupDialog)
		{
			editOrderPopupDialog = $('<div id="dialogDiv" title="Edit Order"><span id="itemPopupDialogMsg">'+editOrderResponseTxt+'</span></div>');

			editOrderPopupDialog.dialog({
				modal: true,
				draggable: false,
				resizable: false,
				width: 640
			});
		}
	
		editOrderPopupDialog.dialog("open");
	});
}

function editOrder()
{
	var postdata = {};
	postdata['newdelivery'] = document.getElementById('newdelivery').value;
	postdata['newemail'] = document.getElementById('newemail').value;
	postdata['newphonenumber'] = document.getElementById('newphonenumber').value;
	postdata['newfirstname'] = document.getElementById('newfirstname').value;
	postdata['newlastname'] = document.getElementById('newlastname').value;
	postdata['newaddress'] = document.getElementById('newaddress').value;
	postdata['newcity'] = document.getElementById('newcity').value;
	postdata['newstate'] = document.getElementById('newstate').value;
	postdata['newzipcode'] = document.getElementById('newzipcode').value;
	$.post('../php/editorder.php', {'postdata': postdata}, function(editOrderResponseTxt)
	{
		//alert(editOrderResponseTxt);
		
		$('#mainleft').load('../php/leftorderpage.php');
				
		editOrderPopupDialog.dialog("close");
	});
}

function deleteItem(num)
{
	var postdata = {};
	postdata['num'] = num;
	//alert(num);
	
	$.post('../php/deleteitem.php', postdata, function(deleteItemResponseTxt)
	{
		//alert(deleteItemResponseTxt);
		
		$('#mainleft').load('../php/leftorderpage.php');
	});
}

function addItemToOrder(loc_id,rest_id,item_id)
{
	//alert();
	//alert(loc_id+","+rest_id+","+item_id);
	
	input = new Array();
	var i = 0;

	$("div#dialogDiv :input").each(function(){
		if($(this).attr('type')=="radio")
		{
			input[i] = $(this).is(':checked'); 
		}
		else
		{
			input[i] = $(this).val(); // This is the jquery object of the input, do what you will
		}
		i++;
	});

	//dial.dialog("close");
	//dial.dialog("destroy");
	
	var postdata = {};
	$.post('../php/checkneworder.php', postdata, function(checkNewOrderResponseTxt)
	{
		//alert('checkneworder');
		
		if(checkNewOrderResponseTxt=="New Order")
		{
			var postdata = {};
			$.post('../php/sessionusername.php', postdata, function(username)
			{
				if(!username)
				{
					var postdata = {};
					$.post('../php/loginneworderdiv.php?loc_id=' + loc_id + '&rest_id=' + rest_id + '&item_id=' + item_id, postdata, function(responseTxt)
					{
						// do something here with the returnedData
						loginNewOrderDialog = $('<div id="dialogDiv" title="Login"><span id="loginNewOrderDialogMsg">'+responseTxt+'</span></div>');
					
						loginNewOrderDialog.dialog({
							modal: true,
							draggable: false,
							resizable: false,
							width: 620
						});
					
						loginNewOrderDialog.dialog("open");
					});
				}
				else
				{
					startNewOrderPopup(loc_id, rest_id, item_id);
				}
			});
		}
		else if(checkNewOrderResponseTxt=="Existing Order")
		{
			//alert('existing order');
			
			$.post('../php/additemtoorder.php?loc_id=' + loc_id + '&rest_id=' + rest_id + '&item_id=' + item_id, {'input': input}, function(addItemToOrderResponseTxt)
			{
				//alert(addItemToOrderResponseTxt);
				
				$('#mainleft').load('../php/leftorderpage.php');
				
				itemPopupDialog.dialog("close");
			});
		}
	});
}

function startNewOrderPopup(loc_id,rest_id,item_id)
{
	$.post('../php/startneworderpopup.php?loc_id=' + loc_id + '&rest_id=' + rest_id + '&item_id=' + item_id, {'input': input}, function(startOrderResponseTxt)
	{
		// do something here with the returnedData
		var startNewOrderDialog = $('<div id="dialogDiv" title="New Order"><span id="dialogMsg">'+startOrderResponseTxt+'</span></div>');

		startNewOrderDialog.dialog({
			modal: true,
			draggable: false,
			resizable: false,
			width: 640
		});

		startNewOrderDialog.dialog("open");
		
		
	});
}

function startNewOrder(loc_id,rest_id,item_id)
{
	var postdata = {};
	postdata['newdelivery'] = document.getElementById('newdelivery').value;
	postdata['newemail'] = document.getElementById('newemail').value;
	postdata['newphonenumber'] = document.getElementById('newphonenumber').value;
	postdata['newfirstname'] = document.getElementById('newfirstname').value;
	postdata['newlastname'] = document.getElementById('newlastname').value;
	postdata['newaddress'] = document.getElementById('newaddress').value;
	postdata['newcity'] = document.getElementById('newcity').value;
	postdata['newstate'] = document.getElementById('newstate').value;
	postdata['newzipcode'] = document.getElementById('newzipcode').value;
	$.post('../php/startneworder.php?loc_id=' + loc_id + '&rest_id=' + rest_id + '&item_id=' + item_id, {'input': input, 'postdata': postdata}, function(startOrderResponseTxt)
	{
		// do something here with the returnedData
		//alert(startOrderResponseTxt);
		location.reload();
	});
}

function accountSettings()
{
	var xmlhttp;
	xmlhttp = getXMLhttp();
	var i = $.cookie("i");
	var s = $.cookie("s");
	xmlhttp.open("POST","../php/accountsettings.php",true);
	xmlhttp.setRequestHeader("Content-type","application/x-www-form-urlencoded");
	xmlhttp.send("i="+e+"&s="+p);
	xmlhttp.onreadystatechange=function()
	{
		if (xmlhttp.readyState==4 && xmlhttp.status==200)
		{
			document.getElementById("main").innerHTML=xmlhttp.responseText;
		}
	}
	$.cookie("i", i, { expires: 3, domain: '.gottanom.com'});
	$.cookie("s", s, { expires: 3, domain: '.gottanom.com'});
}



function register()
{
	var postdata = {};
	postdata['newusername'] = document.getElementById('newusername').value;
	postdata['newemail'] = document.getElementById('newemail').value;
	postdata['confirmemail'] = document.getElementById('confirmemail').value;
	postdata['newphonenumber'] = document.getElementById('newphonenumber').value;
	postdata['newfirstname'] = document.getElementById('newfirstname').value;
	postdata['newlastname'] = document.getElementById('newlastname').value;
	postdata['newpassword'] = document.getElementById('newpassword').value;
	postdata['confirmpassword'] = document.getElementById('confirmpassword').value;
	postdata['termsofusechecked'] = document.getElementById("termsofuse").checked;
	$.post('../php/register.php', postdata, function(responseTxt)
	{
    	// do something here with the returnedData
    	document.getElementById("registermessage").innerHTML=responseTxt;
    	document.getElementById("registermessage").className="alert";
	});
}

function registerPopup()
{
	
	var postdata = {};
	$.post('../php/registerpopup.php', postdata, function(responseTxt)
	{
    	// do something here with the returnedData
    	if(!registerPopupDialog)
    	{
    		registerPopupDialog = $('<div id="dialogDiv" title="Enter your information below to register for GottaNom.com!"><span id="dialogMsg">'+responseTxt+'</span></div>');
		
			registerPopupDialog.dialog({
				modal: true,
				draggable: false,
				resizable: false,
				width: 640
			});
		}
	
		//$('#dialogMsg').text("Are you sure you want to delete this category? Any subcategories or items inside it will be deleted as well.");
		registerPopupDialog.dialog("open");
		
		checkToEnable();
	});
}

function forgotPasswordPopup()
{
	var postdata = {};
	$.post('../php/forgotpassworddiv.php', postdata, function(responseTxt)
	{
    	var passwordPopupDialog = $('<div id="dialogDiv" title="Forgot Password!"><span id="dialogMsg">'+responseTxt+'</span></div>');
		
		passwordPopupDialog.dialog({
			modal: true,
			draggable: false,
			resizable: false,
			width: 640
		});
	
		//$('#dialogMsg').text("Are you sure you want to delete this category? Any subcategories or items inside it will be deleted as well.");
		passwordPopupDialog.dialog("open");
    	
    	// do something here with the returnedData
    	/*
    	document.getElementById("registermessage").innerHTML=responseTxt;
    	document.getElementById("registermessage").className="alert";
    	*/
	});
}

function forgotPassword()
{
	var postdata = {};
	postdata['username'] = document.getElementById('usernamebox').value;
	postdata['email'] = document.getElementById('emailbox').value;
	$.post('../php/forgotpassword.php', postdata, function(responseTxt)
	{
    	var passwordDialog = $('<div id="dialogDiv" title="Forgot Password Alert!"><span id="dialogMsg">'+responseTxt+'</span></div>');
		
		passwordDialog.dialog({
			modal: true,
			draggable: false,
			resizable: false,
			buttons: [ { text: "Thanks", click: function() { $( this ).dialog( "close" ); } } ]
		});
	
		//$('#dialogMsg').text("Are you sure you want to delete this category? Any subcategories or items inside it will be deleted as well.");
		passwordDialog.dialog("open");
    	
    	// do something here with the returnedData
    	/*
    	document.getElementById("registermessage").innerHTML=responseTxt;
    	document.getElementById("registermessage").className="alert";
    	*/
	});
}

function forgotPasswordRestaurantPopup()
{
	var postdata = {};
	$.post('../php/forgotpasswordrestaurantdiv.php', postdata, function(responseTxt)
	{
    	var passwordPopupRestaurantDialog = $('<div id="dialogDiv" title="Forgot Password!"><span id="dialogMsg">'+responseTxt+'</span></div>');
		
		passwordPopupRestaurantDialog.dialog({
			modal: true,
			draggable: false,
			resizable: false,
			width: 640
		});
	
		//$('#dialogMsg').text("Are you sure you want to delete this category? Any subcategories or items inside it will be deleted as well.");
		passwordPopupRestaurantDialog.dialog("open");
    	
    	// do something here with the returnedData
    	/*
    	document.getElementById("registermessage").innerHTML=responseTxt;
    	document.getElementById("registermessage").className="alert";
    	*/
	});
}

function forgotPasswordRestaurant()
{
	var postdata = {};
	postdata['username'] = document.getElementById('usernamebox').value;
	postdata['email'] = document.getElementById('emailbox').value;
	$.post('../php/forgotpasswordrestaurant.php', postdata, function(responseTxt)
	{
    	var passwordRestaurantDialog = $('<div id="dialogDiv" title="Forgot Password Alert!"><span id="dialogMsg">'+responseTxt+'</span></div>');
		
		passwordRestaurantDialog.dialog({
			modal: true,
			draggable: false,
			resizable: false,
			buttons: [ { text: "Thanks", click: function() { $( this ).dialog( "close" ); } } ]
		});
	
		//$('#dialogMsg').text("Are you sure you want to delete this category? Any subcategories or items inside it will be deleted as well.");
		passwordRestaurantDialog.dialog("open");
    	
    	// do something here with the returnedData
    	/*
    	document.getElementById("registermessage").innerHTML=responseTxt;
    	document.getElementById("registermessage").className="alert";
    	*/
	});
}

function registerRestaurant()
{
	var postdata = {};
	postdata['newusername'] = document.getElementById('newusername').value;
	postdata['newrestaurantname'] = document.getElementById('newrestaurantname').value;
	postdata['newemail'] = document.getElementById('newemail').value;
	postdata['confirmemail'] = document.getElementById('confirmemail').value;
	postdata['newphonenumber'] = document.getElementById('newphonenumber').value;
	postdata['newfirstname'] = document.getElementById('newfirstname').value;
	postdata['newlastname'] = document.getElementById('newlastname').value;
	postdata['newaddress'] = document.getElementById('newaddress').value;
	postdata['newcity'] = document.getElementById('newcity').value;
	postdata['newstate'] = document.getElementById('newstate').value;
	postdata['newzipcode'] = document.getElementById('newzipcode').value;
	postdata['newpassword'] = document.getElementById('newpassword').value;
	postdata['confirmpassword'] = document.getElementById('confirmpassword').value;
	postdata['wedeliverchecked'] = document.getElementById("wedeliver").checked;
	postdata['weallowpickupchecked'] = document.getElementById("weallowpickup").checked;
	postdata['termsofusechecked'] = document.getElementById("termsofuse").checked;
	$.post('../php/registerrestaurant.php', postdata, function(responseTxt)
	{
    	var registerRestaurantDialog = $('<div id="dialogDiv" title="Registration Alert!"><span id="dialogMsg">'+responseTxt+'</span></div>');
		
		registerRestaurantDialog.dialog({
			modal: true,
			draggable: false,
			resizable: false,
			buttons: [ { text: "Thanks", click: function() { $( this ).dialog( "close" ); } } ]
		});
	
		//$('#dialogMsg').text("Are you sure you want to delete this category? Any subcategories or items inside it will be deleted as well.");
		registerRestaurantDialog.dialog("open");
    	
    	// do something here with the returnedData
    	/*
    	document.getElementById("registermessage").innerHTML=responseTxt;
    	document.getElementById("registermessage").className="alert";
    	*/
	});
}

function registerUser()
{
	var postdata = {};
	postdata['newusername'] = document.getElementById('newusername').value;
	postdata['newemail'] = document.getElementById('newemail').value;
	postdata['confirmemail'] = document.getElementById('confirmemail').value;
	postdata['newphonenumber'] = document.getElementById('newphonenumber').value;
	postdata['newfirstname'] = document.getElementById('newfirstname').value;
	postdata['newlastname'] = document.getElementById('newlastname').value;
	postdata['newpassword'] = document.getElementById('newpassword').value;
	postdata['confirmpassword'] = document.getElementById('confirmpassword').value;
	postdata['termsofusechecked'] = document.getElementById("termsofuse").checked;
	$.post('../php/registeruser.php', postdata, function(responseTxt)
	{
		var registerDialog = $('<div id="dialogDiv" title="Registration Alert!"><span id="dialogMsg">'+responseTxt+'</span></div>');
		
		registerDialog.dialog({
			modal: true,
			draggable: false,
			resizable: false,
			buttons: [ { text: "Thanks", click: function() { $( this ).dialog( "close" ); } } ]
		});
	
		//$('#dialogMsg').text("Are you sure you want to delete this category? Any subcategories or items inside it will be deleted as well.");
		registerDialog.dialog("open");
    	
    	
    	// do something here with the returnedData
    	/*
    	document.getElementById("registermessage").innerHTML=responseTxt;
    	document.getElementById("registermessage").className="alert";
    	*/
	});
}

function setMain(pageaddress)
{	
	$('#topbar').load('../php/' + pageaddress + ' #top');
	$('#mainbody').load('../php/' + pageaddress + ' #main');
}

function setSide(pageaddress)
{
	$('#mainleft').load('../php/' + pageaddress);
}

function setTopLogin(pageaddress)
{
	var xmlhttp;
	xmlhttp = getXMLhttp();
	xmlhttp.open("POST","../php/" + pageaddress,true);
	xmlhttp.setRequestHeader("Content-type","application/x-www-form-urlencoded");
	xmlhttp.send();
	xmlhttp.onreadystatechange=function()
	{
		if (xmlhttp.readyState==4 && xmlhttp.status==200)
		{
			document.getElementById("toplogin").innerHTML=xmlhttp.responseText;
		}
	}
}

function switchToCustomer()
{
	$.cookie("restaurant", null, { domain: '.gottanom.com'});
	changeURLVariable("restaurant=false");
	location.reload();
}

function switchToRestaurant()
{
	$.cookie("restaurant", 1, { expires: 3*365, domain: '.gottanom.com'});
	changeURLVariable("restaurant=true");
	location.reload();
}

function testAddress()
{
	var id = "addresstest";
	var addresstext = document.getElementById('newaddress').value;
	
	if (addresstext.length == 0)
	{
		document.getElementById(id).innerHTML = "Street Address";
		document.getElementById(id).className = "test";
		disableButton();
	}
	else if (validateAddress(addresstext))
	{
		document.getElementById(id).innerHTML = "Alright";
		document.getElementById(id).className = "optional";
		checkToEnable();
	}
	else
	{
		document.getElementById(id).innerHTML = "Invalid address";
		document.getElementById(id).className = "test";
		disableButton();
	}
}

function testAddressNewOrder()
{
	var id = "addresstest";
	var addresstext = document.getElementById('newaddress').value;
	
	if (addresstext.length == 0)
	{
		document.getElementById(id).innerHTML = "Street Address";
		document.getElementById(id).className = "test";
		checkNewOrder();
	}
	else if (validateAddress(addresstext))
	{
		document.getElementById(id).innerHTML = "Alright";
		document.getElementById(id).className = "optional";
		checkNewOrder();
	}
	else
	{
		document.getElementById(id).innerHTML = "Invalid address";
		document.getElementById(id).className = "test";
		checkNewOrder();
	}
	
	var deliveryorpickup = document.getElementById("newdelivery").value;
	
	if(deliveryorpickup == "pickup")
	{
		document.getElementById(id).className = "optional";
	}
}

function testAddressUpdate()
{
	var id = "addresstest";
	var addresstext = document.getElementById('newaddress').value;
	
	if (addresstext.length == 0)
	{
		document.getElementById(id).innerHTML = "Street Address";
		document.getElementById(id).className = "optional";
		disableButton();
	}
	else if (validateAddress(addresstext))
	{
		document.getElementById(id).innerHTML = "Alright";
		document.getElementById(id).className = "optional";
		checkToEnable();
	}
	else
	{
		document.getElementById(id).innerHTML = "Invalid address";
		document.getElementById(id).className = "test";
		disableButton();
	}
}

function testConfirmEmail()
{
	var id = "confirmemailtest";
	var emailtext = document.getElementById('newemail').value;
	var confirmemailtext = document.getElementById('confirmemail').value;
	
	if (confirmemailtext.length == 0)
	{
		document.getElementById(id).innerHTML = "Confirm Email";
		document.getElementById(id).className = "test";
		disableButton();
	}
	else if (confirmemailtext == emailtext)
	{
		document.getElementById(id).innerHTML = "Emails match";
		document.getElementById(id).className = "optional";
		checkToEnable();
	}
	else
	{
		document.getElementById(id).innerHTML = "Emails must match";
		document.getElementById(id).className = "test";
		disableButton();
	}
}

function testConfirmEmailUpdate()
{
	var id = "confirmemailtest";
	var emailtext = document.getElementById('newemail').value;
	var confirmemailtext = document.getElementById('confirmemail').value;
	
	if (confirmemailtext != emailtext)
	{
		document.getElementById(id).innerHTML = "Confirm Email";
		document.getElementById(id).className = "test";
		disableButton();
	}
	else
	{
		document.getElementById(id).innerHTML = "Emails match";
		document.getElementById(id).className = "optional";
		checkToEnable();
	}
}

function testConfirmPassword()
{
	var id = "confirmpasswordtest";
	var passwordtext = document.getElementById('newpassword').value;
	var confirmpasswordtext = document.getElementById('confirmpassword').value;
	
	if (confirmpasswordtext.length == 0)
	{
		document.getElementById(id).innerHTML = "Confirm Password";
		document.getElementById(id).className = "test";
		disableButton();
	}
	else if (confirmpasswordtext == passwordtext)
	{
		document.getElementById(id).innerHTML = "Passwords match";
		document.getElementById(id).className = "optional";
		checkToEnable();
	}
	else
	{
		document.getElementById(id).innerHTML = "Must match";
		document.getElementById(id).className = "test";
		disableButton();
	}
}

function testConfirmPasswordUpdate()
{
	var id = "confirmpasswordtest";
	var passwordtext = document.getElementById('newpassword').value;
	var confirmpasswordtext = document.getElementById('confirmpassword').value;
	
	if (confirmpasswordtext != passwordtext)
	{
		document.getElementById(id).innerHTML = "Confirm Password";
		document.getElementById(id).className = "test";
		disableButton();
	}
	else
	{
		document.getElementById(id).innerHTML = "Passwords match";
		document.getElementById(id).className = "optional";
		checkToEnable();
	}
}

function testCity()
{
	var id = "citytest";
	var citytext = document.getElementById('newcity').value;
	
	if (citytext.length == 0)
	{
		document.getElementById(id).innerHTML = "City";
		document.getElementById(id).className = "test";
		disableButton();
	}
	else if (validateCity(citytext))
	{
		document.getElementById(id).innerHTML = "Good";
		document.getElementById(id).className = "optional";
		checkToEnable();
	}
	else
	{
		document.getElementById(id).innerHTML = "Invalid city";
		document.getElementById(id).className = "test";
		disableButton();
	}
}

function testCityNewOrder()
{
	var id = "citytest";
	var citytext = document.getElementById('newcity').value;
	
	if (citytext.length == 0)
	{
		document.getElementById(id).innerHTML = "City";
		document.getElementById(id).className = "test";
		checkNewOrder();
	}
	else if (validateCity(citytext))
	{
		document.getElementById(id).innerHTML = "Good";
		document.getElementById(id).className = "optional";
		checkNewOrder();
	}
	else
	{
		document.getElementById(id).innerHTML = "Invalid city";
		document.getElementById(id).className = "test";
		checkNewOrder();
	}
	
	var deliveryorpickup = document.getElementById("newdelivery").value;
	
	if(deliveryorpickup == "pickup")
	{
		document.getElementById(id).className = "optional";
	}
}

function testCityUpdate()
{
	var id = "citytest";
	var citytext = document.getElementById('newcity').value;
	
	if (citytext.length == 0)
	{
		document.getElementById(id).innerHTML = "City";
		document.getElementById(id).className = "optional";
		checkToEnable();
	}
	else if (validateCity(citytext))
	{
		document.getElementById(id).innerHTML = "Good";
		document.getElementById(id).className = "optional";
		checkToEnable();
	}
	else
	{
		document.getElementById(id).innerHTML = "Invalid city";
		document.getElementById(id).className = "test";
		disableButton();
	}
}

function testDeliveryPickup()
{
	var id = "deliverypickuptest";
	var wedeliver = document.getElementById("wedeliver").checked;
	var weallowpickup = document.getElementById("weallowpickup").checked;
	
	if (wedeliver && weallowpickup)
	{
		document.getElementById(id).innerHTML = "Even Better!";
		document.getElementById(id).className = "optional";
		checkToEnable();
	}
	else if (wedeliver || weallowpickup)
	{
		document.getElementById(id).innerHTML = "Nice!";
		document.getElementById(id).className = "optional";
		checkToEnable();
	}
	else
	{
		document.getElementById(id).innerHTML = "Choose At Least One";
		document.getElementById(id).className = "test";
		disableButton();
	}
}

function testEmail()
{
	var id = "emailtest";
	var emailtext = document.getElementById('newemail').value;
	
	if (emailtext.length == 0)
	{
		document.getElementById(id).innerHTML = "Email Address";
		document.getElementById(id).className = "test";
		disableButton();
	}
	else if (validateEmail(emailtext))
	{
		document.getElementById(id).innerHTML = "Valid email";
		document.getElementById(id).className = "optional";
		checkToEnable();
	}
	else
	{
		document.getElementById(id).innerHTML = "Invalid email";
		document.getElementById(id).className = "test";
		disableButton();
	}
	
	testConfirmEmail();
}

function testEmailNewOrder()
{
	var id = "emailtest";
	var emailtext = document.getElementById('newemail').value;
	
	if (emailtext.length == 0)
	{
		document.getElementById(id).innerHTML = "Email Address";
		document.getElementById(id).className = "test";
		checkNewOrder();
	}
	else if (validateEmail(emailtext))
	{
		document.getElementById(id).innerHTML = "Valid email";
		document.getElementById(id).className = "optional";
		checkNewOrder();
	}
	else
	{
		document.getElementById(id).innerHTML = "Invalid email";
		document.getElementById(id).className = "test";
		checkNewOrder();
	}
}

function testEmailUpdate()
{
	var id = "emailtest";
	var emailtext = document.getElementById('newemail').value;
	
	if (emailtext.length == 0)
	{
		document.getElementById(id).innerHTML = "Email Address";
		document.getElementById(id).className = "optional";
		checkToEnable();
	}
	else if (validateEmail(emailtext))
	{
		document.getElementById(id).innerHTML = "Valid email";
		document.getElementById(id).className = "optional";
		checkToEnable();
	}
	else
	{
		document.getElementById(id).innerHTML = "Invalid email";
		document.getElementById(id).className = "test";
		disableButton();
	}
	
	testConfirmEmail();
}

function testFirstName()
{
	var id = "firstnametest";
	var firstnametext = document.getElementById('newfirstname').value;
	
	if (firstnametext.length >= 1)
	{
		document.getElementById(id).innerHTML = "Nice name";
		document.getElementById(id).className = "optional";
	}
	else
	{
		document.getElementById(id).innerHTML = "First Name";
		document.getElementById(id).className = "test";
	}
}

function testFirstNameNewOrder()
{
	var id = "firstnametest";
	var firstnametext = document.getElementById('newfirstname').value;
	
	if (firstnametext.length >= 1)
	{
		document.getElementById(id).innerHTML = "Nice name";
		document.getElementById(id).className = "optional";
	}
	else
	{
		document.getElementById(id).innerHTML = "First Name";
		document.getElementById(id).className = "test";
	}
	checkNewOrder();
}

function testFirstNameUpdate()
{
	var id = "firstnametest";
	var firstnametext = document.getElementById('newfirstname').value;
	
	if (firstnametext.length >= 1)
	{
		document.getElementById(id).innerHTML = "Nice name";
		document.getElementById(id).className = "optional";
	}
	else
	{
		document.getElementById(id).innerHTML = "First Name";
		document.getElementById(id).className = "optional";
	}
}

function testLastName()
{
	var id = "lastnametest";
	var lastnametext = document.getElementById('newlastname').value;
	
	if (lastnametext.length >= 1)
	{
		document.getElementById(id).innerHTML = "Sweet name";
		document.getElementById(id).className = "optional";
	}
	else
	{
		document.getElementById(id).innerHTML = "Last Name";
		document.getElementById(id).className = "test";
	}
}

function testLastNameNewOrder()
{
	var id = "lastnametest";
	var lastnametext = document.getElementById('newlastname').value;
	
	if (lastnametext.length >= 1)
	{
		document.getElementById(id).innerHTML = "Sweet name";
		document.getElementById(id).className = "optional";
	}
	else
	{
		document.getElementById(id).innerHTML = "Last Name";
		document.getElementById(id).className = "test";
	}
	checkNewOrder();
}

function testLastNameUpdate()
{
	var id = "lastnametest";
	var lastnametext = document.getElementById('newlastname').value;
	
	if (lastnametext.length >= 1)
	{
		document.getElementById(id).innerHTML = "Sweet name";
		document.getElementById(id).className = "optional";
	}
	else
	{
		document.getElementById(id).innerHTML = "Last Name";
		document.getElementById(id).className = "optional";
	}
}

function testNewOrder()
{
	var deliveryorpickup = document.getElementById("newdelivery").value;
	
	testEmailNewOrder();
	testPhoneNumberNewOrder();
	testFirstName();
	testLastName();
	
	if(deliveryorpickup == "delivery")
	{
		testAddressNewOrder();
		testCityNewOrder();
		testStateNewOrder();
		testZipCodeNewOrder();
	}
	
	checkNewOrder();
}

function testOwnerFirstName()
{
	var id = "firstnametest";
	var firstnametext = document.getElementById('newfirstname').value;
	
	if (firstnametext.length >= 1)
	{
		document.getElementById(id).innerHTML = "Nice name";
		document.getElementById(id).className = "optional";
	}
	else
	{
		document.getElementById(id).innerHTML = "Owner's First Name";
		document.getElementById(id).className = "test";
	}
}

function testOwnerFirstNameUpdate()
{
	var id = "firstnametest";
	var firstnametext = document.getElementById('newfirstname').value;
	
	if (firstnametext.length >= 1)
	{
		document.getElementById(id).innerHTML = "Nice name";
		document.getElementById(id).className = "optional";
	}
	else
	{
		document.getElementById(id).innerHTML = "Owner's First Name";
		document.getElementById(id).className = "optional";
	}
}

function testOwnerLastName()
{
	var id = "lastnametest";
	var lastnametext = document.getElementById('newlastname').value;
	
	if (lastnametext.length >= 1)
	{
		document.getElementById(id).innerHTML = "Sweet name";
		document.getElementById(id).className = "optional";
	}
	else
	{
		document.getElementById(id).innerHTML = "Owner's Last Name";
		document.getElementById(id).className = "test";
	}
}

function testOwnerLastNameUpdate()
{
	var id = "lastnametest";
	var lastnametext = document.getElementById('newlastname').value;
	
	if (lastnametext.length >= 1)
	{
		document.getElementById(id).innerHTML = "Sweet name";
		document.getElementById(id).className = "optional";
	}
	else
	{
		document.getElementById(id).innerHTML = "Owner's Last Name";
		document.getElementById(id).className = "optional";
	}
}

function testPassword()
{
	var id = "passwordtest";
	var passwordtext = document.getElementById('newpassword').value;
	
	if (passwordtext.length == 0)
	{
		document.getElementById(id).innerHTML = "Password";
		document.getElementById(id).className = "test";
		disableButton();
	}
	else if (passwordtext.length >= 6)
	{
		document.getElementById(id).innerHTML = "Good password";
		document.getElementById(id).className = "optional";
		checkToEnable();
	}
	else
	{
		document.getElementById(id).innerHTML = "Must be 6 characters";
		document.getElementById(id).className = "test";
		disableButton();
	}
	
	if (passwordtext.length > 0)
	{
		validatePassword(passwordtext);
	}
	
	testConfirmPassword();
}

function testPasswordCurrent()
{
	var id = "currentpasswordtest";
	var passwordtext = document.getElementById('currentpassword').value;
	
	if (passwordtext.length == 0)
	{
		document.getElementById(id).innerHTML = "Current Password";
		document.getElementById(id).className = "test";
		disableButton();
	}
	else if (passwordtext.length >= 6)
	{
		document.getElementById(id).innerHTML = "Let's Check";
		document.getElementById(id).className = "optional";
		checkToEnable();
	}
	else
	{
		document.getElementById(id).innerHTML = "Current Password";
		document.getElementById(id).className = "test";
		disableButton();
	}
	
	if (passwordtext.length > 0)
	{
		validatePassword(passwordtext);
	}
}

function testPasswordUpdate()
{
	var id = "passwordtest";
	var passwordtext = document.getElementById('newpassword').value;
	
	if (passwordtext.length == 0)
	{
		document.getElementById(id).innerHTML = "Password";
		document.getElementById(id).className = "optional";
		checkToEnable();
	}
	else if (passwordtext.length >= 6)
	{
		document.getElementById(id).innerHTML = "Good password";
		document.getElementById(id).className = "optional";
		checkToEnable();
	}
	else
	{
		document.getElementById(id).innerHTML = "Must be 6 characters";
		document.getElementById(id).className = "test";
		disableButton();
	}
	
	if (passwordtext.length > 0)
	{
		validatePassword(passwordtext);
	}
	
	testConfirmPasswordUpdate();
}

function testPhoneNumber()
{
	var id = "phonenumbertest";
	var phonenumbertext = document.getElementById('newphonenumber').value;
	var phonenumberlength = phonenumbertext.length;
	
	switch(phonenumberlength)
	{
		case 0:
			document.getElementById(id).innerHTML = "Phone Number";
			document.getElementById(id).className = "test";
			disableButton();
			break;
		/*
		case 3:
			document.getElementById('newphonenumber').value += "-";
			document.getElementById(id).innerHTML = "Invalid phone number";
			document.getElementById(id).className = "test";
			disableButton();
			break;
		case 7:
			document.getElementById('newphonenumber').value += "-";
			document.getElementById(id).innerHTML = "Invalid phone number";
			document.getElementById(id).className = "test";
			disableButton();
			break;
		*/
		default:
			validatePhoneNumber(phonenumbertext);
			break;
	}
}

function testPhoneNumberNewOrder()
{
	var id = "phonenumbertest";
	var phonenumbertext = document.getElementById('newphonenumber').value;
	var phonenumberlength = phonenumbertext.length;
	
	switch(phonenumberlength)
	{
		case 0:
			document.getElementById(id).innerHTML = "Phone Number";
			document.getElementById(id).className = "test";
			checkNewOrder();
			break;
		default:
			validatePhoneNumberNewOrder(phonenumbertext);
			break;
	}
}

function testPhoneNumberUpdate()
{
	var id = "phonenumbertest";
	var phonenumbertext = document.getElementById('newphonenumber').value;
	var phonenumberlength = phonenumbertext.length;
	
	switch(phonenumberlength)
	{
		case 0:
			document.getElementById(id).innerHTML = "Phone Number";
			document.getElementById(id).className = "optional";
			checkToEnable();
			break;
		/*
		case 3:
			document.getElementById('newphonenumber').value += "-";
			document.getElementById(id).innerHTML = "Invalid phone number";
			document.getElementById(id).className = "test";
			disableButton();
			break;
		case 7:
			document.getElementById('newphonenumber').value += "-";
			document.getElementById(id).innerHTML = "Invalid phone number";
			document.getElementById(id).className = "test";
			disableButton();
			break;
		*/
		default:
			validatePhoneNumber(phonenumbertext);
			break;
	}
}

function testRestaurantName()
{
	var id = "restaurantnametest";
	var firstnametext = document.getElementById('newrestaurantname').value;
	
	if (firstnametext.length >= 1)
	{
		document.getElementById(id).innerHTML = "Good!";
		document.getElementById(id).className = "optional";
		checkToEnable();
	}
	else
	{
		document.getElementById(id).innerHTML = "Restaurant Name";
		document.getElementById(id).className = "test";
		disableButton();
	}
}

function testRestaurantNameUpdate()
{
	var id = "restaurantnametest";
	var firstnametext = document.getElementById('newrestaurantname').value;
	
	if (firstnametext.length >= 1)
	{
		document.getElementById(id).innerHTML = "Good!";
		document.getElementById(id).className = "optional";
		checkToEnable();
	}
	else
	{
		document.getElementById(id).innerHTML = "Restaurant Name";
		document.getElementById(id).className = "optional";
		checkToEnable();
	}
}

function testState()
{
	var id = "statetest";
	var statetext = document.getElementById('newstate').value;
	
	if (validateState(statetext))
	{
		document.getElementById(id).innerHTML = "Well done";
		document.getElementById(id).className = "optional";
		checkToEnable();
	}
	else
	{
		document.getElementById(id).innerHTML = "State";
		document.getElementById(id).className = "test";
		disableButton();
	}
}

function testStateNewOrder()
{
	var id = "statetest";
	var statetext = document.getElementById('newstate').value;
	
	if (validateState(statetext))
	{
		document.getElementById(id).innerHTML = "Well done";
		document.getElementById(id).className = "optional";
		checkNewOrder();
	}
	else
	{
		document.getElementById(id).innerHTML = "State";
		document.getElementById(id).className = "test";
		checkNewOrder();
	}
	
	var deliveryorpickup = document.getElementById("newdelivery").value;
	
	if(deliveryorpickup == "pickup")
	{
		document.getElementById(id).className = "optional";
	}
}

function testStateUpdate()
{
	var id = "statetest";
	var statetext = document.getElementById('newstate').value;
	
	if (statetext.length == 0)
	{
		document.getElementById(id).innerHTML = "State";
		document.getElementById(id).className = "optional";
		checkToEnable();
	}
	else if (validateState(statetext))
	{
		document.getElementById(id).innerHTML = "Well done";
		document.getElementById(id).className = "optional";
		checkToEnable();
	}
	else
	{
		document.getElementById(id).innerHTML = "State";
		document.getElementById(id).className = "test";
		disableButton();
	}
}

function testTermsOfUse()
{
	var id = "termsofusetest";
	var termsofusechecked = document.getElementById("termsofuse").checked;
	
	if (termsofusechecked)
	{
		document.getElementById(id).className = "optional";
		checkToEnable();
	}
	else
	{
		document.getElementById(id).className = "test";
		disableButton();
	}
}

function testUsername()
{
	var id = "usernametest";
	var usernametext = document.getElementById('newusername').value;
	
	if (usernametext.length == 0)
	{
		document.getElementById(id).innerHTML = "Username";
		document.getElementById(id).className = "test";
		disableButton();
	}
	else if (usernametext.length >= 6)
	{
		document.getElementById(id).innerHTML = "Valid username";
		document.getElementById(id).className = "optional";
		checkToEnable();
	}
	else
	{
		document.getElementById(id).innerHTML = "Must be 6 characters";
		document.getElementById(id).className = "test";
		disableButton();
	}
	
	if (usernametext.length > 0)
	{
		validateUsername(usernametext);
	}
}

function testUsernameUpdate()
{
	var id = "usernametest";
	var usernametext = document.getElementById('newusername').value;
	
	if (usernametext.length == 0)
	{
		document.getElementById(id).innerHTML = "Username";
		document.getElementById(id).className = "optional";
		checkToEnable();
	}
	else if (usernametext.length >= 6)
	{
		document.getElementById(id).innerHTML = "Valid username";
		document.getElementById(id).className = "optional";
		checkToEnable();
	}
	else
	{
		document.getElementById(id).innerHTML = "Must be 6 characters";
		document.getElementById(id).className = "test";
		disableButton();
	}
	
	if (usernametext.length > 0)
	{
		validateUsername(usernametext);
	}
}

function testZipCode()
{
	var id = "zipcodetest";
	var zipcodetext = document.getElementById('newzipcode').value;
	
	validateZipCode(zipcodetext);
}

function testZipCodeNewOrder()
{
	var id = "zipcodetest";
	var zipcodetext = document.getElementById('newzipcode').value;
	
	validateZipCodeNewOrder(zipcodetext);
}

function testZipCodeUpdate()
{
	var id = "zipcodetest";
	var zipcodetext = document.getElementById('newzipcode').value;
	
	if(zipcodetext.length == 0)
	{
		document.getElementById(id).innerHTML = "Zip Code";
		document.getElementById(id).className = "optional";
		checkToEnable();
	}
	else
	{
		validateZipCode(zipcodetext);
	}
}

function updateInfo()
{
	var postdata = {};
	postdata['newusername'] = document.getElementById('newusername').value;
	postdata['newemail'] = document.getElementById('newemail').value;
	postdata['confirmemail'] = document.getElementById('confirmemail').value;
	postdata['newphonenumber'] = document.getElementById('newphonenumber').value;
	postdata['newfirstname'] = document.getElementById('newfirstname').value;
	postdata['newlastname'] = document.getElementById('newlastname').value;
	postdata['newaddress'] = document.getElementById('newaddress').value;
	postdata['newcity'] = document.getElementById('newcity').value;
	postdata['newstate'] = document.getElementById('newstate').value;
	postdata['newzipcode'] = document.getElementById('newzipcode').value;
	postdata['newpassword'] = document.getElementById('newpassword').value;
	postdata['confirmpassword'] = document.getElementById('confirmpassword').value;
	postdata['currentpassword'] = document.getElementById('currentpassword').value;
	$.post('../php/updateinfo.php', postdata, function(responseTxt)
	{
    	var UpdateInfoDialog = $('<div id="dialogDiv" title="Info Update Alert!"><span id="dialogMsg">'+responseTxt+'</span></div>');
		
		UpdateInfoDialog.dialog({
			modal: true,
			draggable: false,
			resizable: false,
			buttons: [ { text: "Thanks", click: function() { $( this ).dialog( "close" ); } } ]
		});
	
		//$('#dialogMsg').text("Are you sure you want to delete this category? Any subcategories or items inside it will be deleted as well.");
		UpdateInfoDialog.dialog("open");
    	
    	// do something here with the returnedData
    	/*
    	document.getElementById("registermessage").innerHTML=responseTxt;
    	document.getElementById("registermessage").className="alert";
    	*/
	});
}

function updateRestaurantInfo()
{
	var postdata = {};
	postdata['newusername'] = document.getElementById('newusername').value;
	postdata['newrestaurantname'] = document.getElementById('newrestaurantname').value;
	postdata['newemail'] = document.getElementById('newemail').value;
	postdata['confirmemail'] = document.getElementById('confirmemail').value;
	postdata['newphonenumber'] = document.getElementById('newphonenumber').value;
	postdata['newfirstname'] = document.getElementById('newfirstname').value;
	postdata['newlastname'] = document.getElementById('newlastname').value;
	postdata['newaddress'] = document.getElementById('newaddress').value;
	postdata['newcity'] = document.getElementById('newcity').value;
	postdata['newstate'] = document.getElementById('newstate').value;
	postdata['newzipcode'] = document.getElementById('newzipcode').value;
	postdata['newpassword'] = document.getElementById('newpassword').value;
	postdata['confirmpassword'] = document.getElementById('confirmpassword').value;
	postdata['wedeliverchecked'] = document.getElementById("wedeliver").checked;
	postdata['weallowpickupchecked'] = document.getElementById("weallowpickup").checked;
	postdata['hours'] = getHoursVariable();
	postdata['currentpassword'] = document.getElementById('currentpassword').value;
	$.post('../php/updaterestaurantinfo.php', postdata, function(responseTxt)
	{
    	var UpdateInfoRestaurantDialog = $('<div id="dialogDiv" title="Info Update Alert!"><span id="dialogMsg">'+responseTxt+'</span></div>');
		
		UpdateInfoRestaurantDialog.dialog({
			modal: true,
			draggable: false,
			resizable: false,
			buttons: [ { text: "Thanks", click: function() { $( this ).dialog( "close" ); } } ]
		});
	
		//$('#dialogMsg').text("Are you sure you want to delete this category? Any subcategories or items inside it will be deleted as well.");
		UpdateInfoRestaurantDialog.dialog("open");
    	
    	// do something here with the returnedData
    	/*
    	document.getElementById("registermessage").innerHTML=responseTxt;
    	document.getElementById("registermessage").className="alert";
    	*/
	});
}


function getHoursVariable()
{
	var hours = [[document.getElementById('mondayopen1').value,document.getElementById('mondayclose1').value,document.getElementById('mondayopen2').value,document.getElementById('mondayclose2').value],
				 [document.getElementById('tuesdayopen1').value,document.getElementById('tuesdayclose1').value,document.getElementById('tuesdayopen2').value,document.getElementById('tuesdayclose2').value],
				 [document.getElementById('wednesdayopen1').value,document.getElementById('wednesdayclose1').value,document.getElementById('wednesdayopen2').value,document.getElementById('wednesdayclose2').value],
				 [document.getElementById('thursdayopen1').value,document.getElementById('thursdayclose1').value,document.getElementById('thursdayopen2').value,document.getElementById('thursdayclose2').value],
				 [document.getElementById('fridayopen1').value,document.getElementById('fridayclose1').value,document.getElementById('fridayopen2').value,document.getElementById('fridayclose2').value],
				 [document.getElementById('saturdayopen1').value,document.getElementById('saturdayclose1').value,document.getElementById('saturdayopen2').value,document.getElementById('saturdayclose2').value],
				 [document.getElementById('sundayopen1').value,document.getElementById('sundayclose1').value,document.getElementById('sundayopen2').value,document.getElementById('sundayclose2').value]];
				 
	return hours;
}


function validateAddress(address)
{ 
    var ra = /[0-9]+ [0-9a-z-.' ]+/gi;
    return ra.test(address);
}

function validateCity(city)
{ 
    var rc = /[0-9a-z][0-9a-z-.' ]+/gi;
    return rc.test(city);
}

function validateEmail(email)
{ 
    var re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    return re.test(email);
} 

function validatePassword(password)
{ 
    var id = "passwordtest";
    var rp = /[a-zA-Z0-9!@#$%^&]$/;
    
    if (!rp.test(password))
    {
    	document.getElementById(id).innerHTML = "No crazy characters";
		document.getElementById(id).className = "test";
		disableButton();
    }
} 

function validatePhoneNumber(phonenumber)
{ 
	var id = "phonenumbertest";
    var phonematch = /^[2-9]\d{2}[2-9]\d{2}\d{4}$/;
	var digits = phonenumber.replace(/\D/g, "");
	
    if (digits.match(phonematch) !== null)
    {
    	document.getElementById(id).innerHTML = "Good phone number";
		document.getElementById(id).className = "optional";
		var numbertext = digits.slice(0,3)+"-"+digits.slice(3,6)+"-"+digits.slice(6,10);
		document.getElementById('newphonenumber').value = numbertext;
		checkToEnable();
    }
    else
    {
    	document.getElementById(id).innerHTML = "Invalid phone number";
		document.getElementById(id).className = "test";
		disableButton();
    }
}

function validatePhoneNumberNewOrder(phonenumber)
{
	var id = "phonenumbertest";
    var phonematch = /^[2-9]\d{2}[2-9]\d{2}\d{4}$/;
	var digits = phonenumber.replace(/\D/g, "");
	
    if (digits.match(phonematch) !== null)
    {
    	document.getElementById(id).innerHTML = "Good number";
		document.getElementById(id).className = "optional";
		var numbertext = digits.slice(0,3)+"-"+digits.slice(3,6)+"-"+digits.slice(6,10);
		document.getElementById('newphonenumber').value = numbertext;
		checkNewOrder();
    }
    else
    {
    	document.getElementById(id).innerHTML = "Invalid phone number";
		document.getElementById(id).className = "test";
		checkNewOrder();
    }
}

function validateState(state)
{
    var rs = /^[A-Z][A-Z]/;
    return rs.test(state);
}

function validateUsername(username)
{ 
    var id = "usernametest";
    var ru = /^[0-9a-zA-Z]+$/;
    
    if (!ru.test(username))
    {
    	document.getElementById(id).innerHTML = "Must be alphanumeric";
		document.getElementById(id).className = "test";
		disableButton();
    }
}

function validateZipCode(zipcode)
{ 
    var id = "zipcodetest";
    var zipcodematch = /^\d{5}$/;
	
    if (zipcode.match(zipcodematch) !== null)
    {
    	document.getElementById(id).innerHTML = "Good zip code";
		document.getElementById(id).className = "optional";
		checkToEnable();
    }
    else
    {
    	document.getElementById(id).innerHTML = "Zip Code";
		document.getElementById(id).className = "test";
		disableButton();
    }
}

function validateZipCodeNewOrder(zipcode)
{ 
    var id = "zipcodetest";
    var zipcodematch = /^\d{5}$/;
	
    if (zipcode.match(zipcodematch) !== null)
    {
    	document.getElementById(id).innerHTML = "Good zip code";
		document.getElementById(id).className = "optional";
		checkNewOrder();
    }
    else
    {
    	document.getElementById(id).innerHTML = "Zip Code";
		document.getElementById(id).className = "test";
		checkNewOrder();
    }
	
	var deliveryorpickup = document.getElementById("newdelivery").value;
	
	if(deliveryorpickup == "pickup")
	{
		document.getElementById(id).className = "optional";
	}
}

function deleteitemmessage(element)
{
    alert('deleteitem');
    new Messi('Are you sure you want to delete this item?', {title: 'Delete?', buttons: [{id: 0, label: 'Yes', val: 'Y'}, {id: 1, label: 'No', val: 'N'}], callback: function(val) { alert(12); alert('Your selection: ' + val); }});
    //element.parentNode.parentNode.parentNode.removeChild(element.parentNode.parentNode);
}

function deletethis(li,ol)
{
	ol.removeChild(li);
}

function deletecategory(li,ol)
{
	var deleteCategoryDialog = $('<div id="dialogDiv" title="Delete Category?"><span id="dialogMsg">Are you sure you want to delete this category? Any subcategories or items inside it will be deleted as well.</span></div>');
	
	deleteCategoryDialog.dialog({
		modal: true,
		draggable: false,
		resizable: false,
		buttons: [ { text: "Yes", click: function() { deletethis(li,ol); $( this ).dialog( "close" ); } }, { text: "No", click: function() { $( this ).dialog( "close" ); } } ]
	});
	
	//$('#dialogMsg').text("Are you sure you want to delete this category? Any subcategories or items inside it will be deleted as well.");
	deleteCategoryDialog.dialog("open");
}

function deletesubcategory(li,ol)
{
	
	var deleteSubcategoryDialog = $('<div id="dialogDiv" title="Delete Subcategory?"><span id="dialogMsg">Are you sure you want to delete this subcategory? Any items inside it will be deleted as well.</span></div>');
	
	deleteSubcategoryDialog.dialog({
		modal: true,
		draggable: false,
		resizable: false,
		buttons: [ { text: "Yes", click: function() { deletethis(li,ol); $( this ).dialog( "close" ); } }, { text: "No", click: function() { $( this ).dialog( "close" ); } } ]
	});
	
	//$('#dialogMsg').text("Are you sure you want to delete this subcategory? Any items inside it will be deleted as well.");
	deleteSubcategoryDialog.dialog("open");
}

function deleteitem(li,ol)
{
	
	var deleteItemDialog = $('<div id="dialogDiv" title="Delete Item?"><span id="dialogMsg">Are you sure you want to delete this item?</span></div>');
	
	deleteItemDialog.dialog({
		modal: true,
		draggable: false,
		resizable: false,
		buttons: [ { text: "Yes", click: function() { deletethis(li,ol); $( this ).dialog( "close" ); } }, { text: "No", click: function() { $( this ).dialog( "close" ); } } ]
	});
	
	//$('#dialogMsg').text("Are you sure you want to delete this item?");
	deleteItemDialog.dialog("open");
}

function saveMenu()
{
	//serializeHTML = String($("ol.category").sortable('serialize').get());
	
	var input = new Array();
	var i = 0;
	
	$("div#mainbody :input").each(function(){
		input[i] = $(this).val(); // This is the jquery object of the input, do what you will
		i++;
	});
	
	$.post('../php/savemenu.php', {'input': input}, function(responseTxt)
	{
		//alert(responseTxt);
		
		var saveMenuDialog = $('<div id="dialogDiv" title="Menu Saved!"><span id="dialogMsg">'+responseTxt+'</span></div>');
	
		saveMenuDialog.dialog({
			modal: true,
			draggable: false,
			resizable: false,
			buttons: [ { text: "Thanks", click: function() { $( this ).dialog( "close" ); } } ]
		});
	
		//$('#dialogMsg').text("Are you sure you want to delete this item?");
		saveMenuDialog.dialog("open");
	});
	
	//alert(highcat+"-"+highsubcat+"-"+highitem);
	
	//alert(serializeHTML.length)
	
	//alert(serializeHTML);
}

function categorySortable()
{

	$(function() {
		var adjustment

		$("ol.category").sortable({
		  group: 'category',
		  handle: 'i.icon-move-category',
		  // animation on drop
		  onDrop: function  (item, container, _super) {
			var clonedItem = $('<li/>').css({height: 0})
			item.before(clonedItem)
			clonedItem.animate({'height': item.height()})
	
			item.animate(clonedItem.position(), function  () {
			  clonedItem.detach()
			  _super(item)
			})
		
			highcat++
			var catli = "<i class='icon-move-category'></i><table id='menutable'><tr><td class='firstcol'><input type='hidden' id='cat_id' placeholder='cat_id' value='::"+highcat+"' /><input type='text' id='categoryname' placeholder='Category' value='' /></td></tr><tr><td class='allcol'><input type='text' id='categorynote' placeholder='Category Note' class='description' value='' /></td></tr></table><i onClick='var li = this.parentNode; var ol = li.parentNode; deletecategory(li,ol)' class='icon-cancel'></i><ol class='subcategory'></ol>"
			var catlipallet = "New Category"
		
			if(item.text()==catlipallet)
			{
				item.html(catli)
			}
		
			subcategorySortable()
			_super(item, container)
		  },
	  
		  serialize: function (parent, children, isContainer) {
			return isContainer ? children.join() : parent.html()
		  },

		  // set item relative to cursor position
		  onDragStart: function (item, container, _super) {
				// Duplicate items of the no drop area
				if(!container.options.drop)
				{
				  $("<li><i class='icon-move-category'></i>New Category</li>").insertAfter(item)
				  subcategorySortable()
				}
			  
				var offset = item.offset(),
				pointer = container.rootGroup.pointer

				adjustment = {
				  left: pointer.left - offset.left,
				  top: pointer.top - offset.top
				}
			
				_super(item)
			  },
  
		  onDrag: function ($item, position) {
			$item.css({
			  left: position.left - adjustment.left,
			  top: position.top - adjustment.top
			})
		  }
		})
	});

}

function subcategorySortable()
{
	
	$(function() {
		var adjustment

		$("ol.subcategory").sortable({
		  group: 'subcategory',
		  handle: 'i.icon-move-subcategory',
		  // animation on drop
		  onDrop: function  (item, container, _super) {
			var clonedItem = $('<li/>').css({height: 0})
			item.before(clonedItem)
			clonedItem.animate({'height': item.height()})
	
			item.animate(clonedItem.position(), function  () {
			  clonedItem.detach()
			  _super(item)
			})
		
			highsubcat++
			var subcatli = "<i class='icon-move-subcategory'></i><table id='menutable'><colgroup><col width='100%'><col span='5' nowrap></colgroup><tr><td class='firstcol'><input type='hidden' id='subcat_id' placeholder='subcat_id' value=';;"+highsubcat+"' /><input type='text' id='subcategoryname' placeholder='Subcategory' value='' /></td><td><input type='text' id='size_1' class='size' placeholder='Size 1' value='' /></td><td><input type='text' id='size_2' class='size' placeholder='Size 2' value='' /></td><td><input type='text' id='size_3' class='size' placeholder='Size 3' value='' /></td><td><input type='text' id='size_4' class='size' placeholder='Size 4' value='' /></td><td><input type='text' id='size_5' class='size' placeholder='Size 5' value='' /></td></tr><tr><td class='allcol' colspan='6'><input type='text' id='subcategorynote' placeholder='Subcategory Note' class='description' value='' /></td></tr></table><i onClick='var li = this.parentNode; var ol = li.parentNode; deletesubcategory(li,ol)' class='icon-cancel'></i><ol class='item'></ol>"
			var subcatlipallet = "New Subcategory"
		
			if(item.text()==subcatlipallet)
			{
				item.html(subcatli)
			}
		
			itemSortable()
			_super(item, container)
		  },
	  
		  serialize: function (parent, children, isContainer) {
			return isContainer ? children.join() : parent.get()
		  },

		  // set item relative to cursor position
		  onDragStart: function (item, container, _super) {
				// Duplicate items of the no drop area
				if(!container.options.drop)
				{
				  $("<li><i class='icon-move-subcategory'></i>New Subcategory</li>").insertAfter(item)
				  itemSortable()
				}
			  
				var offset = item.offset(),
				pointer = container.rootGroup.pointer

				adjustment = {
				  left: pointer.left - offset.left,
				  top: pointer.top - offset.top
				}
			
				_super(item)
			  },
  
		  onDrag: function ($item, position) {
			$item.css({
			  left: position.left - adjustment.left,
			  top: position.top - adjustment.top
			})
		  }
		})
	});
}

function itemSortable()
{

	$(function() {
		var adjustment

		$("ol.item").sortable({
		  group: 'item',
		  handle: 'i.icon-move-item',
		  // animation on drop
		  onDrop: function  (item, container, _super) {
			var clonedItem = $('<li/>').css({height: 0})
			item.before(clonedItem)
			clonedItem.animate({'height': item.height()})
	
			item.animate(clonedItem.position(), function  () {
			  clonedItem.detach()
			  _super(item)
			})
		
			objectArray = $("ol.category").sortable('serialize').get()
			$('#serialize_output').text(objectArray)
			_super(item, container)
		  },
	  
		  serialize: function (parent, children, isContainer) {
			return isContainer ? children.join() : parent.text()
		  },

		  // set item relative to cursor position
		  onDragStart: function (item, container, _super) {
				// Duplicate items of the no drop area
				if(!container.options.drop)
				  item.clone().insertAfter(item)
			  
				var offset = item.offset(),
				pointer = container.rootGroup.pointer

				adjustment = {
				  left: pointer.left - offset.left,
				  top: pointer.top - offset.top
				}
			
				_super(item)
			  },
  
		  onDrag: function ($item, position) {
			$item.css({
			  left: position.left - adjustment.left,
			  top: position.top - adjustment.top
			})
		  }
		})
	});

}

$(function() {
    var icns = {
      header: "ui-icon-circle-arrow-e",
      activeHeader: "ui-icon-circle-arrow-s"
    };
    $("#accordion").accordion({ icons: icns, header: "h3", collapsible: true, active: false, heightStyle: "content" });
    //$(".ui-accordion-container").accordion({ active: "a.default", ...,  header: "a.accordion-label" });
    $("#accordion h3 a").click(function() {
		window.location = $(this).attr('href');
		$("#accordion").accordion({ event: "click" }).activate(2);
		return false;
	});
    $( "#tabs" ).tabs();
	$( "#dialog-modal" ).dialog({
		height: 140,
		modal: true,
		dragable: false
	});
});

$(function() {
	var adjustment

	$("ol.category").sortable({
	  group: 'category',
	  handle: 'i.icon-move-category',
	  // animation on drop
	  onDrop: function  (item, container, _super) {
		var clonedItem = $('<li/>').css({height: 0})
		item.before(clonedItem)
		clonedItem.animate({'height': item.height()})
	
		item.animate(clonedItem.position(), function  () {
		  clonedItem.detach()
		  _super(item)
		})
		
		highcat++
		var catli = "<i class='icon-move-category'></i><table id='menutable'><tr><td class='firstcol'><input type='hidden' id='cat_id' placeholder='cat_id' value='::"+highcat+"' /><input type='text' id='categoryname' placeholder='Category' value='' /></td></tr><tr><td class='allcol'><input type='text' id='categorynote' placeholder='Category Note' class='description' value='' /></td></tr></table><i onClick='var li = this.parentNode; var ol = li.parentNode; deletecategory(li,ol)' class='icon-cancel'></i><ol class='subcategory'></ol>"
		var catlipallet = "New Category"
		
		if(item.text()==catlipallet)
		{
			item.html(catli)
		}
		
		subcategorySortable()
		_super(item, container)
	  },
	  
	  serialize: function (parent, children, isContainer) {
		return isContainer ? children.join() : parent.html()
	  },

	  // set item relative to cursor position
	  onDragStart: function (item, container, _super) {
			// Duplicate items of the no drop area
			if(!container.options.drop)
			{
			  $("<li><i class='icon-move-category'></i>New Category</li>").insertAfter(item)
			  subcategorySortable()
			}
			  
			var offset = item.offset(),
			pointer = container.rootGroup.pointer

			adjustment = {
			  left: pointer.left - offset.left,
			  top: pointer.top - offset.top
			}
			
			_super(item)
		  },
  
	  onDrag: function ($item, position) {
		$item.css({
		  left: position.left - adjustment.left,
		  top: position.top - adjustment.top
		})
	  }
	})
});

$(function() {
	$("ol.category-pallet").sortable({
	  drop: false,
	  group: 'category',
	  handle: 'i.icon-move-category'
	})
});

$(function() {
	var adjustment

	$("ol.subcategory").sortable({
	  group: 'subcategory',
	  handle: 'i.icon-move-subcategory',
	  // animation on drop
	  onDrop: function  (item, container, _super) {
		var clonedItem = $('<li/>').css({height: 0})
		item.before(clonedItem)
		clonedItem.animate({'height': item.height()})
	
		item.animate(clonedItem.position(), function  () {
		  clonedItem.detach()
		  _super(item)
		})
		
		highsubcat++
		var subcatli = "<i class='icon-move-subcategory'></i><table id='menutable'><colgroup><col width='100%'><col span='5' nowrap></colgroup><tr><td class='firstcol'><input type='hidden' id='subcat_id' placeholder='subcat_id' value=';;"+highsubcat+"' /><input type='text' id='subcategoryname' placeholder='Subcategory' value='' /></td><td><input type='text' id='size_1' class='size' placeholder='Size 1' value='' /></td><td><input type='text' id='size_2' class='size' placeholder='Size 2' value='' /></td><td><input type='text' id='size_3' class='size' placeholder='Size 3' value='' /></td><td><input type='text' id='size_4' class='size' placeholder='Size 4' value='' /></td><td><input type='text' id='size_5' class='size' placeholder='Size 5' value='' /></td></tr><tr><td class='allcol' colspan='6'><input type='text' id='subcategorynote' placeholder='Subcategory Note' class='description' value='' /></td></tr></table><i onClick='var li = this.parentNode; var ol = li.parentNode; deletesubcategory(li,ol)' class='icon-cancel'></i><ol class='item'></ol>"
		var subcatlipallet = "New Subcategory"
		
		if(item.text()==subcatlipallet)
		{
			item.html(subcatli)
		}
		
		itemSortable()
		_super(item, container)
	  },
	  
	  serialize: function (parent, children, isContainer) {
		return isContainer ? children.join() : parent.get()
	  },

	  // set item relative to cursor position
	  onDragStart: function (item, container, _super) {
			// Duplicate items of the no drop area
			if(!container.options.drop)
			{
			  $("<li><i class='icon-move-subcategory'></i>New Subcategory</li>").insertAfter(item)
			  itemSortable()
			}
			  
			var offset = item.offset(),
			pointer = container.rootGroup.pointer

			adjustment = {
			  left: pointer.left - offset.left,
			  top: pointer.top - offset.top
			}
			
			_super(item)
		  },
  
	  onDrag: function ($item, position) {
		$item.css({
		  left: position.left - adjustment.left,
		  top: position.top - adjustment.top
		})
	  }
	})
});

$(function() {
	$("ol.subcategory-pallet").sortable({
	  drop: false,
	  group: 'subcategory',
	  handle: 'i.icon-move-subcategory'
	})
});

$(function() {
	var adjustment

	$("ol.item").sortable({
	  group: 'item',
	  handle: 'i.icon-move-item',
	  // animation on drop
	  onDrop: function  (item, container, _super) {
		var clonedItem = $('<li/>').css({height: 0})
		item.before(clonedItem)
		clonedItem.animate({'height': item.height()})
		
		item.animate(clonedItem.position(), function  () {
		  clonedItem.detach()
		  _super(item)
		})
		
		highitem++
		var itemli = "<i class='icon-move-item'></i><table id='menutable'><colgroup><col width='100%'><col span='5' nowrap></colgroup><tr><td class='firstcol'><input type='hidden' id='item_id' placeholder='item_id' value=',,"+highitem+"' /><input type='text' id='item' placeholder='Item' value='' /></td><td><input type='text' id='price_1' class='size' placeholder='Price 1' value='' /></td><td><input type='text' id='price_2' class='size' placeholder='Price 2' value='' /></td><td><input type='text' id='price_3' class='size' placeholder='Price 3' value='' /></td><td><input type='text' id='price_4' class='size' placeholder='Price 4' value='' /></td><td><input type='text' id='price_5' class='size' placeholder='Price 5' value='' /></td></tr><tr><td class='allcol' colspan='6'><input type='text' id='item_desc' placeholder='Item Description' class='description' value='' /><br><input type='text' id='alergen' placeholder='alergen' value='' /></td></tr></table><i onClick='var li = this.parentNode; var ol = li.parentNode; deleteitem(li,ol)' class='icon-cancel'></i>"
		var itemlipallet = "New Item"
		
		if(item.text()==itemlipallet)
		{
			item.html(itemli)
		}
		
		_super(item, container)
	  },
	  
	  serialize: function (parent, children, isContainer) {
		return isContainer ? children.join() : parent.get()
	  },

	  // set item relative to cursor position
	  onDragStart: function (item, container, _super) {
			// Duplicate items of the no drop area
			if(!container.options.drop)
			  item.clone().insertAfter(item)
			  
			var offset = item.offset(),
			pointer = container.rootGroup.pointer

			adjustment = {
			  left: pointer.left - offset.left,
			  top: pointer.top - offset.top
			}
			
			_super(item)
		  },
  
	  onDrag: function ($item, position) {
		$item.css({
		  left: position.left - adjustment.left,
		  top: position.top - adjustment.top
		})
	  }
	})
});

$(function() {
	$("ol.item-pallet").sortable({
	  drop: false,
	  group: 'item',
	  handle: 'i.icon-move-item'
	})
});