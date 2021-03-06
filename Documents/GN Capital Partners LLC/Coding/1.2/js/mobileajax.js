window.onload=start;
var input = new Array();
var SESSION = new Array();
var popup = new Array();
var dial;
var registerPopupDialog;
var registerDialog;
var loginNewOrderDialog;
var itemPopupDialog;
var editItemPopupDialog;
var editOrderPopupDialog;
var hoursPopupDialog;
var popupDeleteOrderDialog;
var editOrderItemPopupDialog;
var loginFailureDialog;
var editOrderTipPopupDialog;
var placeOrderPopupDialog;
var restaurantInfoPopupDialog;
var currentAddressPopupDialog;
var editCurrentAddressPopupDialog;

function start()
{
	ga('send', 'event', 'start', 'click', 'start', {'hitCallback':
     function () {
		/*
		var backgrounds = ['background1.jpg', 'background2.jpg', 'background3.jpg', 'background4.jpg', 'background5.jpg'];
		$('body').css({'background-image': 'url(./img/' + backgrounds[Math.floor(Math.random() * backgrounds.length)] + ')'});
		*/
	
		/*
		if(!$.cookie("restaurant"))
		{
			var postdata = {};
			$.post('./php/currentaddressvariables.php', postdata, function(responseTxt)
			{
				//alert(responseTxt);
				responseTxt = '<xml version="1.0" >' + responseTxt;
				//alert(responseTxt);
		
				$(responseTxt).find("variable").each(function()
				{
					//alert($(this).attr('name'));
					SESSION[$(this).attr('name')] = $(this).text();
				});
		
				//alert(SESSION['user_id']);
		
				if(!SESSION['current_address'])
				{
					if(!SESSION['user_id'])
					{
						var postdata = {};
						$.post('./php/currentaddressloginpopup.php', postdata, function(currentAddressResponseTxt)
						{
							// do something here with the returnedData
							currentAddressPopupDialog = $('<div id="dialogDiv" title="Login"><span id="dialogMsg">'+currentAddressResponseTxt+'</span></div>');
	
							currentAddressPopupDialog.dialog({
								modal: true,
								draggable: false,
								resizable: false,
								width: 320,
								beforeClose: function() { $(this).dialog('destroy').remove(); }
							});
	
							currentAddressPopupDialog.dialog("open");
						});
					}
					else
					{
						var postdata = {};
						$.post('./php/currentaddresspopup.php', postdata, function(currentAddressResponseTxt)
						{
							// do something here with the returnedData
							currentAddressPopupDialog = $('<div id="dialogDiv" title="Login"><span id="dialogMsg">'+currentAddressResponseTxt+'</span></div>');
	
							currentAddressPopupDialog.dialog({
								modal: true,
								draggable: false,
								resizable: false,
								width: 320,
								beforeClose: function() { $(this).dialog('destroy').remove(); }
							});
	
							currentAddressPopupDialog.dialog("open");
						});
					}
				}
			});
		}
		*/
	
	 }
	});
}

function submitForm(theForm,thePage,javascript)
{
	ga('send', 'event', 'submitForm', 'click', thePage, {'hitCallback':
     function () {
	
		//alert();
		var postdata = {};
		var inputs = $(theForm).find('input, textarea, select').not(':input[type=button], :input[type=submit], :input[type=reset]');
		$(inputs).each(function()
		{
			$this = $(this);
			if($this.attr('type')=="radio")
			{
				postdata[this.id] = $this.prop('checked'); 
			}
			else if($this.attr('type')=="checkbox")
			{
				postdata[this.id] = $this.prop('checked'); 
				//alert(postdata[this.id]);
			}
			else
			{
				postdata[this.id] = this.value; // This is the jquery object of the input, do what you will
				//alert(this.id);
				//alert(postdata[this.id]);
			}
		});
	
		$.post('./php/'+thePage, postdata, function(formResponseText)
		{
			//alert(formResponseText);
			//alert(javascript);
		
			formResponseText = '<xml version="1.0" >' + formResponseText + '</xml>';
			
			//alert(formResponseText);
			//var $formResponseText = $(formResponseText);
			
			xmlDoc = $.parseXML(formResponseText);
			//alert(xmlDoc);
			$formResponseText = $(xmlDoc);
			
			//alert($formResponseText);
			//alert($formResponseText.find('action'));
		
			$formResponseText.find('variable').each(function()
			{
				//alert();
				SESSION[$(this).attr('name')] = $(this).text();
				//SESSION[$(this).attr('name')] = $(this).attr('value');
				//alert(SESSION[$(this).attr('name')]);
			});
			
			$formResponseText.find('action').each(function()
			{
				$this=$(this);
				$type=$this.attr('type');
				
				switch($type)
				{
					case 'javascript':
						eval($this.text());
						break;
					case 'popup':
						popupMessage($this);
						break;
				}
			});
			
			$formResponseText.find('javascript').each(function()
			{
				$this=$(this);
				//alert($this.text());
				eval($this.text());
			});
		
			eval(javascript);
		
		});
	
	 }
	});
}

/*
function dialog($xml)
{
	ga('send', 'event', 'dialog', 'click', 'dialog', {'hitCallback':
     function () {
		//alert('popup');
		// do something here with the returnedData
		popup[$xml.attr('name')] = $('<div id="dialogDiv" title="'+$xml.attr('title')+'"><span id="itemPopupDialogMsg">'+$xml.html()+'</span></div>');
	
		popup[$xml.attr('name')].dialog({
			modal: true,
			draggable: false,
			resizable: false,
			width: 320,
			beforeClose: function() { $(this).dialog('destroy').remove(); }
		});
	
		popup[$xml.attr('name')].dialog("open");	
	 }
	});
}
*/

function popupMessage($xml)
{
	//alert('popupMessage');
	name = $xml.attr('name');
	title = $xml.attr('title');
	text = $xml.find('text').html();
	popup[name] = $('<div id="dialogDiv" title="'+title+'"><span id="itemPopupDialogMsg">'+text+'</span></div>');

	popup[name].dialog({
		modal: true,
		draggable: false,
		resizable: false,
		width: 6320,
		beforeClose: function() { $(this).dialog('destroy').remove(); }
	});
	
	$button=$xml.find('button');
	if($button)
	{
		$button_code = "popup[name].dialog({ buttons: [ "+$button.text()+" ] });";
		eval($button_code);
	}

	popup[name].dialog("open");
}

function closeEditCurrentAddressPopupDialog()
{
	ga('send', 'event', 'closeEditCurrentAddressPopupDialog', 'click', 'closeEditCurrentAddressPopupDialog', {'hitCallback':
     function () {
		editCurrentAddressPopupDialog.dialog('close');
		$("#mainleft").load("./php/mobileleftcategoriespage.php");
		//$("#mainbody").load("./php/categoriespage.php #main");
	 }
	});
}

function loadRegDiv()
{
	ga('send', 'event', 'loadRegDiv', 'click', 'loadRegDiv', {'hitCallback':
     function () {
		start();
		checkToEnable();
		$(document).ajaxComplete(function()
		{
			checkToEnable();
		});
	 }
	});
}

function loadMenuDiv()
{
	ga('send', 'event', 'loadMenuDiv', 'click', 'loadMenuDiv', {'hitCallback':
     function () {
		//alert(1);
	
		$('#mainleft').load('./php/' + page);
	
		$(document).ajaxComplete(function()
		{
			//alert(2);
			$('.sorted_table').sortable({
			  containerSelector: 'table',
			  itemPath: '> tbody',
			  itemSelector: 'tr',
			  placeholder: '<tr class="placeholder"/>'
			});
		});
	 }
	});
}

function loadMenuEdit()
{	
	ga('send', 'event', 'loadMenuEdit', 'click', 'loadMenuEdit', {'hitCallback':
     function () {
		/*
		$('#mainleft').load('./php/leftsorttest.php', function()
		{
		*/
			categorySortable();
			subcategorySortable();
			itemSortable();
		
			//alert(3);
		/*
		});
		*/
	 }
	});
}

function registerPage()
{
	ga('send', 'event', 'registerPage', 'click', 'registerPage', {'hitCallback':
     function () {
		$('#topbar').load('./php/registerpage.php #top');
		$('#mainbody').load('./php/registerpage.php #main', function()
		{
			checkToEnable();
		});
	 }
	});
}

function restaurantRegisterPage()
{
	ga('send', 'event', 'restaurantRegisterPage', 'click', 'restaurantRegisterPage', {'hitCallback':
     function () {
		$('#topbar').load('./php/restaurantregisterpage.php #top');
		$('#mainbody').load('./php/restaurantregisterpage.php #main', function()
		{
			checkToEnable();
		});
	 }
	});
}

function changeURLVariable(inputText)
{
	ga('send', 'event', 'changeURLVariable', 'click', inputText, {'hitCallback':
     function () {
	
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
	});
}

function checkToEnable()
{
	ga('send', 'event', 'checkToEnable', 'click', 'checkToEnable', {'hitCallback':
     function () {
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
	});
}

function checkToEnablePlaceOrder(theForm)
{
	ga('send', 'event', 'checkToEnablePlaceOrder', 'click', 'checkToEnablePlaceOrder', {'hitCallback':
     function () {
		//alert('checkToEnablePlaceOrder');
		//input = new Array();
		//var i = 0;
		//var forminputtext = "";
	
		$(theForm).find(":input[type=submit]").each(function() {
			this.disabled = false;
		});

		$(theForm).find(":input").each(function() {
		
			var testID = this.id + "test";
			//alert(testID);
			//alert(document.getElementById(testID));
			//alert(document.getElementById(testID).className != "optional");
		
			if(document.getElementById(testID).className != "optional")
			{
				//alert('not optional');
				$(theForm).find(":input[type=submit]").each(function() {
					//alert('this.disabled = true;');
					this.disabled = true;
				});
				return false;
			}
		});
	 }
	});
}

function checkToEnableNew(theForm)
{
	ga('send', 'event', 'checkToEnableNew', 'click', 'checkToEnableNew', {'hitCallback':
     function () {
		//alert('checkToEnableNew');
		//input = new Array();
		//var i = 0;
		//var forminputtext = "";
	
		theForm.find(":input[type=submit]").each(function() {
			this.disabled = false;
		});

		theForm.find(":input[type!=submit]").each(function() {
		
			var testID = '#' + this.id + "_test";
			//alert(testID);
			//alert(document.getElementById(testID));
			//alert(document.getElementById(testID).className != "optional");
			//alert(theForm.find(testID).attr('class'));
		
			if(theForm.find(testID).attr('class') != "optional")
			{
				//alert('not optional');
				theForm.find(":input[type=submit]").each(function() {
					//alert('this.disabled = true;');
					this.disabled = true;
				});
				return false;
			}
		});
	 }
	});
}

function checkNewOrder()
{
	ga('send', 'event', 'checkNewOrder', 'click', 'checkNewOrder', {'hitCallback':
     function () {
		var emailfilled = (document.getElementById("emailtest").className == "optional");
		var phonenumberfilled = (document.getElementById("phonenumbertest").className == "optional");
		var firstnamefilled = (document.getElementById("firstnametest").className == "optional");
		var lastnamefilled = (document.getElementById("lastnametest").className == "optional");
	
		var deliveryorpickup = document.getElementById("delivery").value;
	
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
	});
}

function disableButton()
{
	ga('send', 'event', 'disableButton', 'click', 'disableButton', {'hitCallback':
     function () {
		document.getElementById("registerbutton").disabled = true;
		document.getElementById("registerbutton").title = "Disabled";
	 }
	});
}

function enableButton()
{
	ga('send', 'event', 'enableButton', 'click', 'enableButton', {'hitCallback':
     function () {
		document.getElementById("registerbutton").disabled = false;
		document.getElementById("registerbutton").title = "Register";
	 }
	});
}

function getXMLhttp()
{
	ga('send', 'event', 'getXMLhttp', 'click', 'getXMLhttp', {'hitCallback':
     function () {
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
	});
}

function calculateTip(percent,subtotal,tax,delivery_fee)
{
	//alert('calculateTip');
	tip = Math.round(subtotal*percent)/100;
	document.getElementById("orderTip").value = tip;
	calculateTotal(subtotal,tax,delivery_fee,tip);
}

function roundTotal(total,subtotal,tax,delivery_fee)
{
	ga('send', 'event', 'roundTotal', 'click', total, {'hitCallback':
     function () {
		if(total != Math.round(total))
		{
			//alert(total+','+subtotal+','+tax+','+delivery_fee+','+tip);
			roundedTotal = Math.round(total+.5);
			totalDifference = Math.round((roundedTotal-total)*100)/100;
			//alert(roundedTotal+','+totalDifference);
			tip = document.getElementById("orderTip").value;
			tip = Number(tip);
			tip = Math.round((tip+totalDifference)*100)/100;
			document.getElementById("orderTip").value = tip;
			calculateTotal(subtotal,tax,delivery_fee,tip);
		}
	 }
	});
}

function calculateTotal(subtotal,tax,delivery_fee,tip)
{
	//alert('calculateTotal');
	tip = Number(tip);
	//alert(tip);
	total = Math.round((subtotal+tax+delivery_fee+tip)*100)/100;
	document.getElementById("orderTotal").innerHTML = "$"+total;
}

function login(theForm)
{
	ga('send', 'event', 'login', 'click', 'login', {'hitCallback':
     function () {
		//alert();
		var postdata = {};
		postdata['username'] = theForm.elements['username'].value;
		postdata['password'] = theForm.elements['password'].value;
		//alert(postdata['username']);
		$.post('./php/login.php', postdata, function(loginResponseTxt)
		{
			// do something here with the returnedData
			//alert(loginResponseTxt);
			if(loginResponseTxt == 'Welcome')
			{
				//document.getElementById("topnavcontent").innerHTML=loginResponseTxt;
				var postdata2 = {};
				$.post('./php/sessionusername.php', postdata2, function(responseTxt)
				{
					u = responseTxt;
					$.post('./php/sessionsignin.php', postdata2, function(responseTxt)
					{
						s = responseTxt;
						$.cookie("u", u, { expires: 3, domain: '.gottanom.com'});
						$.cookie("s", s, { expires: 3, domain: '.gottanom.com'});
						location.reload();
					});
				});
			}
			else
			{
				// do something here with the returnedData
				loginFailureDialog = $('<div id="dialogDiv" title="Login Failure"><span id="loginDialogMsg">'+loginResponseTxt+'</span></div>');
		
				loginFailureDialog.dialog({
					modal: true,
					draggable: false,
					resizable: false,
					width: 320,
					buttons: [ { text: "Try Again", click: function() { $( this ).dialog( "close" ); } } ]
				});
		
				loginFailureDialog.dialog("open");
			}
		});
	 }
	});
}

function loginwithoutreload(theForm,loc_id,rest_id,item_id)
{
	ga('send', 'event', 'loginwithoutreload', 'click', 'loginwithoutreload', {'hitCallback':
     function () {
		var postdata = {};
		postdata['username'] = theForm.elements['username'].value;
		postdata['password'] = theForm.elements['password'].value;
		//alert(postdata['username']);
		//alert(postdata['password']);
		$.post('./php/loginneworderpopup.php?loc_id=' + loc_id + '&rest_id=' + rest_id + '&item_id=' + item_id, postdata, function(responseTxt)
		{
			// do something here with the returnedData
			document.getElementById("loginNewOrderDialogMsg").innerHTML=responseTxt;
		
			var postdata2 = {};
			$.post('./php/sessionusername.php', postdata2, function(responseTxt)
			{
				u = responseTxt;
				if(u)
				{
					loginNewOrderDialog.dialog({
						modal: true,
						draggable: false,
						resizable: false,
						width: 320,
						beforeClose: function() { startNewOrderPopup(loc_id,rest_id,item_id); }
					});
				}
				$.post('./php/sessionsignin.php', postdata2, function(responseTxt)
				{
					s = responseTxt;
					$.cookie("u", u, { expires: 3, domain: '.gottanom.com'});
					$.cookie("s", s, { expires: 3, domain: '.gottanom.com'});
				});
			});
		});
	 }
	});
}

function loginneworder()
{
	ga('send', 'event', 'loginneworder', 'click', 'loginneworder', {'hitCallback':
     function () {
		var postdata = {};
		postdata['username'] = document.getElementById('username').value;
		postdata['password'] = document.getElementById('password').value;
		//alert(postdata['username']);
		//alert(postdata['password']);
		$.post('./php/loginneworderpopup.php?loc_id=' + loc_id + '&rest_id=' + rest_id + '&item_id=' + item_id, postdata, function(responseTxt)
		{
			// do something here with the returnedData
			document.getElementById("loginNewOrderDialogMsg").innerHTML=responseTxt;
		
			var postdata2 = {};
			$.post('./php/sessionusername.php', postdata2, function(responseTxt)
			{
				u = responseTxt;
				$.post('./php/sessionsignin.php', postdata2, function(responseTxt)
				{
					s = responseTxt;
					$.cookie("u", u, { expires: 3, domain: '.gottanom.com'});
					$.cookie("s", s, { expires: 3, domain: '.gottanom.com'});
				});
			});
		});
	 }
	});
}

function loginRestaurant(theForm)
{
	ga('send', 'event', 'loginRestaurant', 'click', 'loginRestaurant', {'hitCallback':
     function () {
		var postdata = {};
		postdata['username'] = theForm.elements['loginusername'].value;
		postdata['password'] = theForm.elements['loginpassword'].value;
		$.post('./php/loginrestaurant.php', postdata, function(loginResponseTxt)
		{
			// do something here with the returnedData
			// document.getElementById("topnavcontent").innerHTML=responseTxt;
			// alert(loginResponseTxt);
			if(loginResponseTxt == 'Welcome')
			{
				//document.getElementById("topnavcontent").innerHTML=loginResponseTxt;
				var postdata2 = {};
				$.post('./php/sessionusername.php', postdata2, function(responseTxt)
				{
					u = responseTxt;
					$.post('./php/sessionsignin.php', postdata2, function(responseTxt)
					{
						s = responseTxt;
						$.cookie("u", u, { expires: 3, domain: '.gottanom.com'});
						$.cookie("s", s, { expires: 3, domain: '.gottanom.com'});
						location.reload();
					});
				});
			}
			else
			{
				// do something here with the returnedData
				loginFailureDialog = $('<div id="dialogDiv" title="Login Failure"><span id="loginDialogMsg">'+loginResponseTxt+'</span></div>');
		
				loginFailureDialog.dialog({
					modal: true,
					draggable: false,
					resizable: false,
					width: 620,
					buttons: [ { text: "Try Again", click: function() { $( this ).dialog( "close" ); } } ]
				});
		
				loginFailureDialog.dialog("open");
			}
		});
	 }
	});
}

function logout()
{
	ga('send', 'event', 'logout', 'click', 'logout', {'hitCallback':
     function () {
		$.cookie("u", null, { domain: '.gottanom.com'});
		$.cookie("s", null, { domain: '.gottanom.com'});
		var postdata = {};
		$.post('./php/logout.php', postdata, function(responseTxt)
		{
			location.href='https://gottanom.com/mobile.php';
		});
	 }
	});
}

function cookielogin(i,s)
{
	ga('send', 'event', 'cookielogin', 'click', 'cookielogin', {'hitCallback':
     function () {
		var ns = makecode();
		var xmlhttp;
		xmlhttp = getXMLhttp();
		xmlhttp.open("POST","./php/login.php",true);
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
	});
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
	ga('send', 'event', 'popupLogin', 'click', 'popupLogin', {'hitCallback':
     function () {
		var postdata = {};
		$.post('./php/mobilelogindiv.php', postdata, function(responseTxt)
		{
			// do something here with the returnedData
			loginPopupDialog = $('<div id="dialogDiv" title="Login"><span id="dialogMsg">'+responseTxt+'</span></div>');
	
			loginPopupDialog.dialog({
				modal: true,
				draggable: false,
				resizable: false,
				width: 320
			});
	
			loginPopupDialog.dialog("open");
		});
	 }
	});
}

function popupRestaurantLogin()
{
	ga('send', 'event', 'popupRestaurantLogin', 'click', 'popupRestaurantLogin', {'hitCallback':
     function () {
		var postdata = {};
		$.post('./php/loginrestaurantdiv.php', postdata, function(responseTxt)
		{
			// do something here with the returnedData
			loginPopupDialog = $('<div id="dialogDiv" title="Login"><span id="dialogMsg">'+responseTxt+'</span></div>');
	
			loginPopupDialog.dialog({
				modal: true,
				draggable: false,
				resizable: false,
				width: 320
			});
	
			loginPopupDialog.dialog("open");
		});
	 }
	});
}

function popupLoginNewOrder()
{
	ga('send', 'event', 'popupLoginNewOrder', 'click', 'popupLoginNewOrder', {'hitCallback':
     function () {
		var postdata = {};
		$.post('./php/loginneworderdiv.php', postdata, function(responseTxt)
		{
			// do something here with the returnedData
			loginNewOrderDialog = $('<div id="dialogDiv" title="Login"><span id="loginNewOrderDialogMsg">'+responseTxt+'</span></div>');
	
			loginNewOrderDialog.dialog({
				modal: true,
				draggable: false,
				resizable: false,
				width: 320
			});
	
			loginNewOrderDialog.dialog("open");
		});
	 }
	});
}

function popupItem(loc_id,rest_id,item_id,name)
{
	ga('send', 'event', 'popupItem', 'click', 'rest_id=' + rest_id + '&item_id=' + item_id, {'hitCallback':
     function () {
		name = decodeURIComponent(name);
		var postdata = {};
		$.post('./php/itempopup.php?loc_id=' + loc_id + '&rest_id=' + rest_id + '&item_id=' + item_id, postdata, function(itemPopupResponseTxt)
		{
			// do something here with the returnedData
			itemPopupDialog = $('<div id="dialogDiv" title="'+name+'"><span id="itemPopupDialogMsg">'+itemPopupResponseTxt+'</span></div>');
		
			itemPopupDialog.find("#notes").attr('cols','25');
		
			itemPopupDialog.dialog({
				modal: true,
				draggable: false,
				resizable: false,
				width: 320,
				beforeClose: function() { $(this).dialog('destroy').remove(); }
			});
		
			itemPopupDialog.dialog("open");
		});
	 }
	});
}

function popupEditItem(loc_id,rest_id,item_id,name)
{
	ga('send', 'event', 'popupEditItem', 'click', 'rest_id=' + rest_id + '&item_id=' + item_id, {'hitCallback':
     function () {
		var postdata = {};
		$.post('./php/edititempopup.php?loc_id=' + loc_id + '&rest_id=' + rest_id + '&item_id=' + item_id, postdata, function(editItemPopupResponseTxt)
		{
			// do something here with the returnedData
			editItemPopupDialog = $('<div id="dialogDiv" title="'+name+'"><span id="itemPopupDialogMsg">'+editItemPopupResponseTxt+'</span></div>');
	
			editItemPopupDialog.dialog({
				modal: true,
				draggable: false,
				resizable: false,
				width: 320
			});
	
			editItemPopupDialog.dialog("open");
		});
	 }
	});
}

function popupEditOrder()
{
	ga('send', 'event', 'popupEditOrder', 'click', 'popupEditOrder', {'hitCallback':
     function () {
		var postdata = {};
		$.post('./php/mobileeditorderpopup.php', postdata, function(editOrderResponseTxt)
		{
			// do something here with the returnedData

			editOrderPopupDialog = $('<div id="dialogDiv" title="Edit Order"><span id="itemPopupDialogMsg">'+editOrderResponseTxt+'</span></div>');

			editOrderPopupDialog.dialog({
				modal: true,
				draggable: false,
				resizable: false,
				width: 320,
				beforeClose: function() { $(this).dialog('destroy').remove(); }
			});
	
			editOrderPopupDialog.dialog("open");
		});
	 }
	});
}

function popupDeleteOrder()
{
	ga('send', 'event', 'popupDeleteOrder', 'click', 'popupDeleteOrder', {'hitCallback':
     function () {
		popupDeleteOrderDialog = $('<div id="dialogDiv" title="Delete Order?"><span id="dialogMsg">Are you sure you want to delete this order?</span></div>');
	
		popupDeleteOrderDialog.dialog({
			modal: true,
			draggable: false,
			resizable: false,
			buttons: [ { text: "Yes", click: function() { deleteOrder(); $( this ).dialog( "close" ); } }, { text: "No", click: function() { $( this ).dialog( "close" ); } } ]
		});
	
		//$('#dialogMsg').text("Are you sure you want to delete this item?");
		popupDeleteOrderDialog.dialog("open");
	 }
	});
}

function editOrder(theForm)
{
	ga('send', 'event', 'editOrder', 'click', 'editOrder', {'hitCallback':
     function () {
		var postdata = {};
	
		$(theForm).find(":input").each(function()
		{
			$this = $(this);
			if($this.attr('type')=="radio")
			{
				postdata[this.id] = $this.is(':checked'); 
			}
			else if($this.attr('type')=="checkbox")
			{
				postdata[this.id] = $this.is(':checked'); 
				//alert(postdata[this.id]);
			}
			else
			{
				postdata[this.id] = this.value; // This is the jquery object of the input, do what you will
				//alert(this.id);
				//alert(postdata[this.id]);
			}
		});
	
		/*	
		postdata['newdelivery'] = document.getElementById('newdelivery').value;
		postdata['newemail'] = document.getElementById('newemail').value;
		postdata['newphonenumber'] = document.getElementById('newphonenumber').value;
		postdata['newfirstname'] = document.getElementById('newfirstname').value;
		postdata['newlastname'] = document.getElementById('newlastname').value;
		postdata['newaddress'] = document.getElementById('newaddress').value;
		postdata['newcity'] = document.getElementById('newcity').value;
		postdata['newstate'] = document.getElementById('newstate').value;
		postdata['newzipcode'] = document.getElementById('newzipcode').value;
		*/
	
		$.post('./php/editordernew.php', {'postdata': postdata}, function(editOrderResponseTxt)
		{
			$('#mainleft').load('./php/mobilevieworderpage.php #main');
		
			editOrderPopupDialog.dialog("close");
		});
	 }
	});
}

function popupEditCurrentAddress()
{
	ga('send', 'event', 'popupEditCurrentAddress', 'click', 'popupEditCurrentAddress', {'hitCallback':
     function () {
		//alert();
	
		var postdata = {};
		$.post('./php/mobileeditcurrentaddresspopup.php', postdata, function(editCurrentAddressPopupResponseTxt)
		{
			//alert(editCurrentAddressPopupResponseTxt);
			// do something here with the returnedData
		
			editCurrentAddressPopupDialog = $('<div id="dialogDiv" title="Edit Current Address"><span id="editCurrentAddressPopupDialogMsg">'+editCurrentAddressPopupResponseTxt+'</span></div>');

			editCurrentAddressPopupDialog.dialog({
				modal: true,
				draggable: false,
				resizable: false,
				width: 320,
				beforeClose: function() { $(this).dialog('destroy').remove(); }
			});
	
			editCurrentAddressPopupDialog.dialog("open");
		
			testFormCurrentAddress();
		});
	 }
	});
}

function editOrderPlaceOrderPopup()
{
	ga('send', 'event', 'editOrderPlaceOrderPopup', 'click', 'editOrderPlaceOrderPopup', {'hitCallback':
     function () {
		var postdata = {};
		$.post('./php/mobileeditorderpopup.php', postdata, function(editOrderResponseTxt)
		{
			// do something here with the returnedData
			//if(!editOrderPopupDialog)
			//{
				editOrderPopupDialog = $('<div id="dialogDiv" title="Edit Order"><span id="itemPopupDialogMsg">'+editOrderResponseTxt+'</span></div>');

				editOrderPopupDialog.dialog({
					modal: true,
					draggable: false,
					resizable: false,
					width: 320,
					beforeClose: function() { reviewOrder(); $(this).dialog('destroy').remove(); }
				});
			//}
	
			editOrderPopupDialog.dialog("open");
		});
	 }
	});
}

function deleteOrder()
{
	ga('send', 'event', 'deleteOrder', 'click', 'deleteOrder', {'hitCallback':
     function () {
		//alert('debug');
		var postdata = {};
		$.post('./php/deleteorder.php', postdata, function(deleteOrderResponseTxt)
		{
			//alert(deleteOrderResponseTxt);
			if(deleteOrderResponseTxt == 0)
			{
				location.reload();
			}
		});
	 }
	});
}

function deleteItem(order_item_id,loc_id,rest_id)
{
	ga('send', 'event', 'deleteItem', 'click', 'rest_id='+rest_id+'&order_item_id'+order_item_id, {'hitCallback':
     function () {
		var postdata = {};
		//alert(order_item_id);
	
		$.post('./php/deleteitem.php?order_item_id=' + order_item_id, postdata, function(deleteItemResponseTxt)
		{
			//alert(deleteItemResponseTxt);
			if(deleteItemResponseTxt == 0)
			{
				//$('#mainleft').load('./php/leftcategoriespage.php');
				setMobile('mobilerestaurantpage.php?loc_id='+loc_id+'&rest_id='+rest_id);
			}
			else
			{
				$('#mainleft').load('./php/mobilevieworderpage.php #main');
			}
		});
	 }
	});
}

function deleteItemViewFullOrderPopup(order_item_id)
{
	ga('send', 'event', 'deleteItemViewFullOrderPopup', 'click', order_item_id, {'hitCallback':
     function () {
		deleteItemViewFullOrderPopupDialog = $('<div id="dialogDiv" title="Delete Item?"><span id="itemPopupDialogMsg">Are you sure you want to delete this item?</span></div>');

		deleteItemViewFullOrderPopupDialog.dialog({
			modal: true,
			draggable: false,
			resizable: false,
			buttons: [ { text: "Yes", click: function() { deleteItemViewFullOrder(order_item_id); $( this ).dialog( "close" ); } }, { text: "No", click: function() { $( this ).dialog( "close" ); } } ]
		});

		deleteItemViewFullOrderPopupDialog.dialog("open");
	 }
	});
}

function deleteItemViewFullOrder(order_item_id)
{
	ga('send', 'event', 'deleteItemViewFullOrder', 'click', order_item_id, {'hitCallback':
     function () {
		var postdata = {};
		//alert(order_item_id);
	
		$.post('./php/deleteitem.php?order_item_id=' + order_item_id, postdata, function(deleteItemResponseTxt)
		{
			//alert(deleteItemResponseTxt);
			if(deleteItemResponseTxt == 0)
			{
				$('#mainleft').load('./php/leftcategoriespage.php');
				setMain('restaurantpage.php');
			}
			else
			{
				$('#mainleft').load('./php/leftorderpage.php');
				$('#mainbody').load('./php/viewfullorderpage.php #main');
			}
		});
	 }
	});
}

function deleteItemPlaceOrderPopup(order_item_id)
{
	ga('send', 'event', 'deleteItemPlaceOrderPopup', 'click', order_item_id, {'hitCallback':
     function () {
		deleteItemPlaceOrderPopupDialog = $('<div id="dialogDiv" title="Delete Item?"><span id="itemPopupDialogMsg">Are you sure you want to delete this item?</span></div>');

		deleteItemPlaceOrderPopupDialog.dialog({
			modal: true,
			draggable: false,
			resizable: false,
			buttons: [ { text: "Yes", click: function() { deleteItemPlaceOrder(order_item_id); $( this ).dialog( "close" ); } }, { text: "No", click: function() { $( this ).dialog( "close" ); } } ]
		});

		deleteItemPlaceOrderPopupDialog.dialog("open");
	 }
	});
}

function deleteItemPlaceOrder(order_item_id)
{
	ga('send', 'event', 'deleteItemPlaceOrder', 'click', order_item_id, {'hitCallback':
     function () {
		var postdata = {};
		//alert(order_item_id);
	
		$.post('./php/deleteitem.php?order_item_id=' + order_item_id, postdata, function(deleteItemResponseTxt)
		{
			//alert(deleteItemResponseTxt);
			if(deleteItemResponseTxt == 0)
			{
				$('#mainleft').load('./php/leftcategoriespage.php');
				setMain('restaurantpage.php');
			}
			else
			{
				$('#mainleft').load('./php/leftorderpage.php');
				$('#mainbody').load('./php/placeorderpage.php #main');
			}
		});
	 }
	});
}

function editOrderItemPopup(order_item_id,name)
{
	ga('send', 'event', 'editOrderItemPopup', 'click', order_item_id, {'hitCallback':
     function () {
		name = decodeURIComponent(name);
		var postdata = {};
		$.post('./php/mobileeditorderitempopup.php?order_item_id=' + order_item_id, postdata, function(editOrderItemPopupResponseTxt)
		{
			// do something here with the returnedData
			editOrderItemPopupDialog = $('<div id="dialogDiv" title="'+name+'"><span id="itemPopupDialogMsg">'+editOrderItemPopupResponseTxt+'</span></div>');
	
			editOrderItemPopupDialog.dialog({
				modal: true,
				draggable: false,
				resizable: false,
				width: 320,
				beforeClose: function() { $(this).dialog('destroy').remove(); }
			});
	
			editOrderItemPopupDialog.dialog("open");
		});
	 }
	});
}

function editOrderItemViewFullOrderPopup(order_item_id,name)
{
	ga('send', 'event', 'editOrderItemViewFullOrderPopup', 'click', order_item_id, {'hitCallback':
     function () {
		name = decodeURIComponent(name);
		var postdata = {};
		$.post('./php/editorderitempopup.php?order_item_id=' + order_item_id, postdata, function(editOrderItemPopupResponseTxt)
		{
			// do something here with the returnedData
			editOrderItemPopupDialog = $('<div id="dialogDiv" title="'+name+'"><span id="itemPopupDialogMsg">'+editOrderItemPopupResponseTxt+'</span></div>');
	
			editOrderItemPopupDialog.dialog({
				modal: true,
				draggable: false,
				resizable: false,
				width: 320,
				beforeClose: function() { $('#mainbody').load('./php/viewfullorderpage.php #main'); }
			});
	
			editOrderItemPopupDialog.dialog("open");
		});
	 }
	});
}

function editOrderItemPlaceOrderPopup(order_item_id,name)
{
	ga('send', 'event', 'editOrderItemPlaceOrderPopup', 'click', order_item_id, {'hitCallback':
     function () {
		name = decodeURIComponent(name);
		var postdata = {};
		$.post('./php/editorderitempopup.php?order_item_id=' + order_item_id, postdata, function(editOrderItemPopupResponseTxt)
		{
			// do something here with the returnedData
			editOrderItemPopupDialog = $('<div id="dialogDiv" title="'+name+'"><span id="itemPopupDialogMsg">'+editOrderItemPopupResponseTxt+'</span></div>');
	
			editOrderItemPopupDialog.dialog({
				modal: true,
				draggable: false,
				resizable: false,
				width: 320,
				beforeClose: function() { reviewOrder(); }
			});
	
			editOrderItemPopupDialog.dialog("open");
		});
	 }
	});
}

function editOrderTipPopup()
{
	ga('send', 'event', 'editOrderTipPopup', 'click', 'editOrderTipPopup', {'hitCallback':
     function () {
		var postdata = {};
		$.post('./php/editordertippopup.php', postdata, function(editOrderTipPopupResponseTxt)
		{
			// do something here with the returnedData
			if(!editOrderTipPopupDialog)
			{
				editOrderTipPopupDialog = $('<div id="dialogDiv" title="Tip"><span id="itemPopupDialogMsg">'+editOrderTipPopupResponseTxt+'</span></div>');
		
				editOrderTipPopupDialog.dialog({
					modal: true,
					draggable: false,
					resizable: false,
					width: 320
				});
			}
	
			editOrderTipPopupDialog.dialog("open");
		});
	 }
	});
}

function mobileEditOrderTipPopup()
{
	ga('send', 'event', 'mobileEditOrderTipPopup', 'click', 'mobileEditOrderTipPopup', {'hitCallback':
     function () {
		var postdata = {};
		$.post('./php/mobileeditordertippopup.php', postdata, function(editOrderTipPopupResponseTxt)
		{
			// do something here with the returnedData
			if(!editOrderTipPopupDialog)
			{
				editOrderTipPopupDialog = $('<div id="dialogDiv" title="Tip"><span id="itemPopupDialogMsg">'+editOrderTipPopupResponseTxt+'</span></div>');
		
				editOrderTipPopupDialog.dialog({
					modal: true,
					draggable: false,
					resizable: false,
					width: 320
				});
			}
	
			editOrderTipPopupDialog.dialog("open");
		});
	 }
	});
}

function editOrderTip(theForm)
{
	ga('send', 'event', 'editOrderTip', 'click', 'editOrderTip', {'hitCallback':
     function () {
		var postdata = {};
		postdata['tip'] = theForm.elements['orderTip'].value;
		//alert(postdata['tip']);
		$.post('./php/editordertip.php', postdata, function(editOrderTipResponseTxt)
		{
			//alert(editOrderTipResponseTxt);
			//alert(editOrderTipResponseTxt);
		
			editOrderTipPopupDialog.dialog("close");
		
			$('#mainleft').load('./php/leftorderpage.php');
		});
	 }
	});
}

function mobileEditOrderTip(theForm)
{
	ga('send', 'event', 'mobileEditOrderTip', 'click', 'mobileEditOrderTip', {'hitCallback':
     function () {
		var postdata = {};
		postdata['tip'] = theForm.elements['orderTip'].value;
		//alert(postdata['tip']);
		$.post('./php/editordertip.php', postdata, function(editOrderTipResponseTxt)
		{
			//alert(editOrderTipResponseTxt);
			//alert(editOrderTipResponseTxt);
		
			editOrderTipPopupDialog.dialog("close");
		
			$('#mainleft').load('./php/mobilevieworderpage.php #main');
		});
	 }
	});
}

function addItemToOrder(theForm,loc_id,rest_id,item_id)
{
	ga('send', 'event', 'addItemToOrder', 'click', 'rest_id=' + rest_id + '&item_id=' + item_id, {'hitCallback':
     function () {
		//alert();
		//alert(loc_id+","+rest_id+","+item_id);
	
		input = new Array();
		var i = 0;

		$(theForm).find(":input").each(function(){
			if($(this).attr('type')=="radio")
			{
				input[i] = $(this).is(':checked'); 
			}
			else if($(this).attr('type')=="checkbox")
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
		$.post('./php/checkneworder.php', postdata, function(checkNewOrderResponseTxt)
		{
			//alert('checkneworder');
		
			if(checkNewOrderResponseTxt=="New Order")
			{
				var postdata = {};
				$.post('./php/sessionusername.php', postdata, function(username)
				{
					if(!username)
					{
						var postdata = {};
						$.post('./php/mobileloginneworderdiv.php?loc_id=' + loc_id + '&rest_id=' + rest_id + '&item_id=' + item_id, postdata, function(responseTxt)
						{
							// do something here with the returnedData
							if(!loginNewOrderDialog)
							{
								loginNewOrderDialog = $('<div id="dialogDiv" title="Login"><span id="loginNewOrderDialogMsg">'+responseTxt+'</span></div>');
					
								loginNewOrderDialog.dialog({
									modal: true,
									draggable: false,
									resizable: false,
									width: 320
								});
							}
					
							loginNewOrderDialog.dialog("open");
						});
					}
					else
					{
						//alert('calling startNewOrderPopup');
						startNewOrderPopup(loc_id, rest_id, item_id);
					}
				});
			}
			else if(checkNewOrderResponseTxt=="Existing Order")
			{
				//alert('existing order');
			
				$.post('./php/additemtoorder.php?loc_id=' + loc_id + '&rest_id=' + rest_id + '&item_id=' + item_id, {'input': input}, function(addItemToOrderResponseTxt)
				{
					//alert(addItemToOrderResponseTxt);
				
					$('#topbar').load('./php/mobilerestaurantpage.php #top');
				
					itemPopupDialog.dialog("close");
				});
			}
		});
	 }
	});
}

function startNewOrderPopup(loc_id,rest_id,item_id)
{
	ga('send', 'event', 'startNewOrderPopup', 'click', 'rest_id=' + rest_id + '&item_id=' + item_id, {'hitCallback':
     function () {
		//alert(loc_id+","+rest_id+","+item_id);
		//alert('startNewOrderPopup');
		//alert(input);
		$.post('./php/mobilestartneworderpopup.php?loc_id=' + loc_id + '&rest_id=' + rest_id + '&item_id=' + item_id, {'input': input}, function(startOrderResponseTxt)
		{
			//alert(startOrderResponseTxt);
			// do something here with the returnedData
			var startNewOrderDialog = $('<div id="dialogDiv" title="New Order"><span id="dialogMsg">'+startOrderResponseTxt+'</span></div>');
			//alert(2);
			startNewOrderDialog.dialog({
				modal: true,
				draggable: false,
				resizable: false,
				width: 320,
				beforeClose: function() { $(this).dialog('destroy').remove(); }
			});
			//alert(3);
			startNewOrderDialog.dialog("open");
		});
	 }
	});
}

function startNewOrder(loc_id,rest_id,item_id)
{
	ga('send', 'event', 'startNewOrder', 'click', 'rest_id=' + rest_id + '&item_id=' + item_id, {'hitCallback':
     function () {
		var postdata = {};
		postdata['newdelivery'] = document.getElementById('delivery').value;
		postdata['newemail'] = document.getElementById('email').value;
		postdata['newphonenumber'] = document.getElementById('phone_number').value;
		postdata['newfirstname'] = document.getElementById('first_name').value;
		postdata['newlastname'] = document.getElementById('last_name').value;
		postdata['on_beach'] = document.getElementById('current_on_beach').value;
		postdata['newaddress'] = document.getElementById('address').value;
		postdata['newcity'] = document.getElementById('city').value;
		postdata['newstate'] = document.getElementById('state').value;
		postdata['newzipcode'] = document.getElementById('zip').value;
		$.post('./php/startneworder.php?loc_id=' + loc_id + '&rest_id=' + rest_id + '&item_id=' + item_id, {'input': input, 'postdata': postdata}, function(startOrderResponseTxt)
		{
			// do something here with the returnedData
			//alert(startOrderResponseTxt);
			location.reload();
		});
	 }
	});
}

function updateItemInOrder(order_item_id)
{
	ga('send', 'event', 'updateItemInOrder', 'click', order_item_id, {'hitCallback':
     function () {
		//alert();
		//alert(loc_id+","+rest_id+","+item_id);
		//alert(order_item_id);
	
		input = new Array();
		var i = 0;

		$("div#dialogDiv :input").each(function(){
			if($(this).attr('type')=="radio")
			{
				input[i] = $(this).is(':checked'); 
			}
			else if($(this).attr('type')=="checkbox")
			{
				input[i] = $(this).is(':checked'); 
			}
			else
			{
				input[i] = $(this).val(); // This is the jquery object of the input, do what you will
			}
			i++;
		});
	
		//alert(i);
	
		$.post('./php/updateiteminorder.php?order_item_id=' + order_item_id, {'input': input}, function(updateItemInOrderResponseTxt)
		{
			//alert(updateItemInOrderResponseTxt);
		
			$('#mainleft').load('./php/mobilevieworderpage.php #main');
		
			editOrderItemPopupDialog.dialog("close");
		});
	 }
	});
}

function placeOrderPopup(theForm)
{
	ga('send', 'event', 'placeOrderPopup', 'click', 'placeOrderPopup', {'hitCallback':
     function () {
		placeOrderPopupDialog = $('<div id="dialogDiv" title="Place Your Order!"><span id="dialogMsg">Are you sure you would like to place your order?</span></div>');

		placeOrderPopupDialog.dialog({
			modal: true,
			draggable: false,
			resizable: false,
			buttons: [ { text: "Yes", click: function() { placeOrder(theForm); } }, { text: "No", click: function() { $( this ).dialog( "close" ); } } ]
		});

		placeOrderPopupDialog.dialog("open");
	 }
	});
	
}

function placeOrder(theForm) {
    
	ga('send', 'event', 'placeOrder', 'click', 'placeOrder', {'hitCallback':
     function () {
		//alert(theForm);
	
		var postdata = {};
	
		$(theForm).find(":input").each(function() {
			postdata[this.id] = this.value;
		});
	
		//postdata['cardnumber'] = theForm.elements['cardnumber'].value;
		//postdata['expdate'] = theForm.elements['expdate'].value;
	
		//alert(postdata['cardnumber']);
	
		$.post('./php/placeorder.php', postdata, function(placeOrderResponseTxt)
		{
			// do something here with the returnedData
			// alert(placeOrderResponseTxt);
			if(placeOrderResponseTxt == "1")
			{
				dialogTxt = "Your order has been placed!";
			}
			else
			{
				//alert(placeOrderResponseTxt);
				dialogTxt = placeOrderResponseTxt;
			}
		
			placeOrderDialog = $('<div id="dialogDiv" title="Order Status"><span id="placeOrderDialogMsg">'+dialogTxt+'</span></div>');
		
			if(placeOrderResponseTxt == "1")
			{
				placeOrderDialog.dialog({
					modal: true,
					draggable: false,
					resizable: false,
					width: 320,
					buttons: [ { text: "Awesome", click: function() { $( this ).dialog( "close" ); } } ],
					beforeClose: function() { deleteOrder(); }
				});
			}
			else
			{
				placeOrderDialog.dialog({
					modal: true,
					draggable: false,
					resizable: false,
					width: 320,
					buttons: [ { text: "Alright", click: function() { $( this ).dialog( "close" ); } } ]
				});
			}
	
			placeOrderDialog.dialog("open");
			placeOrderPopupDialog.dialog("close");
		});
	 }
	});
	
}

function accountSettings()
{
	ga('send', 'event', 'accountSettings', 'click', 'accountSettings', {'hitCallback':
     function () {
		var xmlhttp;
		xmlhttp = getXMLhttp();
		var i = $.cookie("i");
		var s = $.cookie("s");
		xmlhttp.open("POST","./php/accountsettings.php",true);
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
	});
}



function register()
{
	ga('send', 'event', 'register', 'click', 'register', {'hitCallback':
     function () {
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
		$.post('./php/register.php', postdata, function(responseTxt)
		{
			// do something here with the returnedData
			document.getElementById("registermessage").innerHTML=responseTxt;
			document.getElementById("registermessage").className="alert";
		});
	 }
	});
}

function registerPopup()
{
	ga('send', 'event', 'registerPopup', 'click', 'registerPopup', {'hitCallback':
     function () {
		var postdata = {};
		$.post('./php/mobileregisterpopup.php', postdata, function(responseTxt)
		{
			// do something here with the returnedData
			if(!registerPopupDialog)
			{
				registerPopupDialog = $('<div id="dialogDiv" title="Enter your information below to register for GottaNom.com!"><span id="dialogMsg">'+responseTxt+'</span></div>');
		
				registerPopupDialog.dialog({
					modal: true,
					draggable: false,
					resizable: false,
					width: 320
				});
			}
	
			//$('#dialogMsg').text("Are you sure you want to delete this category? Any subcategories or items inside it will be deleted as well.");
			registerPopupDialog.dialog("open");
		
			checkToEnable();
		});
	 }
	});
}

function hoursPopup(name,loc_id,rest_id)
{
	ga('send', 'event', 'hoursPopup', 'click', 'loc_id=' + loc_id + '&rest_id=' + rest_id, {'hitCallback':
     function () {
		//alert(1);
		name = decodeURIComponent(name);
		var postdata = {};
		$.post('./php/restauranthours.php?loc_id=' + loc_id + '&rest_id=' + rest_id, postdata, function(hoursResponseTxt)
		{
			//alert(2);
		
			hoursPopupDialog = $('<div id="dialogDiv" title="'+name+' Hours"><span id="dialogMsg">'+hoursResponseTxt+'</span></div>');
	
			hoursPopupDialog.dialog({
				modal: true,
				draggable: false,
				resizable: false
			});
	
			//$('#dialogMsg').text("Are you sure you want to delete this category? Any subcategories or items inside it will be deleted as well.");
			hoursPopupDialog.dialog("open");
		});
	 }
	});
}

function restaurantInfoPopup(name,loc_id,rest_id)
{
	ga('send', 'event', 'restaurantInfoPopup', 'click', 'loc_id=' + loc_id + '&rest_id=' + rest_id, {'hitCallback':
     function () {
		//alert(1);
		name = decodeURIComponent(name);
		var postdata = {};
		$.post('./php/restaurantinfopopup.php?loc_id=' + loc_id + '&rest_id=' + rest_id, postdata, function(restaurantInfoResponseTxt)
		{
			//alert(2);
		
			restaurantInfoPopupDialog = $('<div id="dialogDiv" title="'+name+' Info"><span id="dialogMsg">'+restaurantInfoResponseTxt+'</span></div>');
	
			restaurantInfoPopupDialog.dialog({
				modal: true,
				draggable: false,
				resizable: false
			});
	
			//$('#dialogMsg').text("Are you sure you want to delete this category? Any subcategories or items inside it will be deleted as well.");
			restaurantInfoPopupDialog.dialog("open");
		});
	 }
	});
}

function forgotPasswordPopup()
{
	ga('send', 'event', 'forgotPasswordPopup', 'click', 'forgotPasswordPopup', {'hitCallback':
     function () {
		var postdata = {};
		$.post('./php/mobileforgotpassworddiv.php', postdata, function(responseTxt)
		{
			var passwordPopupDialog = $('<div id="dialogDiv" title="Forgot Password!"><span id="dialogMsg">'+responseTxt+'</span></div>');
		
			passwordPopupDialog.dialog({
				modal: true,
				draggable: false,
				resizable: false,
				width: 320
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
	});
}

function forgotPassword()
{
	ga('send', 'event', 'forgotPassword', 'click', 'forgotPassword', {'hitCallback':
     function () {
		var postdata = {};
		postdata['username'] = document.getElementById('usernamebox').value;
		postdata['email'] = document.getElementById('emailbox').value;
		$.post('./php/forgotpassword.php', postdata, function(responseTxt)
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
	});
}

function forgotPasswordRestaurantPopup()
{
	ga('send', 'event', 'forgotPasswordRestaurantPopup', 'click', 'forgotPasswordRestaurantPopup', {'hitCallback':
     function () {
		var postdata = {};
		$.post('./php/forgotpasswordrestaurantdiv.php', postdata, function(responseTxt)
		{
			var passwordPopupRestaurantDialog = $('<div id="dialogDiv" title="Forgot Password!"><span id="dialogMsg">'+responseTxt+'</span></div>');
		
			passwordPopupRestaurantDialog.dialog({
				modal: true,
				draggable: false,
				resizable: false,
				width: 320
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
	});
}

function forgotPasswordRestaurant(theForm)
{
	ga('send', 'event', 'forgotPasswordRestaurant', 'click', 'forgotPasswordRestaurant', {'hitCallback':
     function () {
		var postdata = {};
		postdata['username'] = theForm.elements['usernamebox'].value;
		postdata['email'] = theForm.elements['emailbox'].value;
		$.post('./php/forgotpasswordrestaurant.php', postdata, function(responseTxt)
		{
			var passwordRestaurantDialog = $('<div id="dialogDiv" title="Forgot Password Alert!"><span id="dialogMsg">'+responseTxt+'</span></div>');
		
			passwordRestaurantDialog.dialog({
				modal: true,
				draggable: false,
				resizable: false,
				buttons: [ { text: "Thanks", click: function() { $( this ).dialog( "close" ); } } ],
				beforeClose: function() { location.reload(); }
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
	});
}

function registerRestaurant(theForm)
{
	ga('send', 'event', 'registerRestaurant', 'click', 'registerRestaurant', {'hitCallback':
     function () {
		var postdata = {};
		postdata['newusername'] = theForm.elements['newusername'].value;
		postdata['newrestaurantname'] = theForm.elements['newrestaurantname'].value;
		postdata['newemail'] = theForm.elements['newemail'].value;
		postdata['confirmemail'] = theForm.elements['confirmemail'].value;
		postdata['newphonenumber'] = theForm.elements['newphonenumber'].value;
		postdata['newfirstname'] = theForm.elements['newfirstname'].value;
		postdata['newlastname'] = theForm.elements['newlastname'].value;
		postdata['newaddress'] = theForm.elements['newaddress'].value;
		postdata['newcity'] = theForm.elements['newcity'].value;
		postdata['newstate'] = theForm.elements['newstate'].value;
		postdata['newzipcode'] = theForm.elements['newzipcode'].value;
		postdata['newpassword'] = theForm.elements['newpassword'].value;
		postdata['confirmpassword'] = theForm.elements['confirmpassword'].value;
		postdata['wedeliverchecked'] = theForm.elements["wedeliver"].checked;
		postdata['weallowpickupchecked'] = theForm.elements["weallowpickup"].checked;
		postdata['termsofusechecked'] = theForm.elements["termsofuse"].checked;
		$.post('./php/registerrestaurant.php', postdata, function(responseTxt)
		{
			var registerRestaurantDialog = $('<div id="dialogDiv" title="Registration Alert!"><span id="dialogMsg">'+responseTxt+'</span></div>');
		
			registerRestaurantDialog.dialog({
				modal: true,
				draggable: false,
				resizable: false,
				buttons: [ { text: "Thanks", click: function() { $( this ).dialog( "close" ); } } ],
				beforeClose: function() { location.reload(); }
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
	});
}

function registerUser(theForm)
{
	ga('send', 'event', 'registerUser', 'click', 'registerUser', {'hitCallback':
     function () {
		var postdata = {};
		postdata['newusername'] = theForm.elements['username'].value;
		postdata['newemail'] = theForm.elements['email'].value;
		postdata['confirmemail'] = theForm.elements['confirm_email'].value;
		postdata['newphonenumber'] = theForm.elements['phone_number'].value;
		postdata['newfirstname'] = theForm.elements['first_name'].value;
		postdata['newlastname'] = theForm.elements['last_name'].value;
		postdata['newpassword'] = theForm.elements['password'].value;
		postdata['confirmpassword'] = theForm.elements['confirm_password'].value;
		postdata['termsofusechecked'] = theForm.elements["terms_of_use"].checked;
		$.post('./php/registeruser.php', postdata, function(responseTxt)
		{
			registerDialog = $('<div id="dialogDiv" title="Registration Alert!"><span id="dialogMsg">'+responseTxt+'</span></div>');
		
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
	});
}

function reviewOrder()
{
	ga('send', 'event', 'reviewOrder', 'click', 'reviewOrder', {'hitCallback':
     function () {
		$('#topbar').load('./php/mobilerevieworderpage.php #top');
		$('#mainleft').load('./php/mobilerevieworderpage.php #main', function() {
			testFormPlaceOrder();
		});
	 }
	});
}

function setMain(pageaddress)
{
	ga('send', 'event', 'setMain', 'click', pageaddress, {'hitCallback':
     function () {
		$('#topbar').load('./php/' + pageaddress + ' #top');
		$('#mainbody').load('./php/' + pageaddress + ' #main');
	 }
	});
}

function setMobile(pageaddress)
{
	ga('send', 'event', 'setMobile', 'click', pageaddress, {'hitCallback':
     function () {
		$('#topbar').load('./php/' + pageaddress + ' #top');
		$('#mainleft').load('./php/' + pageaddress + ' #main');
	 }
	});
}

/*
function openPage(page)
{
	window.open(page);
}
*/

var openPage = function(url) {
   ga('send', 'event', 'outbound', 'click', url, {'hitCallback':
     function () {
     	window.open(url);
     }
   });
}

function setSide(pageaddress)
{
	ga('send', 'event', 'setSide', 'click', pageaddress, {'hitCallback':
     function () {
		$('#mainleft').load('./php/' + pageaddress);
	 }
	});
}

function hideSide()
{
	ga('send', 'event', 'hideSide', 'click', 'hideSide', {'hitCallback':
     function () {
		$('#mainleft').hide();
	 }
	});
}

function showSide()
{
	ga('send', 'event', 'showSide', 'click', 'showSide', {'hitCallback':
     function () {
		$('#mainleft').show();
	 }
	});
}

function setTopLogin(pageaddress)
{
	ga('send', 'event', 'switchToCustomer', 'click', 'switchToCustomer', {'hitCallback':
     function () {
		var xmlhttp;
		xmlhttp = getXMLhttp();
		xmlhttp.open("POST","./php/" + pageaddress,true);
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
	});
}

function switchToCustomer()
{
	ga('send', 'event', 'switchToCustomer', 'click', 'switchToCustomer', {'hitCallback':
     function () {
		$.cookie("restaurant", null, { domain: '.gottanom.com'});
		changeURLVariable("restaurant=false");
		location.reload();
	 }
	});
}

function switchToRestaurant()
{
	ga('send', 'event', 'switchToRestaurant', 'click', 'switchToRestaurant', {'hitCallback':
     function () {
		$.cookie("restaurant", 1, { expires: 3*365, domain: '.gottanom.com'});
		changeURLVariable("restaurant=true");
		location.reload();
	 }
	});
}

function testFormPlaceOrder()
{
	theForm = document.getElementById('placeorderform');
	
	//testDeliveryPlaceOrder(theForm.elements['delivery']);
	testNameOnCardPlaceOrder(theForm.elements['nameoncard']);
	//testEmailPlaceOrder(theForm.elements['email']);
	testBillAddressPlaceOrder(theForm.elements['billaddress']);
	//testPhoneNumberPlaceOrder(theForm.elements['phonenumber']);
	testBillCityPlaceOrder(theForm.elements['billcity']);
	//testFirstNamePlaceOrder(theForm.elements['firstname']);
	testBillStatePlaceOrder(theForm.elements['billstate']);
	//testLastNamePlaceOrder(theForm.elements['lastname']);
	testBillZipCodePlaceOrder(theForm.elements['billzipcode']);
	//testAddressPlaceOrder(theForm.elements['deliveryaddress']);
	testCardTypePlaceOrder(theForm.elements['cardtype']);
	//testCityPlaceOrder(theForm.elements['deliverycity']);
	testCardNumberPlaceOrder(theForm.elements['cardnumber']);
	//testStatePlaceOrder(theForm.elements['deliverystate']);
	testConfirmCardNumberPlaceOrder(theForm.elements['confirmnumber']);
	//testZipCodePlaceOrder(theForm.elements['deliveryzipcode']);
	testExpDatePlaceOrder(theForm.elements['expdate']);
}

function testFormQuantity(formID,quantity)
{
	//alert(quantity);
	if(quantity)
	{
		theForm = document.getElementById(formID);
	
		$(theForm).find(":input[type=submit]").each(function() {
			this.disabled = true;
		});
	
		var i = 0;
		var sum = 0;
		var product = 0;
		$(theForm).find(":input[type=number]").each(function() {
			if(i==0)
			{
				product = quantity * parseInt(this.value);
				i = 1;
			}
			else
			{
				if(!isNaN(parseFloat(this.value)) && isFinite(this.value))
				{
					sum += parseInt(this.value);
				}
				//alert(sum);
			}
		});
	
		//alert(product + '==' + sum);
		//alert('sum='+sum);
		//alert('product='+product);
	
		if(sum == product)
		{
			$(theForm).find(":input[type=submit]").each(function() {
				this.disabled = false;
			});
			$('#quantity_test').html(" ");
			$('#quantity_test').attr('class','optional');
		}
		else if (sum < product)
		{
			$('#quantity_test').html("You must add "+(product-sum)+" more items.");
			$('#quantity_test').attr('class','test');
		}
		else if (sum > product)
		{
			$('#quantity_test').html("You must remove "+(sum-product)+" items.");
			$('#quantity_test').attr('class','test');
		}
	}
}

function testForm(formID)
{
	theForm = document.getElementById(formID);
	
	//alert(formID);
	
	testDeliveryPlaceOrder(theForm.elements['delivery']);
	testEmailPlaceOrder(theForm.elements['email']);
	testPhoneNumberPlaceOrder(theForm.elements['phonenumber']);
	testFirstNamePlaceOrder(theForm.elements['firstname']);
	testLastNamePlaceOrder(theForm.elements['lastname']);
	testAddressPlaceOrder(theForm.elements['deliveryaddress']);
	testCityPlaceOrder(theForm.elements['deliverycity']);
	testStatePlaceOrder(theForm.elements['deliverystate']);
	testZipCodePlaceOrder(theForm.elements['deliveryzipcode']);
}

function checkForm(formID)
{
	$form = $(formID);
	$form.find('input, textarea, select').each(function()
	{
		//alert(this.id);
		$(this).keyup();
		$(this).change();
		//alert(this.id);
	});
}

function testFormCurrentAddress()
{
	
	//alert('testFormCurrentAddress');
	testAddress($('#current_address')[0]);
	testCity($('#current_city')[0]);
	testState($('#current_state')[0]);
	testZip($('#current_zip')[0]);
	
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

function testAddress(theInput)
{
	var inputText = theInput.value;
	var testID = "#" + theInput.id + "_test";
	var theForm = $(theInput).closest('form');
	var theTable = $(theInput).closest('table');
	var onBeach = $(':input:eq(' + ($(':input').index(theInput) - 1) + ')').prop('checked');
	
	if(onBeach)
	{
		if (inputText.length == 0)
		{
			theTable.find(testID).html("Cross Street");
			theTable.find(testID).attr("class", "test");
			theForm.find(":input[type=submit]").each(function() {
				this.disabled = true;
			});
		}
		else if (inputText.length >= 3)
		{
			theTable.find(testID).html("Alright");
			theTable.find(testID).attr("class", "optional");
			checkToEnableNew(theForm);
		}
		else
		{
			theTable.find(testID).html("Give us more");
			theTable.find(testID).attr("class", "test");
			theForm.find(":input[type=submit]").each(function() {
				this.disabled = true;
			});
		}
	}
	else
	{
		if (inputText.length == 0)
		{
			theTable.find(testID).html("Street Address");
			theTable.find(testID).attr("class", "test");
			theForm.find(":input[type=submit]").each(function() {
				this.disabled = true;
			});
		}
		else if (validateAddress(inputText))
		{
			theTable.find(testID).html("Alright");
			theTable.find(testID).attr("class", "optional");
			checkToEnableNew(theForm);
		}
		else
		{
			theTable.find(testID).html("Invalid Address");
			theTable.find(testID).attr("class", "test");
			theForm.find(":input[type=submit]").each(function() {
				this.disabled = true;
			});
		}
	}
}

function testOnBeach(theCheckbox)
{
	//alert('testOnBeach');
	
	var $checkbox = $(theCheckbox);
	var theForm = $checkbox.closest('form');
	var theTable = $checkbox.closest('table');
	var onBeach = $checkbox.prop('checked');
	
	var $input = $(':input:eq(' + ($(':input').index(theCheckbox) + 1) + ')');
	var inputText = $input.prop('value');
	var testID = "#" + $input.prop('id') + "_test";
	
	if(onBeach)
	{
		if (inputText.length == 0)
		{
			theTable.find(testID).html("Cross Street");
			theTable.find(testID).attr("class", "test");
			theForm.find(":input[type=submit]").each(function() {
				this.disabled = true;
			});
		}
		else if (inputText.length >= 3)
		{
			theTable.find(testID).html("Alright");
			theTable.find(testID).attr("class", "optional");
			checkToEnableNew(theForm);
		}
		else
		{
			theTable.find(testID).html("Give us more");
			theTable.find(testID).attr("class", "test");
			theForm.find(":input[type=submit]").each(function() {
				this.disabled = true;
			});
		}
	}
	else
	{
		if (inputText.length == 0)
		{
			theTable.find(testID).html("Street Address");
			theTable.find(testID).attr("class", "test");
			theForm.find(":input[type=submit]").each(function() {
				this.disabled = true;
			});
		}
		else if (validateAddress(inputText))
		{
			theTable.find(testID).html("Alright");
			theTable.find(testID).attr("class", "optional");
			checkToEnableNew(theForm);
		}
		else
		{
			theTable.find(testID).html("Invalid Address");
			theTable.find(testID).attr("class", "test");
			theForm.find(":input[type=submit]").each(function() {
				this.disabled = true;
			});
		}
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
	
	var deliveryorpickup = document.getElementById("delivery").value;
	
	if(deliveryorpickup == "pickup")
	{
		document.getElementById(id).className = "optional";
	}
}

function testAddressPlaceOrder(theInput)
{
	var inputText = theInput.value;
	var testID = "#" + theInput.id + "test";
	var theForm = theInput.form;
	var theTable = $(theInput).closest('table');
	
	if (inputText.length == 0)
	{
		theTable.find(testID).html("Street Address");
		theTable.find(testID).attr("class", "test");
		$(theForm).find(":input[type=submit]").each(function() {
			this.disabled = true;
		});
	}
	else if (validateAddress(inputText))
	{
		theTable.find(testID).html("Alright");
		theTable.find(testID).attr("class", "optional");
		checkToEnablePlaceOrder(theForm);
	}
	else
	{
		theTable.find(testID).html("Invalid Address");
		theTable.find(testID).attr("class", "test");
		$(theForm).find(":input[type=submit]").each(function() {
			this.disabled = true;
		});
	}
}

function testBillAddressPlaceOrder(theInput)
{
	var inputText = theInput.value;
	var testID = "#" + theInput.id + "test";
	var theForm = theInput.form;
	var theTable = $(theInput).closest('table');
	
	if (inputText.length == 0)
	{
		theTable.find(testID).html("Billing Address");
		theTable.find(testID).attr("class", "test");
		$(theForm).find(":input[type=submit]").each(function() {
			this.disabled = true;
		});
	}
	else if (validateAddress(inputText))
	{
		theTable.find(testID).html("Works for me");
		theTable.find(testID).attr("class", "optional");
		checkToEnablePlaceOrder(theForm);
	}
	else
	{
		theTable.find(testID).html("Invalid Address");
		theTable.find(testID).attr("class", "test");
		$(theForm).find(":input[type=submit]").each(function() {
			this.disabled = true;
		});
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

function testBillAddressUpdate()
{
	var id = "billaddresstest";
	var addresstext = document.getElementById('newbilladdress').value;
	
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

function testConfirmCardNumberPlaceOrder(theInput)
{
	//alert('testConfirmCardNumberPlaceOrder(theInput)');
	var inputText = theInput.value;
	var inputDigits = inputText.replace(/ /g,"");
	var testID = "#" + theInput.id + "test";
	var theForm = theInput.form;
	var theTable = $(theInput).closest('table');
	var cardNumber = theForm.elements['cardnumber'].value;
	var cardDigits = cardNumber.replace(/ /g,"");
	//alert(inputDigits);
	
	if (inputText.length == 0)
	{
		theTable.find(testID).html("Confirm Card Number");
		theTable.find(testID).attr("class", "test");
		$(theForm).find(":input[type=submit]").each(function() {
			this.disabled = true;
		});
	}
	else if (inputDigits != cardDigits)
	{
		theTable.find(testID).html("Numbers Don't Match");
		theTable.find(testID).attr("class", "test");
		$(theForm).find(":input[type=submit]").each(function() {
			this.disabled = true;
		});
	}
	else
	{
		theTable.find(testID).html("Card Numbers Match");
		theTable.find(testID).attr("class", "optional");
		checkToEnablePlaceOrder(theForm);
	}
}

function testExpDatePlaceOrder(theInput)
{
	var inputText = theInput.value;
	var testID = "#" + theInput.id + "test";
	var theForm = theInput.form;
	var theTable = $(theInput).closest('table');
	
	if (inputText.length == 0)
	{
		theTable.find(testID).html("Exp. Date");
		theTable.find(testID).attr("class", "test");
		$(theForm).find(":input[type=submit]").each(function() {
			this.disabled = true;
		});
	}
	else if (validateExpDate(inputText))
	{
		theTable.find(testID).html("Good Date");
		theTable.find(testID).attr("class", "optional");
		checkToEnablePlaceOrder(theForm);
	}
	else
	{
		theTable.find(testID).html("Invalid Date");
		theTable.find(testID).attr("class", "test");
		$(theForm).find(":input[type=submit]").each(function() {
			this.disabled = true;
		});
	}
}

function validateExpDate(dateString)
{
	//alert(dateString);
	
	var dateArray = dateString.split("-");
	var date = new Date(dateArray[0],dateArray[1]);
	//alert(dateArray);
	var currentDate = new Date();
	
	//alert(date);
	
	if (currentDate > date)
	{
		return 0;
	}
	else
	{
		return 1;
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

function testCardTypePlaceOrder(theInput)
{
	var inputText = theInput.value;
	var testID = "#" + theInput.id + "test";
	var theForm = theInput.form;
	var theTable = $(theInput).closest('table');
	
	if (inputText)
	{
		theTable.find(testID).html("Nice card");
		theTable.find(testID).attr("class", "optional");
		checkToEnablePlaceOrder(theForm);
	}
	else
	{
		theTable.find(testID).html("Credit Card Type");
		theTable.find(testID).attr("class", "test");
		$(theForm).find(":input[type=submit]").each(function() {
			this.disabled = true;
		});
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

function testCity(theInput)
{
	var inputText = theInput.value;
	var testID = "#" + theInput.id + "_test";
	var theForm = $(theInput).closest('form');
	var theTable = $(theInput).closest('table');
	
	if (inputText.length == 0)
	{
		theTable.find(testID).html("City");
		theTable.find(testID).attr("class", "test");
		theForm.find(":input[type=submit]").each(function() {
			this.disabled = true;
		});
	}
	else if (validateCity(inputText))
	{
		theTable.find(testID).html("Good");
		theTable.find(testID).attr("class", "optional");
		checkToEnableNew(theForm);
	}
	else
	{
		theTable.find(testID).html("Invalid City");
		theTable.find(testID).attr("class", "test");
		theForm.find(":input[type=submit]").each(function() {
			this.disabled = true;
		});
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
	
	var deliveryorpickup = document.getElementById("delivery").value;
	
	if(deliveryorpickup == "pickup")
	{
		document.getElementById(id).className = "optional";
	}
}

function testCityPlaceOrder(theInput)
{
	var inputText = theInput.value;
	var testID = "#" + theInput.id + "test";
	var theForm = theInput.form;
	var theTable = $(theInput).closest('table');
	
	if (inputText.length == 0)
	{
		theTable.find(testID).html("City");
		theTable.find(testID).attr("class", "test");
		$(theForm).find(":input[type=submit]").each(function() {
			this.disabled = true;
		});
	}
	else if (validateCity(inputText))
	{
		theTable.find(testID).html("Good");
		theTable.find(testID).attr("class", "optional");
		checkToEnablePlaceOrder(theForm);
	}
	else
	{
		theTable.find(testID).html("Invalid City");
		theTable.find(testID).attr("class", "test");
		$(theForm).find(":input[type=submit]").each(function() {
			this.disabled = true;
		});
	}
}

function testBillCityPlaceOrder(theInput)
{
	var inputText = theInput.value;
	var testID = "#" + theInput.id + "test";
	var theForm = theInput.form;
	var theTable = $(theInput).closest('table');
	
	if (inputText.length == 0)
	{
		theTable.find(testID).html("Billing City");
		theTable.find(testID).attr("class", "test");
		$(theForm).find(":input[type=submit]").each(function() {
			this.disabled = true;
		});
	}
	else if (validateCity(inputText))
	{
		theTable.find(testID).html("Wonderful");
		theTable.find(testID).attr("class", "optional");
		checkToEnablePlaceOrder(theForm);
	}
	else
	{
		theTable.find(testID).html("No good");
		theTable.find(testID).attr("class", "test");
		$(theForm).find(":input[type=submit]").each(function() {
			this.disabled = true;
		});
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

function testBillCityUpdate()
{
	var id = "billcitytest";
	var citytext = document.getElementById('newbillcity').value;
	
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

function testEmail(theInput)
{
	//alert('testEmail');
	
	var emailtext = theInput.value;
	var testID = "#" + theInput.id + "_test";
	var theForm = $(theInput).closest('form');
	var theTable = $(theInput).closest('table');
	
	//alert(emailtext + " " + testID);
	
	if (emailtext.length == 0)
	{
		theTable.find(testID).html("Email Address");
		theTable.find(testID).attr("class", "test");
		theForm.find(":input[type=submit]").each(function() {
			this.disabled = true;
		});
	}
	else if (validateEmail(emailtext))
	{
		theTable.find(testID).html("Valid email");
		theTable.find(testID).attr("class", "optional");
		checkToEnableNew(theForm);
	}
	else
	{
		theTable.find(testID).html("Invalid email");
		theTable.find(testID).attr("class", "test");
		theForm.find(":input[type=submit]").each(function() {
			this.disabled = true;
		});
	}
	if(confirm_email = $('#confirm_email')[0])
	{
		testConfirmEmail(confirm_email);
	}
}

function testConfirmEmail(theInput)
{
	//alert('testEmailPlaceOrder');
	
	var confirmemailtext = theInput.value;
	var testID = "#" + theInput.id + "_test";
	var theForm = $(theInput).closest('form');
	var theTable = $(theInput).closest('table');
	var emailtext = theForm.find('#email').val();
	
	//alert(emailtext + " " + confirmemailtext);
	
	if (confirmemailtext.length == 0)
	{
		theTable.find(testID).html("Confirm Email");
		theTable.find(testID).attr("class", "test");
		theForm.find(":input[type=submit]").each(function() {
			this.disabled = true;
		});
	}
	else if (confirmemailtext == emailtext)
	{
		theTable.find(testID).html("Emails Match");
		theTable.find(testID).attr("class", "optional");
		checkToEnableNew(theForm);
	}
	else
	{
		theTable.find(testID).html("Emails Must Match");
		theTable.find(testID).attr("class", "test");
		theForm.find(":input[type=submit]").each(function() {
			this.disabled = true;
		});
	}
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

function testDeliveryPlaceOrder(theInput)
{
	//alert('testDeliveryPlaceOrder(theInput)');
	
	var deliveryvalue = theInput.value;
	var testID = theInput.id + "test";
	var theForm = theInput.form;
	var theTable = $(theInput).closest('table');
	
	if (deliveryvalue == 'delivery')
	{
		theForm.elements['deliveryaddress'].disabled = false;
		testAddressPlaceOrder(theForm.elements['deliveryaddress']);
		theForm.elements['deliverycity'].disabled = false;
		testCityPlaceOrder(theForm.elements['deliverycity']);
		theForm.elements['deliverystate'].disabled = false;
		testStatePlaceOrder(theForm.elements['deliverystate']);
		theForm.elements['deliveryzipcode'].disabled = false;
		testZipCodePlaceOrder(theForm.elements['deliveryzipcode']);
	}
	else if (deliveryvalue == 'pickup')
	{
		//theForm.elements['deliveryaddress'].disabled = true;
		theTable.find('#deliveryaddresstest').attr("class", "optional");
		//theForm.elements['deliverycity'].disabled = true;
		theTable.find('#deliverycitytest').attr("class", "optional");
		//theForm.elements['deliverystate'].disabled = true;
		theTable.find('#deliverystatetest').attr("class", "optional");
		//theForm.elements['deliveryzipcode'].disabled = true;
		theTable.find('#deliveryzipcodetest').attr("class", "optional");
	}
}

function testEmailPlaceOrder(theInput)
{
	//alert('testEmailPlaceOrder');
	
	var emailtext = theInput.value;
	var testID = "#" + theInput.id + "test";
	var theForm = theInput.form;
	var theTable = $(theInput).closest('table');
	
	//alert(emailtext + " " + testID);
	
	if (emailtext.length == 0)
	{
		theTable.find(testID).html("Email Address");
		theTable.find(testID).attr("class", "test");
		$(theForm).find(":input[type=submit]").each(function() {
			this.disabled = true;
		});
	}
	else if (validateEmail(emailtext))
	{
		theTable.find(testID).html("Valid email");
		theTable.find(testID).attr("class", "optional");
		checkToEnablePlaceOrder(theForm);
	}
	else
	{
		theTable.find(testID).html("Invalid email");
		theTable.find(testID).attr("class", "test");
		$(theForm).find(":input[type=submit]").each(function() {
			this.disabled = true;
		});
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

function testFirstName(theInput)
{
	var inputText = theInput.value;
	var testID = "#" + theInput.id + "_test";
	var theForm = $(theInput).closest('form');
	var theTable = $(theInput).closest('table');
	
	if (inputText.length >= 1)
	{
		theTable.find(testID).html("Nice Name");
		theTable.find(testID).attr("class", "optional");
		checkToEnableNew(theForm);
	}
	else
	{
		theTable.find(testID).html("First Name");
		theTable.find(testID).attr("class", "test");
		theForm.find(":input[type=submit]").each(function() {
			this.disabled = true;
		});
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

function testFirstNamePlaceOrder(theInput)
{
	var inputText = theInput.value;
	var testID = "#" + theInput.id + "test";
	var theForm = theInput.form;
	var theTable = $(theInput).closest('table');
	
	if (inputText.length >= 1)
	{
		theTable.find(testID).html("Nice Name");
		theTable.find(testID).attr("class", "optional");
		checkToEnablePlaceOrder(theForm);
	}
	else
	{
		theTable.find(testID).html("First Name");
		theTable.find(testID).attr("class", "test");
		$(theForm).find(":input[type=submit]").each(function() {
			this.disabled = true;
		});
	}
}

function testNameOnCardPlaceOrder(theInput)
{
	var inputText = theInput.value;
	var testID = "#" + theInput.id + "test";
	var theForm = theInput.form;
	var theTable = $(theInput).closest('table');
	
	if (inputText.length >= 1)
	{
		theTable.find(testID).html("Cool");
		theTable.find(testID).attr("class", "optional");
		checkToEnablePlaceOrder(theForm);
	}
	else
	{
		theTable.find(testID).html("Name On Card");
		theTable.find(testID).attr("class", "test");
		$(theForm).find(":input[type=submit]").each(function() {
			this.disabled = true;
		});
	}
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

function testBillNameUpdate()
{
	var id = "billnametest";
	var firstnametext = document.getElementById('newbillname').value;
	
	if (firstnametext.length >= 1)
	{
		document.getElementById(id).innerHTML = "Look At That Name!";
		document.getElementById(id).className = "optional";
	}
	else
	{
		document.getElementById(id).innerHTML = "Name On Card";
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

function testLastNamePlaceOrder(theInput)
{
	var inputText = theInput.value;
	var testID = "#" + theInput.id + "test";
	var theForm = theInput.form;
	var theTable = $(theInput).closest('table');
	
	if (inputText.length >= 1)
	{
		theTable.find(testID).html("Sweet Name");
		theTable.find(testID).attr("class", "optional");
		checkToEnablePlaceOrder(theForm);
	}
	else
	{
		theTable.find(testID).html("Last Name");
		theTable.find(testID).attr("class", "test");
		$(theForm).find(":input[type=submit]").each(function() {
			this.disabled = true;
		});
	}
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

function testLastName(theInput)
{
	var inputText = theInput.value;
	var testID = "#" + theInput.id + "_test";
	var theForm = $(theInput).closest('form');
	var theTable = $(theInput).closest('table');
	
	if (inputText.length >= 1)
	{
		theTable.find(testID).html("Sweet Name");
		theTable.find(testID).attr("class", "optional");
		checkToEnableNew(theForm);
	}
	else
	{
		theTable.find(testID).html("Last Name");
		theTable.find(testID).attr("class", "test");
		theForm.find(":input[type=submit]").each(function() {
			this.disabled = true;
		});
	}
}

function testNewOrder()
{
	var deliveryorpickup = document.getElementById("delivery").value;
	
	testEmailNewOrder();
	testPhoneNumberNewOrder();
	testFirstNameNewOrder();
	testLastNameNewOrder();
	
	//if(deliveryorpickup == "delivery")
	//{
		testAddressNewOrder();
		testCityNewOrder();
		testStateNewOrder();
		testZipCodeNewOrder();
	//}
	
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

function testPassword(theInput)
{
	var inputText = theInput.value;
	var testID = "#" + theInput.id + "_test";
	var theForm = $(theInput).closest('form');
	var theTable = $(theInput).closest('table');
	
	if (inputText.length >= 1)
	{
		theTable.find(testID).html("Cool");
		theTable.find(testID).attr("class", "optional");
		checkToEnableNew(theForm);
	}
	else
	{
		theTable.find(testID).html("Password");
		theTable.find(testID).attr("class", "test");
		theForm.find(":input[type=submit]").each(function() {
			this.disabled = true;
		});
	}
	
	testConfirmPassword($('#confirm_password')[0]);
}

function testConfirmPassword(theInput)
{
	//alert('testEmailPlaceOrder');
	
	var confirmpasswordtext = theInput.value;
	var testID = "#" + theInput.id + "_test";
	var theForm = $(theInput).closest('form');
	var theTable = $(theInput).closest('table');
	var passwordtext = theForm.find('#password').val();
	
	//alert(emailtext + " " + confirmemailtext);
	
	if (confirmpasswordtext.length == 0)
	{
		theTable.find(testID).html("Confirm Password");
		theTable.find(testID).attr("class", "test");
		theForm.find(":input[type=submit]").each(function() {
			this.disabled = true;
		});
	}
	else if (confirmpasswordtext == passwordtext)
	{
		theTable.find(testID).html("Passwords Match");
		theTable.find(testID).attr("class", "optional");
		checkToEnableNew(theForm);
	}
	else
	{
		theTable.find(testID).html("Passwords Must Match");
		theTable.find(testID).attr("class", "test");
		theForm.find(":input[type=submit]").each(function() {
			this.disabled = true;
		});
	}
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

function testPhoneNumberPlaceOrder(theInput)
{
	var phonenumbertext = theInput.value;
	var testID = "#" + theInput.id + "test";
	var phonenumberlength = phonenumbertext.length;
	var theForm = theInput.form;
	var theTable = $(theInput).closest('table');
	
	switch(phonenumberlength)
	{
		case 0:
			theTable.find(testID).html("Phone Number");
			theTable.find(testID).attr("class", "test");
			$(theForm).find(":input[type=submit]").each(function() {
				this.disabled = true;
			});
			break;
		default:
			var phonenumberisvalid = validatePhoneNumberPlaceOrder(phonenumbertext);
			if (phonenumberisvalid)
			{
				theTable.find(testID).html("Good Number");
				theTable.find(testID).attr("class", "optional");
				var digits = phonenumbertext.replace(/\D/g, "");
				var numbertext = digits.slice(0,3)+"-"+digits.slice(3,6)+"-"+digits.slice(6,10);
				theInput.value = numbertext;
				checkToEnablePlaceOrder();
			}
			else
			{
				theTable.find(testID).html("Invalid phone number");
				theTable.find(testID).attr("class", "test");
				checkToEnablePlaceOrder();
			}
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

function testPhoneNumber(theInput)
{
	var phonenumbertext = theInput.value;
	var testID = "#" + theInput.id + "_test";
	var phonenumberlength = phonenumbertext.length;
	var theForm = $(theInput).closest('form');
	var theTable = $(theInput).closest('table');
	
	switch(phonenumberlength)
	{
		case 0:
			theTable.find(testID).html("Phone Number");
			theTable.find(testID).attr("class", "test");
			theForm.find(":input[type=submit]").each(function() {
				this.disabled = true;
			});
			break;
		default:
			var phonenumberisvalid = validatePhoneNumberPlaceOrder(phonenumbertext);
			if (phonenumberisvalid)
			{
				theTable.find(testID).html("Good Number");
				theTable.find(testID).attr("class", "optional");
				var digits = phonenumbertext.replace(/\D/g, "");
				var numbertext = digits.slice(0,3)+"-"+digits.slice(3,6)+"-"+digits.slice(6,10);
				theInput.value = numbertext;
				checkToEnableNew(theForm);
			}
			else
			{
				theTable.find(testID).html("Invalid phone number");
				theTable.find(testID).attr("class", "test");
				checkToEnableNew(theForm);
			}
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

function testState(theInput)
{
	var inputText = theInput.value;
	var testID = "#" + theInput.id + "_test";
	var theForm = $(theInput).closest('form');
	var theTable = $(theInput).closest('table');
	
	if (validateState(inputText))
	{
		theTable.find(testID).html("Well done");
		theTable.find(testID).attr("class", "optional");
		checkToEnableNew(theForm);
	}
	else
	{
		theTable.find(testID).html("State");
		theTable.find(testID).attr("class", "test");
		theForm.find(":input[type=submit]").each(function() {
			this.disabled = true;
		});
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
	
	var deliveryorpickup = document.getElementById("delivery").value;
	
	if(deliveryorpickup == "pickup")
	{
		document.getElementById(id).className = "optional";
	}
}

function testStatePlaceOrder(theInput)
{
	var inputText = theInput.value;
	var testID = "#" + theInput.id + "test";
	var theForm = theInput.form;
	var theTable = $(theInput).closest('table');
	
	if (validateState(inputText))
	{
		theTable.find(testID).html("Well done");
		theTable.find(testID).attr("class", "optional");
		checkToEnablePlaceOrder(theForm);
	}
	else
	{
		theTable.find(testID).html("State");
		theTable.find(testID).attr("class", "test");
		$(theForm).find(":input[type=submit]").each(function() {
			this.disabled = true;
		});
	}
}

function testBillStatePlaceOrder(theInput)
{
	var inputText = theInput.value;
	var testID = "#" + theInput.id + "test";
	var theForm = theInput.form;
	var theTable = $(theInput).closest('table');
	
	if (validateState(inputText))
	{
		theTable.find(testID).html("Well done");
		theTable.find(testID).attr("class", "optional");
		checkToEnablePlaceOrder(theForm);
	}
	else
	{
		theTable.find(testID).html("Billing State");
		theTable.find(testID).attr("class", "test");
		$(theForm).find(":input[type=submit]").each(function() {
			this.disabled = true;
		});
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

function testBillStateUpdate()
{
	var id = "billstatetest";
	var statetext = document.getElementById('newbillstate').value;
	
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

function testTermsOfUse(theInput)
{
	var termsofusechecked = theInput.checked;
	var testID = "#" + theInput.id + "_test";
	var theForm = $(theInput).closest('form');
	var theTable = $(theInput).closest('table');
	
	if (termsofusechecked)
	{
		theTable.find(testID).attr("class", "optional");
		checkToEnableNew(theForm);
	}
	else
	{
		theTable.find(testID).attr("class", "test");
		theForm.find(":input[type=submit]").each(function() {
			this.disabled = true;
		});
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

function testUsername(theInput)
{
	var inputText = theInput.value;
	var testID = "#" + theInput.id + "_test";
	var theForm = $(theInput).closest('form');
	var theTable = $(theInput).closest('table');
	
	if (inputText.length == 0)
	{
		theTable.find(testID).html("Username");
		theTable.find(testID).attr("class", "test");
		theForm.find(":input[type=submit]").each(function() {
			this.disabled = true;
		});
	}
	else if (validateUsernameNew(inputText))
	{
		theTable.find(testID).html("Hey there");
		theTable.find(testID).attr("class", "optional");
		checkToEnableNew(theForm);
	}
	else
	{
		theTable.find(testID).html("Invalid Username");
		theTable.find(testID).attr("class", "test");
		theForm.find(":input[type=submit]").each(function() {
			this.disabled = true;
		});
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

function testZipCodePlaceOrder(theInput)
{
	var inputText = theInput.value;
	var testID = "#" + theInput.id + "test";
	var theForm = theInput.form;
	var theTable = $(theInput).closest('table');
	
	//alert(inputText);
	
    if (validateZipCodePlaceOrder(inputText))
    {
		//alert('validated');
		
		theTable.find(testID).html("Good Zip Code");
		theTable.find(testID).attr("class", "optional");
		
		//alert('checkToEnable');
		
		checkToEnablePlaceOrder(theForm);
    }
    else
    {
		//alert('not validated');
		
		theTable.find(testID).html("Zip Code");
		theTable.find(testID).attr("class", "test");
		$(theForm).find(":input[type=submit]").each(function() {
			this.disabled = true;
		});
    }
}

function testZip(theInput)
{
	var inputText = theInput.value;
	var testID = "#" + theInput.id + "_test";
	var theForm = $(theInput).closest('form');
	var theTable = $(theInput).closest('table');
	
	//alert(inputText);
	
    if (validateZip(inputText))
    {
		//alert('validated');
		
		theTable.find(testID).html("Good Zip Code");
		theTable.find(testID).attr("class", "optional");
		
		//alert('checkToEnable');
		
		checkToEnableNew(theForm);
    }
    else
    {
		//alert('not validated');
		
		theTable.find(testID).html("Zip Code");
		theTable.find(testID).attr("class", "test");
		theForm.find(":input[type=submit]").each(function() {
			this.disabled = true;
		});
    }
}

function testCardNumberPlaceOrder(theInput)
{
	var inputText = theInput.value;
	var testID = "#" + theInput.id + "test";
	var theForm = theInput.form;
	var theTable = $(theInput).closest('table');
	//alert(inputText);
	var cardType = theForm.elements['cardtype'].value;
	//alert(cardType);
	
    if (validateCardNumber(inputText,cardType))
    {
		theTable.find(testID).html("Nice card number");
		theTable.find(testID).attr("class", "optional");
		checkToEnablePlaceOrder(theForm);
    }
    else
    {
		theTable.find(testID).html("Credit Card Number");
		theTable.find(testID).attr("class", "test");
		$(theForm).find(":input[type=submit]").each(function() {
			this.disabled = true;
		});
    }
}

function validateCardNumber(cardNumber,cardType)
{
    if (cardType == 'Amex')
    {
    	var cardMatch = /^\d{15}$/;
    }
    else
    {
    	var cardMatch = /^\d{16}$/;
    }
    
	var digits = cardNumber.replace(/ /g,"");
	
    if (digits.match(cardMatch) !== null)
    {
    	return 1;
    }
    else
    {
    	return 0;
    }
}

function testBillZipCodePlaceOrder(theInput)
{
	var inputText = theInput.value;
	var testID = "#" + theInput.id + "test";
	var theForm = theInput.form;
	var theTable = $(theInput).closest('table');
	
    if (validateZipCodePlaceOrder(inputText))
    {
		theTable.find(testID).html("Nice Zip Code");
		theTable.find(testID).attr("class", "optional");
		checkToEnablePlaceOrder(theForm);
    }
    else
    {
		theTable.find(testID).html("Billing Zip Code");
		theTable.find(testID).attr("class", "test");
		$(theForm).find(":input[type=submit]").each(function() {
			this.disabled = true;
		});
    }
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

function testBillZipCodeUpdate()
{
	var id = "billzipcodetest";
	var zipcodetext = document.getElementById('newbillzipcode').value;
	
	if(zipcodetext.length == 0)
	{
		document.getElementById(id).innerHTML = "Zip Code";
		document.getElementById(id).className = "optional";
		checkToEnable();
	}
	else
	{
		validateBillZipCode(zipcodetext);
	}
}

function updateInfo(theForm)
{
	ga('send', 'event', 'updateInfo', 'click', 'updateInfo', {'hitCallback':
     function () {
		var postdata = {};
		postdata['newusername'] = theForm.elements['newusername'].value;
		postdata['newemail'] = theForm.elements['newemail'].value;
		postdata['confirmemail'] = theForm.elements['confirmemail'].value;
		postdata['newphonenumber'] = theForm.elements['newphonenumber'].value;
		postdata['newfirstname'] = theForm.elements['newfirstname'].value;
		postdata['newlastname'] = theForm.elements['newlastname'].value;
		postdata['newdeliverypreference'] = theForm.elements['newdeliverypreference'].value;
		postdata['newaddress'] = theForm.elements['newaddress'].value;
		postdata['newcity'] = theForm.elements['newcity'].value;
		postdata['newstate'] = theForm.elements['newstate'].value;
		postdata['newzipcode'] = theForm.elements['newzipcode'].value;
		postdata['newbillname'] = theForm.elements['newbillname'].value;
		postdata['newbilladdress'] = theForm.elements['newbilladdress'].value;
		postdata['newbillcity'] = theForm.elements['newbillcity'].value;
		postdata['newbillstate'] = theForm.elements['newbillstate'].value;
		postdata['newbillzipcode'] = theForm.elements['newbillzipcode'].value;
		postdata['newpassword'] = theForm.elements['newpassword'].value;
		postdata['confirmpassword'] = theForm.elements['confirmpassword'].value;
		postdata['currentpassword'] = theForm.elements['currentpassword'].value;
		$.post('./php/updateinfo.php', postdata, function(responseTxt)
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
	});
}

function updateRestaurantInfo(theForm)
{
	ga('send', 'event', 'updateRestaurantInfo', 'click', 'updateRestaurantInfo', {'hitCallback':
     function () {
		var postdata = {};
		postdata['newusername'] = theForm.elements['newusername'].value;
		postdata['newrestaurantname'] = theForm.elements['newrestaurantname'].value;
		postdata['newemail'] = theForm.elements['newemail'].value;
		postdata['confirmemail'] = theForm.elements['confirmemail'].value;
		postdata['newphonenumber'] = theForm.elements['newphonenumber'].value;
		postdata['newfirstname'] = theForm.elements['newfirstname'].value;
		postdata['newlastname'] = theForm.elements['newlastname'].value;
		postdata['newaddress'] = theForm.elements['newaddress'].value;
		postdata['newcity'] = theForm.elements['newcity'].value;
		postdata['newstate'] = theForm.elements['newstate'].value;
		postdata['newzipcode'] = theForm.elements['newzipcode'].value;
		postdata['newpassword'] = theForm.elements['newpassword'].value;
		postdata['confirmpassword'] = theForm.elements['confirmpassword'].value;
		postdata['wedeliverchecked'] = theForm.elements["wedeliver"].checked;
		postdata['weallowpickupchecked'] = theForm.elements["weallowpickup"].checked;
		postdata['deliveryfee'] = theForm.elements['deliveryfee'].value;
		postdata['deliveryminimum'] = theForm.elements['deliveryminimum'].value;
		postdata['hours'] = getHoursVariable();
		postdata['currentpassword'] = theForm.elements['currentpassword'].value;
		$.post('./php/updaterestaurantinfo.php', postdata, function(responseTxt)
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
	});
}


function getHoursVariable()
{
	ga('send', 'event', 'start', 'click', 'start', {'hitCallback':
     function () {
		var hours = [[document.getElementById('sundayopen1').value,document.getElementById('sundayclose1').value,document.getElementById('sundayopen2').value,document.getElementById('sundayclose2').value],
					 [document.getElementById('mondayopen1').value,document.getElementById('mondayclose1').value,document.getElementById('mondayopen2').value,document.getElementById('mondayclose2').value],
					 [document.getElementById('tuesdayopen1').value,document.getElementById('tuesdayclose1').value,document.getElementById('tuesdayopen2').value,document.getElementById('tuesdayclose2').value],
					 [document.getElementById('wednesdayopen1').value,document.getElementById('wednesdayclose1').value,document.getElementById('wednesdayopen2').value,document.getElementById('wednesdayclose2').value],
					 [document.getElementById('thursdayopen1').value,document.getElementById('thursdayclose1').value,document.getElementById('thursdayopen2').value,document.getElementById('thursdayclose2').value],
					 [document.getElementById('fridayopen1').value,document.getElementById('fridayclose1').value,document.getElementById('fridayopen2').value,document.getElementById('fridayclose2').value],
					 [document.getElementById('saturdayopen1').value,document.getElementById('saturdayclose1').value,document.getElementById('saturdayopen2').value,document.getElementById('saturdayclose2').value]];
				 
		return hours;
	 }
	});
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

function validatePhoneNumberPlaceOrder(phonenumber)
{
    var phonematch = /^[2-9]\d{2}[2-9]\d{2}\d{4}$/;
	var digits = phonenumber.replace(/\D/g, "");
	
    if (digits.match(phonematch) !== null)
    {
    	return 1;
    }
    else
    {
    	return 0;
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

function validateUsernameNew(username)
{ 
    var id = "usernametest";
    var ru = /^[0-9a-zA-Z]+$/;
    
    return ru.test(username);
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

function validateBillZipCode(zipcode)
{ 
    var id = "billzipcodetest";
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
	
	var deliveryorpickup = document.getElementById("delivery").value;
	
	if(deliveryorpickup == "pickup")
	{
		document.getElementById(id).className = "optional";
	}
}

function validateZipCodePlaceOrder(zipcode)
{
    var zipcodematch = /^\d{5}$/;
	
    if (zipcode.match(zipcodematch) !== null)
    {
    	return 1;
    }
    else
    {
    	return 0;
    }
}

function validateZip(zipcode)
{
    var zipcodematch = /^\d{5}$/;
	
    if (zipcode.match(zipcodematch) !== null)
    {
    	return 1;
    }
    else
    {
    	return 0;
    }
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
	var icns = {
	  header: "ui-icon-circle-arrow-e",
	  activeHeader: "ui-icon-circle-arrow-s"
	};
	$("#accordion").accordion({ icons: icns, header: "h3", collapsible: true, active: false, heightStyle: "content" });
	//$(".ui-accordion-container").accordion({ active: "a.default", ...,  header: "a.accordion-label" });
	$("#accordion h3 a").click(function() {
		window.location = $(this).attr("href");
		$("#accordion").accordion({ event: "click" }).activate(2);
		return false;
	});
	$( "#tabs" ).tabs();
});