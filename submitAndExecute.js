// submit form information to the given page. Organize the form information in an array based on the id of the inputs
function submitForm(theForm,thePage)
{	
	// create postdata array and find the inputs, text areas and selects in the form that not buttons, submits, or resets.
	var postdata = {};
	var inputs = $(theForm).find('input, textarea, select').not(':input[type=button], :input[type=submit], :input[type=reset]');
	
	// go through each input and put the correct information in the postdata array with the correct key.
	$(inputs).each(function()
	{
		$this = $(this);
		if($this.attr('type')=="radio") // if the input is a radio button then store the value in the postdata array with the input id as the key.
		{
			if($this.is(':checked'))
			{
				postdata[this.id] = this.value;
			}
		}
		else if($this.attr('type')=="checkbox") // if the input is a checkbox then store whether or not the checkbox is checked in the postdata array with the input id as the key.
		{
			if(this.value && this.value != 'on') // if the checkbox has a value that is not 'on' then create an array in 
			{
				if(!postdata[this.id]) // if there is nothing in postdata with the key of the checkbox's id then create an array in that position
				{
					postdata[this.id] = {};
				}
				// store whether or not the checkbox is checked in postdata[this.id][this.value].
				postdata[this.id][this.value] = $this.is(':checked');
			}
			else
			{
				// store whether or not the checkbox is checked in the postdata array with the key of the checkbox's id.
				postdata[this.id] = $this.is(':checked');
			}
			
		}
		else // otherwise store the value of the input in the postdata array with the input id as key.
		{
			postdata[this.id] = this.value;
		}
	});

	// execute the page using the postdata array
	executePage(thePage,postdata);
}

// submit form information to the given page. Organize the form information in an array based on the order of the inputs
function submitInput(theForm,thePage)
{
	// create new input array and set variable i as 0.
	input = new Array();
	var i = 0;
	
	// find each input in the form
	$(theForm).find(":input").each(function(){
		if($(this).attr('type')=="radio") // if the input is a radio box put the checked condition in the next position in the input array.
		{
			input[i] = $(this).is(':checked'); 
		}
		else if($(this).attr('type')=="checkbox") // if the input is a checkbox put the checked condition in the next position in the input array.
		{
			input[i] = $(this).is(':checked'); 
		}
		else // otherwise put the value of the input in the next position in the input array.
		{
			input[i] = $(this).val(); // This is the jquery object of the input, do what you will
		}
		// increment the variable i.
		i++;
	});
	
	// create the postdata variable with the input array in the position with the key 'input'.
	var postdata = {'input': input};
	
	// execute the page using the postdata array
	executePage(thePage,postdata);
}

// get response text from the page based on the postdata and parse the xml response text from the page.
function executePage(thePage,postdata)
{
	// change the URL variables based on the page string.
	changeURLVariable(thePage);
	
	// create the postdata array if one does not exist.
	if(!postdata)
	{
		var postdata = {};
	}
	
	// get and parse response text from the page using the postdata 
	$.post('./php/page.php?page='+thePage, postdata, function(formResponseText)
	{
		// wrap response text in xml tag and create a jquery object.
		formResponseText = '<xml version="1.0" >' + formResponseText + '</xml>';
		$formResponseText = $(formResponseText);

		// find each variable in the xml and set a session variable with variable name and value.
		$formResponseText.find('variable').each(function()
		{
			SESSION[$(this).attr('name')] = $(this).text();
		});
		
		// find each javascript tag in the xml and evaluate the javascript text.
		$formResponseText.find('javascript').each(function()
		{
			$this=$(this);
			eval($this.text());
		});
		
		// find each action tag in the xml and execute the action.
		$formResponseText.find('action').each(function()
		{
			// create jquery object from action tag and get action type.
			$this=$(this);
			$type=$this.attr('type');
			
			
			switch($type)
			{
				case 'javascript': // if type is javascript evaluate the javascript.
					eval($this.text());
					break;
				case 'popup': // if the type is popup then create a popup from tag.
					popupMessage($this);
					break;
			}
		});
		
		// find each outermost div in the xml and parse the div xml.
		$formResponseText.find('> div').each(function()
		{
			// create jquery object from tab and parse the xml with the updateDiv function.
			$this=$(this);
			updateDiv($this);
		});
		
	});
}

// parse xml and update div accordingly.
function updateDiv($xml)
{
	// get div id
	id = $xml.attr('id');
	
	// if the div has inner html update the div with the same id to have the same inner html.
	if(htmltext = $xml.html())
	{
		$('#'+id).html(htmltext);
	}
	
	// if the div has a script tag within it, evaluate the javascript in that tag.
	if(scripttext=$('#'+id).find("script").html())
	{
		eval(scripttext);
	}
	
	// if the div's scrollTo attribute is true then scroll to the <a> tag with the name equal to the div's id.
	if($xml.attr('scrollTo')=='true')
	{
		var aTag = $("a[name='"+ id +"']");
		$('html body').animate({scrollTop: aTag.offset().top},'slow');
	}
	
	// if the div's flash attribute is true then fade out and fade in the div after the html and body are ready.
	if($xml.attr('flash')=='true')
	{
		$('html body').promise().done(function(){$("#"+id).fadeOut(300).fadeIn(300);});
	}
	
	// if the div's shake attribute is true then shake the div.
	if($xml.attr('shake')=='true')
	{
		$("#"+id).effect( "shake" );
	}
	
	// if the div's focus attribute is true then focus on the first visible input in the div.
	if($xml.attr('focus')=='true')
	{
		$("#"+id).find('input').filter(':visible:first').focus();
	}
}

// create a popup message based on the given xml.
function popupMessage($xml)
{
	// create popup from the name, title, and text of the xml. store this popup as a jquery object in the popup array with the key of the popup's name.
	name = $xml.attr('name');
	title = decodeURIComponent($xml.attr('title'));
	text = $xml.find('text').html();
	popup[name] = $('<div id="dialogDiv" title="'+title+'"><span id="itemPopupDialogMsg">'+text+'</span></div>');
	
	// set the width of the popup based on the window width.
	if($(window).width()<640)
	{
		$width = $(window).width();
	}
	else
	{
		$width = 640;
	}
	
	// create the dialog from the popup jquery object.
	popup[name].dialog({
		modal: true,
		draggable: false,
		resizable: false,
		width: $width,
		beforeClose: function() { $(this).dialog('destroy').remove(); }
	});
	
	// if the xml has a button tag add the button code to the dialog.
	$button=$xml.find('button');
	if($button)
	{
		$button_code = "popup[name].dialog({ buttons: [ "+$button.text()+" ] });";
		eval($button_code);
	}
	
	// open the dialog.
	popup[name].dialog("open");
}