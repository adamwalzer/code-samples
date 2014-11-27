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

function start()
{
	window_width = $(window).width();
	$('#main_left_content').outerWidth($('#main_left').width());
	$('#main_left').outerHeight($('#main_left_content').height());
	if(window_width>screen.width)
	{
		window_width=screen.width;
	}
	
	if(window_width<=720)
	{
		$('#main_left_content').removeClass('fixed');
		if(window_width<=480)
		{
			minimizeMenu();
		}
		else
		{
			maximizeMenu();
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
	  $('#main_left_content').outerWidth($('#main_left').width());
	  $('#main_left').outerHeight($('#main_left_content').height());
	} else {
	  // otherwise remove it
	  $('#main_left_content').removeClass('fixed');
	}

	var bottom = $('#main_left_content').offset().top + $('#main_left_content').outerHeight(true) - parseFloat($('#main_left_content').css('marginBottom').replace(/auto/, 0)) - parseFloat($('#main_left_content').css('paddingBottom').replace(/auto/, 0));
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
	$('#main_right').find('h3').not('.close').each(function()
	{
		$(this).find('span').addClass("ui-icon-circle-arrow-s");
		$(this).find('span').removeClass("ui-icon-circle-arrow-e");
		$id = '#'+$(this).attr("name");
		$($id).slideDown();
	});
}

function submitForm(theForm,thePage,javascript)
{
	//alert(theForm.attr('id'));
	
	if(!(theForm instanceof jQuery))
	{
		theForm = $(theForm);
	}
	
	//alert(thePage);
	ga('send', 'event', 'submitForm', 'click', thePage, {'hitCallback':
     function () {
	
		var postdata = {};
		var inputs = theForm.find('input, textarea, select').not(':input[type=button], :input[type=submit], :input[type=reset]');
		$(inputs).each(function()
		{
			$this = $(this);
			if($this.attr('type')=="radio")
			{
				if($this.is(':checked'))
				{
					postdata[this.name] = this.value;
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
		
		executePage(thePage,postdata,javascript);
	
	 }
	});
}

function executePage(thePage,postdata,javascript)
{
	//alert('executePage');
	//alert(thePage);
	
	changeURLVariable(thePage);
	
	if(!postdata)
	{
		var postdata = {};
	}
	
	$.post('./php/page.php?page='+thePage, postdata, function(formResponseText)
	{
		//alert(formResponseText);

		formResponseText = '<xml version="1.0" >' + formResponseText + '</xml>';
	
		$formResponseText = $(formResponseText);

		$formResponseText.find('variable').each(function()
		{
			//alert();
			SESSION[$(this).attr('name')] = $(this).text();
		});
	
		$formResponseText.find('action').each(function()
		{
			$this=$(this);
			$type=$this.attr('type');
		
			switch($type)
			{
				case 'popup':
					popupMessage($this);
					break;
			}
		});
		
		$formResponseText.find('> div').each(function()
		{
			$this=$(this);
			$type=$this.attr('type');
		
			switch($type)
			{
				case 'popup':
					popupMessage($this);
					break;
				default:
					updateDiv($this);
			}
		});
		
		eval(javascript);
		
		positionLeft();
		
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
	
		var newVariableArray = [['admin',''],['restaurant',''],['loc_id',''],['rest_id',''],['page','']];
	
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

function addInputNames()
{
	// Not ideal, but jQuery's validate plugin requires fields to have names
	// so we add them at the last possible minute, in case any javascript 
	// exceptions have caused other parts of the script to fail.
	$('.card_number').attr('name', 'card_number')
	$('.cvc').attr('name', 'cvc')
	$('.exp_year').attr('name', 'exp_year')
}

function removeInputNames()
{
	$('.card_number').removeAttr('name')
	$('.cvc').removeAttr('name')
	$('.exp_year').removeAttr('name')
}

function placeOrder(formID)
{
	// remove the input field names for security
	// we do this *before* anything else which might throw an exception
	removeInputNames(); // THIS IS IMPORTANT!
	
	// given a valid form, submit the payment details to stripe
	//$form.find(['submit-button']).attr('disabled', 'disabled')
	popup['place_order'].dialog('close');
	
	executePage('checkprocess&formID='+formID);
}

function placeOrderStripe($form)
{
	Stripe.createToken({
		number: $form.find('.card_number').val(),
		cvc: $form.find('.cvc').val(),
		exp_month: $form.find('.exp_month').val(), 
		exp_year: $form.find('.exp_year').val()
	}, function(status, response) {
		if (response.error) {
			// re-enable the submit button
			//$(form['submit_button']).removeAttr('disabled')

			// show the error
			$('.payment_errors').html(response.error.message);

			// we add these names back in so we can revalidate properly
			addInputNames();
		} else {
			// token contains id, last4, and card type
			var token = response['id'];

			// insert the stripe token
			//var input = $(".'"'."<input name='stripeToken' value='".'"'." + token + ".'"'." style='display:none;' />".'"'.");
			//form.appendChild(input[0])

			// and submit
			//form.submit();
			var pattern = '/[^0-9]*/';
			var card_number = $form.find('.card_number').val().replace(' ','');
			var last_card_digits = card_number.slice(-4);
			
			var postdata = {'stripeToken': token,
							'card_type': $form.find('#card_type').val(),
							'last_card_digits': last_card_digits};
			
			executePage('placeorder',postdata);
		}
	});

	return false;
}

function openPage(url)
{
   ga('send', 'event', 'outbound', 'click', url, {'hitCallback':
     function () {
     	window.open(url);
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
	if(!(theInput instanceof jQuery))
	{
		theInput = $(theInput);
	}
	
	if(options['required'] != false)
	{
		options['required'] = true;
	}
	
	var inputText = theInput.val();
	var testID = "#" + theInput.attr('id') + "_test";
	var theForm = theInput.closest('form');
	
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
		$input.attr('placeholder','Street');
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

function testConfirmCardNumber(theInput,options)
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
		options['javascript'] = inputDigits + ' == ' + cardDigits;
	}
	
	testInput(theInput,options);
}

function testExpMonth(theInput,options)
{
	if(!options)
	{
		var options = new Array();
	}
	
	var yearText = $('.exp_year').val();

	if(!options['javascript'])
	{
		options['javascript'] = 'validateExpDate(inputText,"'+yearText+'") && Stripe.validateExpiry(inputText,"'+yearText+'")';
	}
	
	testInput(theInput,options);
}

function testExpYear(theInput,options)
{
	if(!options)
	{
		var options = new Array();
	}
	
	var monthText = $('.exp_month').val();

	if(!options['javascript'])
	{
		options['javascript'] = 'validateExpDate("'+monthText+'",inputText) && Stripe.validateExpiry("'+monthText+'",inputText)';
	}
	
	testInput(theInput,options);
}

function validateExpDate(month,year)
{
	var dateString = month + '/' + year;
	var expDateMatch = /([0-1])([0-9])([\/])\d{2}/;
	if(!dateString.match(expDateMatch))
	{
		return 0;
	}
	var dateArray = dateString.split("/");
	//alert(dateArray[0]);
	//alert('20'+dateArray[1]);
	
	if(!dateArray[0] || !dateArray[1])
	{
		return 0;
	}
	
	if(parseInt(dateArray[0]) > 12 || parseInt(dateArray[0]) < 1)
	{
		return 0;	
	}
	
	if(dateArray[1].length == 2)
	{
		dateArray[1] = '20'+dateArray[1];
	}
	
	var date = new Date(dateArray[1],dateArray[0]);
	//alert(dateArray);
	var currentDate = new Date();
	
	//alert(date);
	
	return !(currentDate > date);
}

function testCVC(theInput,options)
{
	if(!options)
	{
		var options = new Array();
	}
	
	var cardType = $('#card_type').val();

	if(!options['javascript'])
	{
		options['javascript'] = 'validateCVC(inputText,"'+cardType+'") && Stripe.validateCVC(inputText)';
	}
	
	testInput(theInput,options);
}

function validateCVC(cvcString,cardType)
{
	if(cardType == 'Amex')
	{
		var cvcMatch = /^\d{4}$/;
	}
	else
	{
		var cvcMatch = /^\d{3}$/;
	}
	
	return cvcString.match(cvcMatch);
}

function testCardType(theInput,options)
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
	
	$('#cvc').keyup();
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
	
	if(!(options['confirm'] === false))
	{
		options['confirm'] = true;
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
	
	var theForm = $(theInput).closest('form');
	var confirm_email = theForm.find('#confirm_email');
	
	if(confirm_email && options['confirm'])
	{
		confirm_email.keyup();
	}
}

function testConfirmEmail(theInput,options)
{
	if(!(theInput instanceof jQuery))
	{
		theInput = $(theInput);
	}
	
	if(!options)
	{
		var options = new Array();
	}
	
	var confirmemailtext = theInput.val();
	var theForm = theInput.closest('form');
	var emailtext = theForm.find('#email').val();

	if(!options['javascript'])
	{
		options['javascript'] = '"' + confirmemailtext + '" == "' + emailtext + '"';
	}
	
	testInput(theInput,options);
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

function testNameOnCard(theInput,options)
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
		options['javascript'] = 'validatePassword(inputText)';
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
		options['javascript'] = 'validateUsername(inputText)';
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

function testCardNumber(theInput)
{
    if(!options)
	{
		var options = new Array();
	}
	
	var cardType = $('#card_type').val();
	
	if(!options['javascript'])
	{
		options['javascript'] = 'validateCardNumber(inputText,"'+cardType+'") && Stripe.validateCardNumber(inputText)';
	}
	
	testInput(theInput,options);
	
	
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
    return rp.test(password) && password.length >= 6;
}

function validatePhoneNumber(phonenumber)
{
    var phonematch = /^[2-9]\d{2}[2-9]\d{2}\d{4}$/;
	var digits = phonenumber.replace(/\D/g, "");
	return digits.match(phonematch) !== null
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
    return ru.test(username);
}

function validateZipCode(zipcode)
{
    var zipcodematch = /^\d{5}$/;
	return zipcode.match(zipcodematch) !== null
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
	
	$(theSelect.find(':selected').val()).slideDown(400, function(){
		start();
	});
}

function toggleSubcategory(theDiv)
{
	$(theDiv).find('span').toggleClass("ui-icon-circle-arrow-e ui-icon-circle-arrow-s");
	$id = '#'+$(theDiv).attr("name");
	$($id).slideToggle();
		
	//change this code in the future
	/*
	if($(window).width()<=480)
	{
		$(theDiv).toggleClass("open");
		$(theDiv).find('span').toggleClass("ui-icon-circle-arrow-e ui-icon-circle-arrow-s");
		$id = '#'+$(theDiv).attr("name");
		$($id).slideToggle();
	}
	else
	{
		$(theDiv).toggleClass("open");
		$(theDiv).find('span').toggleClass("ui-icon-circle-arrow-e ui-icon-circle-arrow-s");
		$id = '#'+$(theDiv).attr("name");
		$($id).slideToggle();
	}
	*/
}


function setAddress(theForm)
{
	alert('setAddress');
	
	if(!(theForm instanceof jQuery))
	{
		theForm = $(theForm);
	}
	
	var address = theForm.find('#current_address').val();
	
	alert(address);
	
	var geocoder = new google.maps.Geocoder();
	
	alert(geocoder);
	
	geocoder.geocode( { 'address': address}, function(results, status) {
		if (status == google.maps.GeocoderStatus.OK) {
			
			//alert(results[0].formatted_address);
			//alert(results[0].geometry.location);
			
			if(results[0].types == 'street_address' || results[0].types == 'subpremise' || results[0].types == 'intersection' ) {
				var postdata = getAddressArray(theForm,results[0].address_components);
				//alert(results[0].address_components[0].long_name);
				executePage('setcurrentaddress',postdata);		
			} else {
				console.log('no good: ' + results[0].types);
			}
		} else {
			console.log('Geocode was not successful for the following reason: ' + status);
		}
	});
	
}



function getAddressArray(theForm,address_components)
{
	console.log('getAddressArray');
	var postdata = {};
	
	var inputs = theForm.find('input, textarea, select').not(':input[type=button], :input[type=submit], :input[type=reset]');
	$(inputs).each(function()
	{
		$this = $(this);
		if($this.attr('type')=="radio")
		{
			if($this.is(':checked'))
			{
				postdata[this.name] = this.value;
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
	
	
	for(i=0; i<address_components.length;i++)
	{
		console.log(address_components[i].types);
		switch(address_components[i].types[0])
		{
			case "subpremise":
				postdata["current_apt"] = address_components[i].long_name;
				break;
			case "street_number":
				postdata["current_address"] = address_components[i].long_name;
				break;
			case "route":
				postdata["current_address"] += " " + address_components[i].long_name;
				break;
			case "locality":
				postdata["current_city"] = address_components[i].long_name;
				break;
			case "administrative_area_level_1":
				postdata["current_state"] = address_components[i].short_name;
				break;
			case "postal_code":
				postdata["current_zip"] = address_components[i].long_name;
				break;
		}
		console.log(postdata);
	}
	return postdata;
}


$(window).on("hashchange", function () {
    window.scrollTo(window.scrollX, window.scrollY - 100);
});

$(window).resize(function() {
	$('html body').promise().done(function(){start()});
});
