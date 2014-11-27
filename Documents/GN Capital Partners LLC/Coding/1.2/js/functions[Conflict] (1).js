window.onload=start;
var href = window.location.href;
window.addEventListener('popstate', function() {
	if(href != window.location.href)
	{
		location.reload();
	}
}, false);
var input = new Array();
var SESSION = new Array();
var popup = new Array();
var idleTime;
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
var currentAddressPopupDialog;
var editCurrentAddressPopupDialog;
var startNewOrderDialog;
var deliveryLocationsPopupDialog;
var cardTypesPopupDialog;

function start()
{
	window_width = $(window).width();
	$('#main_left_content').width($('#main_left').width());
	if(window_width>screen.width)
	{
		window_width=screen.width;
	}
	//alert(window_width);
	
	if(window_width<=720)
	{
		$('#main_left_content').removeClass('fixed');
		logo_width = $('#logo').width();
		if(window_width<480)
		{
			$('#top_nav_content').height(logo_width);
			minimizeMenu();
		}
		else
		{
			maximizeMenu();
			$('#top_nav_content').height('80px');
		}
	}
	else
	{
		maximizeMenu();
		positionLeft();
		$(document).ready(function () {
		  $(window).scroll(function (event) {
				positionLeft();
		  });
		});
	}
}

function positionLeft()
{
	if($(window).width()<=720)
	{
	  return false;
	}
	
	var top = $('#main_left').offset().top - parseFloat($('#main_left').css('marginTop').replace(/auto/, 0));
	
	// what the y position of the scroll is
	var y = $(window).scrollTop();

	// whether that's below the form
	if (y >= top) {
	  // if so, ad the fixed class
	  $('#main_left_content').removeClass('bottom');
	  $('#main_left_content').addClass('fixed');
	  $('#main_left_content').width($('#main_left').width());
	  $('#main_left').height($('#main_left_content').height());
	} else {
	  // otherwise remove it
	  $('#main_left_content').removeClass('fixed');
	}

	var bottom = $('#main_left_content').offset().top + $('#main_left_content').outerHeight(true) - parseFloat($('#main_left_content').css('marginBottom').replace(/auto/, 0));
	var main_bottom = $('#main_body').offset().top + $('#main_body').outerHeight(true) - parseFloat($('#main_body').css('marginBottom').replace(/auto/, 0)) - parseFloat($('#main_body').css('paddingBottom').replace(/auto/, 0));

	//alert(bottom+":"+main_bottom);

	if (bottom >= main_bottom) {
		$('#main_left_content').addClass('bottom');
		$('#main_left_content').removeClass('fixed');
		$('#main_left').height($('#main_left_content').height());
	}
}

function minimizeMenu()
{
	$('#main_right').find('.subcategory').each(function()
	{
		$(this).removeClass("open");
		$(this).find('span').addClass("ui-icon-circle-arrow-e");
		$(this).find('span').removeClass("ui-icon-circle-arrow-s");
		$id = '#'+$(this).attr("name");
		$($id).slideUp();
	});
}

function maximizeMenu()
{
	$('#main_right').find('h3').not('close').each(function()
	{
		$(this).find('span').addClass("ui-icon-circle-arrow-s");
		$(this).find('span').removeClass("ui-icon-circle-arrow-e");
		$id = '#'+$(this).attr("name");
		$($id).slideDown();
	});
	
	$('#main_right').find('.close').each(function()
	{
		$(this).find('span').addClass("ui-icon-circle-arrow-e");
		$(this).find('span').removeClass("ui-icon-circle-arrow-s");
		$id = '#'+$(this).attr("name");
		$($id).slideUp();
	});
}

function submitForm(theForm,thePage,javascript)
{
	//alert(thePage);
	ga('send', 'event', 'submitForm', 'click', thePage, {'hitCallback':
     function () {
	
		var postdata = {};
		var inputs = $(theForm).find('input, textarea, select').not(':input[type=button], :input[type=submit], :input[type=reset]');
		$(inputs).each(function()
		{
			$this = $(this);
			if($this.attr('type')=="radio")
			{
				if($this.is(':checked'))
				{
					postdata[this.id] = this.value;
				}
				//postdata[this.id] = $this.is(':checked'); 
			}
			else if($this.attr('type')=="checkbox")
			{
				if(this.value && this.value != 'on')
				{
					if(!postdata[this.id])
					{
						postdata[this.id] = {};
					}
					postdata[this.id][this.value] = $this.is(':checked');
				}
				else
				{
					postdata[this.id] = $this.is(':checked');
				}
				
			}
			else
			{
				postdata[this.id] = this.value;
			}
		});
	
		executePage(thePage,postdata,javascript);
	
	 }
	});
}

function submitInput(theForm,thePage,javascript)
{
	ga('send', 'event', 'submitInput', 'click', 'submitInput', {'hitCallback':
     function () {
		//alert();
	
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
		
		var postdata = {'input': input};
		
		//alert();
		
		executePage(thePage,postdata,javascript);
	
	 }
	});
}

function executePage(thePage,postdata,javascript)
{
	changeURLVariable(thePage);
	
	if(!postdata)
	{
		var postdata = {};
	}
	
	$.post('./php/page.php?page='+thePage, postdata, function(formResponseText)
	{
		alert(formResponseText);

		formResponseText = '<xml version="1.0" >' + formResponseText + '</xml>';
	
		$formResponseText = $(formResponseText);

		$formResponseText.find('variable').each(function()
		{
			//alert();
			SESSION[$(this).attr('name')] = $(this).text();
		});
	
		$formResponseText.find('javascript').each(function()
		{
			$this=$(this);
			//alert($this.text());
			eval($this.text());
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
		
		$formResponseText.find('> div').each(function()
		{
			$this=$(this);
			updateDiv($this);
		});
		
		eval(javascript);
		
	});
}

function updateDiv($xml)
{
	//alert('updateDiv');
	if($xml.attr('id'))
	{
		id = $xml.attr('id');
		//alert('html');
		if(htmltext = $xml.html())
		{
			$('#'+id).html(htmltext);
		}
		
		if($xml.attr('scrollTo')=='true')
		{
			var aTag = $("a[name='"+ id +"']");
			$('html body').animate({scrollTop: aTag.offset().top},'slow');
		}
	
		if($xml.attr('flash')=='true')
		{
			$('html body').promise().done(function(){$("#"+id).fadeOut(300).fadeIn(300);});
		}
	
		if($xml.attr('shake')=='true')
		{
			$("#"+id).effect( "shake" );
		}
	
		if($xml.attr('focus')=='true')
		{
			$("#"+id).find('input').filter(':visible:first').focus();
		}
	}
	
	//alert('script');
	if(scripttext=$xml.find("script").html())
	{
		//alert(scripttext);
		eval(scripttext);
	}
}

function popupMessage($xml)
{
	//alert('popupMessage');
	name = $xml.attr('name');
	title = decodeURIComponent($xml.attr('title'));
	text = $xml.find('text').html();
	//alert(text);
	popup[name] = $('<div id="dialogDiv" title="'+title+'"><span id="itemPopupDialogMsg">'+text+'</span></div>');
	
	//alert(popup[name]);
	
	if($(window).width()<640)
	{
		$width = $(window).width();
	}
	else
	{
		$width = 640;
	}
	
	popup[name].dialog({
		modal: true,
		draggable: false,
		resizable: false,
		width: $width,
		beforeClose: function() { $(this).dialog('destroy').remove(); }
	});
	
	//alert('button');
	
	$button=$xml.find('button');
	if($button)
	{
		$button_code = "popup[name].dialog({ buttons: [ "+$button.text()+" ] });";
		eval($button_code);
	}
	
	//alert('open');

	popup[name].dialog("open");
}

function statesList()
{
	$states = {'AL':"Alabama",
					'AK':"Alaska",
					'AZ':"Arizona",
					'AR':"Arkansas",
					'CA':"California",
					'CO':"Colorado",
					'CT':"Connecticut",
					'DE':"Delaware",
					'DC':"District Of Columbia",
					'FL':"Florida",
					'GA':"Georgia",
					'HI':"Hawaii",
					'ID':"Idaho",
					'IL':"Illinois",
					'IN':"Indiana",
					'IA':"Iowa",
					'KS':"Kansas",
					'KY':"Kentucky",
					'LA':"Louisiana",
					'ME':"Maine",
					'MD':"Maryland",
					'MA':"Massachusetts",
					'MI':"Michigan",
					'MN':"Minnesota",
					'MS':"Mississippi",
					'MO':"Missouri",
					'MT':"Montana",
					'NE':"Nebraska",
					'NV':"Nevada",
					'NH':"New Hampshire",
					'NJ':"New Jersey",
					'NM':"New Mexico",
					'NY':"New York",
					'NC':"North Carolina",
					'ND':"North Dakota",
					'OH':"Ohio",
					'OK':"Oklahoma",
					'OR':"Oregon",
					'PA':"Pennsylvania",
					'RI':"Rhode Island",
					'SC':"South Carolina",
					'SD':"South Dakota",
					'TN':"Tennessee",
					'TX':"Texas",
					'UT':"Utah",
					'VT':"Vermont",
					'VA':"Virginia",
					'WA':"Washington",
					'WV':"West Virginia",
					'WI':"Wisconsin",
					'WY':"Wyoming"};
	return $states;
}

function daysList()
{
	$days = {0:"Sunday",
					1:"Monday",
					2:"Tuesday",
					3:"Wednesday",
					4:"Thursday",
					5:"Friday",
					6:"Saturday"};
	return $days;
}

function deleteRow(theIcon)
{
	$theIcon = $(theIcon);
	$tr = $theIcon.closest('tr');
	$tr.remove();
}

function changeURLVariable(inputText,options)
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
	
		if(options['reload']==true)
		{
			location.reload();	
		}
	
	 }
	 
	});
}

function checkToEnableNew(theForm)
{
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

function checkToEnableCurrentAddress(theForm)
{
	ga('send', 'event', 'checkToEnableCurrentAddress', 'click', 'checkToEnableCurrentAddress', {'hitCallback':
     function () {
		//alert('checkToEnablePlaceOrder');
		//input = new Array();
		//var i = 0;
		//var forminputtext = "";
	
		theForm.find(":input[type=submit]").each(function() {
			this.disabled = false;
		});

		theForm.find(":input").each(function() {
		
			var testID = this.id + "_test";
			//alert(testID);
			//alert(document.getElementById(testID));
			//alert(document.getElementById(testID).className != "optional");
		
			if(document.getElementById(testID).className != "optional")
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

function calculateTip(percent,subtotal,tax,delivery_fee)
{
	//alert('calculateTip');
	tip = Math.round(subtotal*percent)/100;
	$("#tip").val(tip);
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
			tip = $("#tip").val();
			tip = Number(tip);
			tip = Math.round((tip+totalDifference)*100)/100;
			$("#tip").val(tip);
			calculateTotal(subtotal,tax,delivery_fee,tip);
		}
	 }
	});
}

function calculateTotal(subtotal,tax,delivery_fee,tip)
{
	tip = Number(tip);
	total = Math.round((subtotal+tax+delivery_fee+tip)*100)/100;
	$("#orderTotal").html("$"+total);
}

function cookielogin(i,s)
{
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

function makecode()
{
    var code = "";
    var possible = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";

    for( var i=0; i < 32; i++ )
        code += possible.charAt(Math.floor(Math.random() * possible.length));

    return code;
}

function reloadSummaryTable()
{
	ga('send', 'event', 'setMain', 'click', 'revieworderpage.php', {'hitCallback':
     function () {
     	$('#summarytablediv').load('./php/page.php?page=summarytable');
     }
   });
}

function reloadOrderInfo()
{
	ga('send', 'event', 'setMain', 'click', 'revieworderpage.php', {'hitCallback':
     function () {
     	$('#orderinfodiv').load('./php/page.php?page=orderinfo');
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
	
		$.post('./php/editordernew.php', {'postdata': postdata}, function(editOrderResponseTxt)
		{
			//alert(editOrderResponseTxt);
			if(editOrderResponseTxt)
			{
				var editOrderDialog = $('<div id="dialogDiv" title="New Order"><span id="dialogMsg">'+editOrderResponseTxt+'</span></div>');

				editOrderDialog.dialog({
					modal: true,
					draggable: false,
					resizable: false,
					width: 640,
					buttons: [ { text: "Continue As Pickup Order", click: function() { $('#mainleft').load('./php/leftorderpage.php');editOrderPopupDialog.dialog("close");$( this ).dialog( "close" ); } }, { text: "Delete Order", click: function() { deleteOrder();$( this ).dialog( "close" ); } } ],
					beforeClose: function() { $(this).dialog('destroy').remove(); }
				});

				editOrderDialog.dialog("open");
			}
			else
			{
				$('#mainleft').load('./php/leftorderpage.php');
		
				editOrderPopupDialog.dialog("close");
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
				showSide();
				setMain('restaurantpage.php');
			}
			else
			{
				//$('#mainleft').load('./php/leftorderpage.php');
				//$('#mainbody').load('./php/placeorderpage.php #main');
				reviewOrder();
			}
		});
	 }
	});
}

function addItemToOrder(theForm,loc_id,rest_id,item_id)
{
	ga('send', 'event', 'addItemToOrder', 'click', 'rest_id=' + rest_id + '&item_id=' + item_id, {'hitCallback':
     function () {
	
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
		
		var postdata = {};
		$.post('./php/checkneworder.php', postdata, function(checkNewOrderResponseTxt)
		{
			//alert('checkneworder');
		
			if(checkNewOrderResponseTxt=="New Order")
			{
				//alert(checkNewOrderResponseTxt);
				var postdata = {};
				$.post('./php/sessionusername.php', postdata, function(username)
				{
					//alert(username);
					if(!username)
					{
						//alert('about to log in');
						var postdata = {};
						$.post('./php/loginneworderdiv.php?loc_id=' + loc_id + '&rest_id=' + rest_id + '&item_id=' + item_id, postdata, function(responseTxt)
						{
							//alert('loginneworder');
							// do something here with the returnedData
							if(!loginNewOrderDialog)
							{
								loginNewOrderDialog = $('<div id="dialogDiv" title="Login"><span id="loginNewOrderDialogMsg">'+responseTxt+'</span></div>');
					
								loginNewOrderDialog.dialog({
									modal: true,
									draggable: false,
									resizable: false,
									width: 620
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
				$.post('./php/additemtoorder.php?loc_id=' + loc_id + '&rest_id=' + rest_id + '&item_id=' + item_id, {'input': input}, function(addItemToOrderResponseTxt)
				{
					$('#mainleft').load('./php/leftorderpage.php');
				
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
		//alert('startNewOrderPopup');
		//alert(input);
		$.post('./php/startneworderpopup.php?loc_id=' + loc_id + '&rest_id=' + rest_id + '&item_id=' + item_id, {'input': input}, function(startOrderResponseTxt)
		{
			//alert(startOrderResponseTxt);
			// do something here with the returnedData
			startNewOrderDialog = $('<div id="dialogDiv" title="New Order"><span id="dialogMsg">'+startOrderResponseTxt+'</span></div>');
			//alert(2);
			startNewOrderDialog.dialog({
				modal: true,
				draggable: false,
				resizable: false,
				width: 640,
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
			if(startOrderResponseTxt)
			{
				startNewOrderDialog = $('<div id="dialogDiv" title="New Order"><span id="dialogMsg">'+startOrderResponseTxt+'</span></div>');

				startNewOrderDialog.dialog({
					modal: true,
					draggable: false,
					resizable: false,
					width: 640,
					buttons: [ { text: "Continue As Pickup Order", click: function() { location.reload();$( this ).dialog( "close" ); } }, { text: "Go Back", click: function() { deleteOrder();$( this ).dialog( "close" ); } } ],
					beforeClose: function() { $(this).dialog('destroy').remove(); }
				});

				startNewOrderDialog.dialog("open");
			}
			else
			{
				location.reload();
			}
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
		
			$('#mainleft').load('./php/leftorderpage.php');
		
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
				//alert(placeOrderResponseTxt);
				placeOrderDialog.dialog({
					modal: true,
					draggable: false,
					resizable: false,
					width: 620,
					buttons: [ { text: "Awesome", click: function() { $( this ).dialog( "close" ); } } ],
					beforeClose: function() { deleteOrder();$(this).dialog('destroy').remove(); }
				});
			}
			else
			{
				placeOrderDialog.dialog({
					modal: true,
					draggable: false,
					resizable: false,
					width: 620,
					buttons: [ { text: "Alright", click: function() { $( this ).dialog( "close" ); } } ],
					beforeClose: function() { $(this).dialog('destroy').remove(); }
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
		$.post('./php/registerpopup.php', postdata, function(responseTxt)
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
		
			checkToEnableNew($('#userregform'));
		});
	 }
	});
}

function hoursPopup(name,loc_id,rest_id)
{
	ga('send', 'event', 'hoursPopup', 'click', rest_id, {'hitCallback':
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

function forgotPasswordPopup()
{
	ga('send', 'event', 'forgotPassword', 'click', 'forgotPassword', {'hitCallback':
     function () {
		var postdata = {};
		$.post('./php/forgotpassworddiv.php', postdata, function(responseTxt)
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
	});
}

function forgotPasswordRestaurant(theForm)
{
	ga('send', 'event', 'forgotPasswordRestaurant', 'click', theForm.elements['usernamebox'].value+'::'+theForm.elements['emailbox'].value, {'hitCallback':
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
		postdata['newusername'] = theForm.elements['username'].value;
		postdata['newrestaurantname'] = theForm.elements['restaurant_name'].value;
		postdata['newemail'] = theForm.elements['email'].value;
		postdata['confirmemail'] = theForm.elements['confirm_email'].value;
		postdata['newphonenumber'] = theForm.elements['phone_number'].value;
		postdata['newfirstname'] = theForm.elements['first_name'].value;
		postdata['newlastname'] = theForm.elements['last_name'].value;
		postdata['newaddress'] = theForm.elements['address'].value;
		postdata['newcity'] = theForm.elements['city'].value;
		postdata['newstate'] = theForm.elements['state'].value;
		postdata['newzipcode'] = theForm.elements['zip_code'].value;
		postdata['newpassword'] = theForm.elements['password'].value;
		postdata['confirmpassword'] = theForm.elements['confirm_password'].value;
		//alert(1);
		//postdata['wedeliverchecked'] = theForm.elements["we_deliver"].checked;
		//postdata['weallowpickupchecked'] = theForm.elements["we_allow_pickup"].checked;
		postdata['wedeliverchecked'] = document.getElementsByName("we_deliver")[0].checked;
		postdata['weallowpickupchecked'] = document.getElementsByName("we_allow_pickup")[0].checked;
		//alert(2);
		postdata['termsofusechecked'] = theForm.elements["terms_of_use"].checked;
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
	ga('send', 'event', 'setMain', 'click', 'revieworderpage.php', {'hitCallback':
     function () {
     	$('#topbar').load('./php/revieworderpage.php #top');
		$('#mainbody').load('./php/revieworderpage.php #main', function() {
			testFormPlaceOrder();
		});
     }
   });
}

function openPage(url)
{
   ga('send', 'event', 'outbound', 'click', url, {'hitCallback':
     function () {
     	window.open(url);
     }
   });
}

function hideSide()
{
	$('#mainleft').hide();
}

function showSide()
{
	$('#mainleft').show();
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
	ga('send', 'event', 'switchToCustomer', 'click', 'switchToCustomer', {'hitCallback':
     function () {
     	$.cookie("restaurant", 1, { expires: 3*365, domain: '.gottanom.com'});
		changeURLVariable("restaurant=true");
		location.reload();
     }
   });
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

function checkForm(theForm)
{
	if(typeof theForm === 'string')
	{
		theForm = $(theForm);
	}
	
	theForm.find(":input[type=submit]").each(function() {
		this.disabled = false;
	});

	theForm.find(":input[type!=submit]").each(function() {
	
		var testID = '#' + this.id + "_test";
		//alert(testID);
	
		if(theForm.find(testID).attr('class') == "test")
		{
			theForm.find(":input[type=submit]").each(function() {
				this.disabled = true;
			});
			return false;
		}
	});
}

function testForm(theForm)
{
	if(typeof theForm === 'string')
	{
		theForm = $(theForm);
	}
	
	theForm.find('input, textarea, select').each(function()
	{
		$this = $(this);
		//alert(this.id);
		$this.keyup();
		$this.change();
		//alert(this.id);
	});
}

function testInput(theInput,options)
{
	if(options['required'] != false)
	{
		options['required'] = true;
	}
	
	var inputText = theInput.value;
	var testID = "#" + theInput.id + "_test";
	var theForm = $(theInput).closest('form');
	
	if (inputText.length == 0 && options['required'])
	{
		if (options['empty_text'])
		{
			theForm.find(testID).html(options['empty_text']);
		}
		theForm.find(testID).attr("class", "test");
		theForm.find(":input[type=submit]").each(function() {
			this.disabled = true;
		});
	}
	else if (inputText.length == 0)
	{
		if (options['empty_text'])
		{
			theForm.find(testID).html(options['empty_text']);
		}
		theForm.find(testID).attr("class", "optional");
		checkForm(theForm);
	}
	else if (eval(options['javascript']))
	{
		if (options['filled_text'])
		{
			theForm.find(testID).html(options['filled_text']);
		}
		theForm.find(testID).attr("class", "optional");
		checkForm(theForm);
	}
	else
	{
		if (options['error_text'])
		{
			theForm.find(testID).html(options['error_text']);
		}
		theForm.find(testID).attr("class", "test");
		theForm.find(":input[type=submit]").each(function() {
			this.disabled = true;
		});
	}

}

function testAddress(theInput,options)
{
	if(!options)
	{
		var options = new Array();
	}
	
	var onBeach = $(':input:eq(' + ($(':input').index(theInput) - 1) + ')').prop('checked');
	
	if(onBeach)
	{
		if(options['javascript_on_beach'])
		{
			options['javascript'] = options['javascript_on_beach'];
		}
		else
		{
			options['javascript'] = 'inputText.length >= 3';
		}
	}
	else
	{
		if(!options['javascript'])
		{
			options['javascript'] = 'validateAddress(inputText)';
		}
	}
	
	testInput(theInput,options);
}

function testOnBeach(theCheckbox,options)
{
	if(!options)
	{
		var options = new Array();
	}
	
	var $checkbox = $(theCheckbox);
	var onBeach = $checkbox.prop('checked');
	var $input = $(':input:eq(' + ($(':input').index(theCheckbox) + 1) + ')');
	var theInput = $input[0];
	
	if(onBeach)
	{
		$input.attr('placeholder','Cross Street');
		if(options['javascript_on_beach'])
		{
			options['javascript'] = options['javascript_on_beach'];
		}
		else
		{
			options['javascript'] = 'inputText.length >= 3';
		}
	}
	else
	{
		$input.attr('placeholder','Street Address');
		if(!options['javascript'])
		{
			options['javascript'] = 'validateAddress(inputText)';
		}
	}
	
	testInput(theInput,options);
}

function testBillAddress(theInput,options)
{
	if(!options)
	{
		var options = new Array();
	}

	if(!options['javascript'])
	{
		options['javascript'] = 'validateAddress(inputText)';
	}
	
	testInput(theInput,options);
}

function testConfirmCardNumberPlaceOrder(theInput,options)
{
	if(!options)
	{
		var options = new Array();
	}
	
	var inputText = theInput.value;
	var inputDigits = inputText.replace(/ /g,"");
	var theForm = theInput.form;
	var cardNumber = theForm.elements['card_number'].value;
	var cardDigits = cardNumber.replace(/ /g,"");
	
	if(!options['javascript'])
	{
		options['javascript'] = inputDigits + ' != ' + cardDigits;
	}
	
	testInput(theInput,options);
}

function testExpDatePlaceOrder(theInput,options)
{
	if(!options)
	{
		var options = new Array();
	}

	if(!options['javascript'])
	{
		options['javascript'] = 'validateExpDate(inputText)';
	}
	
	testInput(theInput,options);
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

function testCardTypePlaceOrder(theInput,options)
{
	if(!options)
	{
		var options = new Array();
	}

	if(!options['javascript'])
	{
		options['javascript'] = '1';
	}
	
	testInput(theInput,options);
}

function testCity(theInput,options)
{
	if(!options)
	{
		var options = new Array();
	}

	if(!options['javascript'])
	{
		options['javascript'] = 'validateCity(inputText)';
	}
	
	testInput(theInput,options);
}

function testBillCityPlaceOrder(theInput,options)
{
	if(!options)
	{
		var options = new Array();
	}

	if(!options['javascript'])
	{
		options['javascript'] = 'validateCity(inputText)';
	}
	
	testInput(theInput,options);
}

function testDeliveryPickup(theInput)
{
	//alert();
	var theForm = $(theInput).closest('form');
	var id = "delivery_pickup_test";
	//alert();
	var wedeliver = document.getElementsByName("we_deliver")[0].checked;
	var weallowpickup = document.getElementsByName("we_allow_pickup")[0].checked;
	
	//alert(wedeliver);
	//alert(weallowpickup);
	
	if (wedeliver && weallowpickup)
	{
		document.getElementById(id).innerHTML = "Even Better!";
		document.getElementById(id).className = "optional";
		checkToEnableNew();
	}
	else if (wedeliver || weallowpickup)
	{
		document.getElementById(id).innerHTML = "Nice!";
		document.getElementById(id).className = "optional";
		checkToEnableNew(theForm);
	}
	else
	{
		document.getElementById(id).innerHTML = "Choose At Least One";
		document.getElementById(id).className = "test";
		disableButton();
	}
}

function testEmail(theInput,options)
{
	if(!options)
	{
		var options = new Array();
	}

	if(!options['javascript'])
	{
		options['javascript'] = 'validateEmail(inputText)';
	}
	
	testInput(theInput,options);
	
	if(theInput.value.length != 0)
	{
		options['required'] = true;
	}
	
	if(confirm_email = $('#confirm_email')[0])
	{
		testConfirmEmail(confirm_email,options);
	}
}

function testConfirmEmail(theInput,options)
{
	if(!options)
	{
		var options = new Array();
	}
	
	var confirmemailtext = theInput.value;
	var theForm = $(theInput).closest('form');
	var emailtext = theForm.find('#email').val();

	if(!options['javascript'])
	{
		options['javascript'] = '"' + confirmemailtext + '" == "' + emailtext + '"';
	}
	
	testInput(theInput,options);
}

function testDeliveryPlaceOrder(theInput)
{
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

function testFaxUpdate()
{
	var id = "faxtest";
	var faxtext = document.getElementById('fax').value;
	var faxnumber = faxtext.replace(/\D/g, "");
	//alert(callinordernumber);
	var faxlength = faxnumber.length;
	//alert(callinorderlength);
	
	switch(faxlength)
	{
		case 0:
			document.getElementById(id).innerHTML = "Fax Number";
			document.getElementById(id).className = "optional";
			checkToEnable();
			break;
		case 10:
			document.getElementById(id).innerHTML = "Nice Number";
			document.getElementById(id).className = "optional";
			checkToEnable();
			break;
		default:
			document.getElementById(id).innerHTML = "Not a valid number";
			document.getElementById(id).className = "test";
			checkToEnable();
			break;
	}
}

function testFirstName(theInput,options)
{
	if(!options)
	{
		var options = new Array();
	}

	if(!options['javascript'])
	{
		options['javascript'] = '1';
	}
	
	testInput(theInput,options);
}

function testNameOnCardPlaceOrder(theInput,options)
{
	if(!options)
	{
		var options = new Array();
	}

	if(!options['javascript'])
	{
		options['javascript'] = '1';
	}
	
	testInput(theInput,options);
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

function testLastName(theInput,options)
{
	if(!options)
	{
		var options = new Array();
	}

	if(!options['javascript'])
	{
		options['javascript'] = '1';
	}
	
	testInput(theInput,options);
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

function testPassword(theInput,options)
{
	if(!options)
	{
		var options = new Array();
	}

	if(!options['javascript'])
	{
		options['javascript'] = '1';
	}
	
	testInput(theInput,options);
	
	var theForm = $(theInput).closest('form');
	
	if(confirmInput = theForm.find('#confirm_'+theInput.id)[0])
	{
		testConfirmPassword(confirmInput,options);
	}
}

function testConfirmPassword(theInput,options)
{	
	if(!options)
	{
		var options = new Array();
	}
	
	var confirmpasswordtext = theInput.value;
	var theForm = $(theInput).closest('form');
	var passwordtext = theForm.find('#password').val();

	if(!options['javascript'])
	{
		options['javascript'] = '"' + confirmpasswordtext + '" == "' + passwordtext + '"';
	}
	
	testInput(theInput,options);
}

function testPhoneNumber(theInput,options)
{
	if(!options)
	{
		var options = new Array();
	}

	if(!options['javascript'])
	{
		options['javascript'] = 'validatePhoneNumber(inputText)';
	}
	
	testInput(theInput,options);
	
	if(validatePhoneNumber(theInput.value))
	{
		var digits = theInput.value.replace(/\D/g, "");
		var numbertext = digits.slice(0,3)+"-"+digits.slice(3,6)+"-"+digits.slice(6,10);
		theInput.value = numbertext;
	}
}

function testCallInOrderUpdate()
{
	var id = "callinordertest";
	var callinordertext = document.getElementById('callinorder').value;
	var callinordernumber = callinordertext.replace(/\D/g, "");
	//alert(callinordernumber);
	var callinorderlength = callinordernumber.length;
	//alert(callinorderlength);
	
	switch(callinorderlength)
	{
		case 0:
			document.getElementById(id).innerHTML = "Call In Order Number";
			document.getElementById(id).className = "optional";
			checkToEnable();
			break;
		case 10:
			//alert(callinordernumber);
			document.getElementById(id).innerHTML = "Good Number";
			document.getElementById(id).className = "optional";
			checkToEnable();
			break;
		default:
			document.getElementById(id).innerHTML = "Not a valid number";
			document.getElementById(id).className = "test";
			checkToEnable();
			break;
	}
}

function isInt(n)
{
   return typeof n === 'number' && n % 1 == 0;
}

function testRestaurantName(theInput)
{
	if(!options)
	{
		var options = new Array();
	}

	if(!options['javascript'])
	{
		options['javascript'] = '1';
	}
	
	testInput(theInput,options);
}

function testState(theInput)
{
	if(!options)
	{
		var options = new Array();
	}

	if(!options['javascript'])
	{
		options['javascript'] = 'validateState(inputText)';
	}
	
	testInput(theInput,options);
}
	
function testTermsOfUse(theInput)
{
	if(!options)
	{
		var options = new Array();
	}

	if(!options['javascript'])
	{
		options['javascript'] = 'theInput.checked';
	}
	
	testInput(theInput,options);
}

function testUsername(theInput,options)
{
	if(!options)
	{
		var options = new Array();
	}
	
	if(!options['javascript'])
	{
		options['javascript'] = 'validateUsernameNew(inputText)';
	}
	
	testInput(theInput,options);
}

function testZipCode(theInput,options)
{
	if(!options)
	{
		var options = new Array();
	}
	
	if(!options['javascript'])
	{
		options['javascript'] = 'validateZipCode(inputText)';
	}
	
	testInput(theInput,options);
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
		
		postdata['charity'] = {};
		$(theForm).find(":input[type=checkbox]").each(function() {
			postdata['charity'][this.id] = $(this).is(':checked');
		});
		
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
		postdata['callinorder'] = theForm.elements['callinorder'].value.replace(/\D/g, "");
		postdata['fax'] = theForm.elements['fax'].value;
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
	var hours = [[document.getElementById('sundayopen1').value,document.getElementById('sundayclose1').value,document.getElementById('sundayopen2').value,document.getElementById('sundayclose2').value],
				 [document.getElementById('mondayopen1').value,document.getElementById('mondayclose1').value,document.getElementById('mondayopen2').value,document.getElementById('mondayclose2').value],
				 [document.getElementById('tuesdayopen1').value,document.getElementById('tuesdayclose1').value,document.getElementById('tuesdayopen2').value,document.getElementById('tuesdayclose2').value],
				 [document.getElementById('wednesdayopen1').value,document.getElementById('wednesdayclose1').value,document.getElementById('wednesdayopen2').value,document.getElementById('wednesdayclose2').value],
				 [document.getElementById('thursdayopen1').value,document.getElementById('thursdayclose1').value,document.getElementById('thursdayopen2').value,document.getElementById('thursdayclose2').value],
				 [document.getElementById('fridayopen1').value,document.getElementById('fridayclose1').value,document.getElementById('fridayopen2').value,document.getElementById('fridayclose2').value],
				 [document.getElementById('saturdayopen1').value,document.getElementById('saturdayclose1').value,document.getElementById('saturdayopen2').value,document.getElementById('saturdayclose2').value]];
	//alert(hours);	 
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

function validateFaxEmail(email)
{ 
    var re = /^([1][2-9]\d{2}[2-9]\d{2}\d{4})@nextivafax.com$/;
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

function scrollToAnchor(anchorName){
	var aTag = $("a[name='"+ anchorName +"']");
	$('html body').animate({scrollTop: aTag.offset().top},'slow', function()
	{
		 $("#"+anchorName).fadeOut(300).fadeIn(300).find('input').filter(':visible:first').focus();
	});
   
}

function changeCategory(theSelect)
{
	theSelect.find('option').each(function() {
		$(this.value).slideUp();
	});
	
	$(theSelect.find(':selected').val()).slideDown();
}

function toggleSubcategory(theDiv)
{
	$(theDiv).toggleClass("open");
	$(theDiv).find('span').toggleClass("ui-icon-circle-arrow-e ui-icon-circle-arrow-s");
	$id = '#'+$(theDiv).attr("name");
	$($id).slideToggle();
}

$(function() {
    var icns = {
      header: "ui-icon-circle-arrow-e",
      activeHeader: "ui-icon-circle-arrow-s"
    };
    $('#main_right').find("#accordion").each(function() {
    	$(this).accordion({ icons: icns, header: "h3", collapsible: true, active: false, heightStyle: "content" });
    	$(this).find("h3 a").click(function() {
			window.location = $(this).attr('href');
			$(this).accordion({ event: "click" }).activate(2);
		});
		alert();
    });
    //$(".ui-accordion-container").accordion({ active: "a.default", ...,  header: "a.accordion-label" });
    /*
    $("#accordion h3 a").click(function() {
		window.location = $(this).attr('href');
		$("#accordion").accordion({ event: "click" }).activate(2);
		return false;
	});
	*/
    //$( "#tabs" ).tabs();
	$( "#dialog-modal" ).dialog({
		height: 140,
		modal: true,
		dragable: false
	});
});

$(window).on("hashchange", function () {
    window.scrollTo(window.scrollX, window.scrollY - 100);
});

$(window).resize(function() {
	start();
});
