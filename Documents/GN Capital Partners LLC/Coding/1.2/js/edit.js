var input = new Array();
var copyOptionsPopupDialog;

function changeLocation(theSelect)
{
	var postdata = {'loc_id': theSelect.val()}
	
	executePage('setlocation',postdata);
}

function changeRestaurant(theSelect)
{
	var postdata = {'rest_id': theSelect.val()}
	
	executePage('setrestaurant',postdata);
}

function copyOptionsPopup(item_id)
{
	var postdata = {};
	postdata['item_id'] = item_id;
	$.post('../php/copyoptionspopup.php', postdata, function(copyOptionsPopupResponseTxt)
	{
    	// do something here with the returnedData
		//if(!editOrderPopupDialog)
		//{
			copyOptionsPopupDialog = $('<div id="dialogDiv" title="Edit Order"><span id="itemPopupDialogMsg">'+copyOptionsPopupResponseTxt+'</span></div>');

			copyOptionsPopupDialog.dialog({
				modal: true,
				draggable: false,
				resizable: false,
				width: 640,
				beforeClose: function() { $(this).dialog('destroy').remove(); }
			});
		//}
	
		copyOptionsPopupDialog.dialog("open");
	});
}

function copyOptions(theForm)
{
	var postdata = {};
	var inputs = $(theForm).find('input, textarea, select').not(':input[type=button], :input[type=submit], :input[type=reset]');
    $(inputs).each(function()
	{
		$this = $(this);
		if($this.attr('type')=="radio")
		{
			postdata[this.id] = $this.is(':checked'); 
		}
		else if($this.attr('type')=="checkbox")
		{
			postdata[this.id] = $this.is(':checked'); 
		}
		else
		{
			postdata[this.id] = this.value; // This is the jquery object of the input, do what you will
			//alert(this.id);
			//alert(postdata[this.id]);
		}
	});
	
	$.post('../php/copyoptions.php', postdata, function(responseTxt)
	{
		//alert(responseTxt);
		
		$('#mainbody').load('../php/edititempage.php?loc_id='+postdata['loc_id']+'&rest_id='+postdata['rest_id']+'&item_id='+postdata['item_id']+' #main');
		
		copyOptionsPopupDialog.dialog("close");
	});
}

function deleteDeliveryLocation(theIcon)
{
	$theIcon = $(theIcon);
	$tr = $theIcon.closest('tr');
	$tr.remove();
}

function deleteRow(theIcon)
{
	$theIcon = $(theIcon);
	$tr = $theIcon.closest('tr');
	$tr.remove();
}

function addDeliveryLocation(theIcon)
{
	$theIcon = $(theIcon);
	$tr = $theIcon.closest('tr');
	$outputtext = "<tr class='delivery-location'><td><input type='hidden' value='::L' /><i onClick='deleteDeliveryLocation(this)' class='icon-cancel'></i><i onClick='addDeliveryLocation(this)' class='icon-add'></i></td><td><table id='registrationtable'><tr><th colspan='2' class='optional' id='current_city_test'>City</th><th colspan='2' class='center'><input type='text' id='current_city' name='current_city' placeholder='City' size='30' /></th></tr><tr><th colspan='2' class='optional' id='current_state_test'>State</th><th colspan='2' class='center'>";
	
	$statename = "Select Your State...";

	$outputtext += "<select type='text' id='current_state' name='current_state' >";
	$outputtext += "<option selected='selected'>"+$statename+"</option>";
	
	$states = statesList();
	
	for($key in $states)
	{
		$outputtext += "<option value='"+$key+"'>"+$states[$key]+"</option>";
	}

	$outputtext += "</select>";
	$outputtext += "</th></tr><tr><th colspan='2' class='optional' id='current_zip_test'>Zip Code</th><th colspan='2' class='center'><input type='text' id='current_zip' name='current_zip' placeholder='Zip Code' size='30' /></th></tr><tr><th colspan='2' class='optional' id='delivery_minimum_test'>Delivery Minimum</th><th colspan='2' class='center'><input type='number' step='.01' min='0' id='delivery_minimum' name='delivery_minimum' placeholder='Delivery Minimum' size='30' /></th></tr><tr><th colspan='2' class='optional' id='delivery_fee_test'>Delivery Fee</th><th colspan='2' class='center'><input type='number' step='.01' min='0' id='delivery_fee' name='delivery_fee' placeholder='Delivery Fee' size='30' /></th></tr></table></td></tr>";
	
	$tr.after($outputtext);
}

function addCategoryDisableHours(theIcon)
{
	$theIcon = $(theIcon);
	$tr = $theIcon.closest('tr');
	
	$outputtext = "<tr class='delivery-location'>";
		$outputtext += "<td>";
			$outputtext += "<input type='hidden' value='::H' />";
			$outputtext += "<i onClick='deleteRow(this)' class='icon-cancel'></i>";
			$outputtext += "<i onClick='addCategoryDisableHours(this)' class='icon-add'></i>";
		$outputtext += "</td>";
		$outputtext += "<td>";
			$outputtext += "<table id='registrationtable'>";
				$outputtext += "<tr>";
					$outputtext += "<th colspan='2' class='optional' id='start_day_test'>Start Day</th>";
					$outputtext += "<th colspan='2' class='center'>";
		
	$day_num = 0;
	$days = daysList();

	$outputtext += "<select type='text' id='start_day' name='start_day' >";

	for($key in $days)
	{
		$outputtext += "<option value='"+$key+"' ";
		if($key==$day_num)
		{
			$outputtext += "selected='selected' ";
		}
		$outputtext += ">"+$days[$key]+"</option>";
	}

	$outputtext += "</select>";

		$outputtext += "</th>";
	$outputtext += "</tr>";
	$outputtext += "<tr>";
		$outputtext += "<th colspan='2' class='optional' id='start_time_test'>Zip Code</th>";
		$outputtext += "<th colspan='2' class='center'><input type='time' id='start_time' name='start_time' size='30' /></th>";
	$outputtext += "</tr>";
	$outputtext += "<tr>";
		$outputtext += "<th colspan='2' class='optional' id='start_day_test'>Start Day</th>";
		$outputtext += "<th colspan='2' class='center'>";
		
	$stop_day_num = 1;
	$days = daysList();

	$outputtext += "<select type='text' id='stop_day' name='stop_day' >";

	for($key in $days)
	{
		$outputtext += "<option value='"+$key+"' ";
		if($key==$stop_day_num)
		{
			$outputtext += "selected='selected' ";
		}
		$outputtext += ">"+$days[$key]+"</option>";
	}

	$outputtext += "</select>";

					$outputtext += "</th>";
				$outputtext += "</tr>";
				$outputtext += "<tr>";
					$outputtext += "<th colspan='2' class='optional' id='stop_time_test'>Zip Code</th>";
					$outputtext += "<th colspan='2' class='center'><input type='time' id='stop_time' name='stop_time' size='30' /></th>";
				$outputtext += "</tr>";
			$outputtext += "</table>";
		$outputtext += "</td>";
	$outputtext += "</tr>";
	
	$tr.after($outputtext);
}

function deleteCardType(theIcon)
{
	$theIcon = $(theIcon);
	$tr = $theIcon.closest('tr');
	$tr.remove();
}

function addCardType(theIcon)
{
	$theIcon = $(theIcon);
	$tr = $theIcon.closest('tr');
	$outputtext = "<tr class='delivery-location'><td><input type='hidden' value='::C' /><i onClick='deleteCardType(this)' class='icon-cancel'></i><i onClick='addCardType(this)' class='icon-add'></i></td><td><table id='registrationtable'><tr><th colspan='2' class='optional' id='card_type_test'>City</th><th colspan='2' class='center'><input type='text' id='card_type' name='card_type' placeholder='Card Type' size='30' /></th></tr></table></td></tr>";
	
	$tr.after($outputtext);
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

function deletegroup(li,ol)
{
	
	var deleteGroupDialog = $('<div id="dialogDiv" title="Delete Group?"><span id="dialogMsg">Are you sure you want to delete this group? Any options inside it will be deleted as well.</span></div>');
	
	deleteGroupDialog.dialog({
		modal: true,
		draggable: false,
		resizable: false,
		buttons: [ { text: "Yes", click: function() { deletethis(li,ol); $( this ).dialog( "close" ); } }, { text: "No", click: function() { $( this ).dialog( "close" ); } } ]
	});
	
	//$('#dialogMsg').text("Are you sure you want to delete this subcategory? Any items inside it will be deleted as well.");
	deleteGroupDialog.dialog("open");
}

function deleteoption(li,ol)
{
	
	var deleteOptionDialog = $('<div id="dialogDiv" title="Delete Option?"><span id="dialogMsg">Are you sure you want to delete this option?</span></div>');
	
	deleteOptionDialog.dialog({
		modal: true,
		draggable: false,
		resizable: false,
		buttons: [ { text: "Yes", click: function() { deletethis(li,ol); $( this ).dialog( "close" ); } }, { text: "No", click: function() { $( this ).dialog( "close" ); } } ]
	});
	
	//$('#dialogMsg').text("Are you sure you want to delete this item?");
	deleteOptionDialog.dialog("open");
}

function loadMenuEdit()
{	
	ga('send', 'event', 'loadMenuEdit', 'click', 'loadMenuEdit', {'hitCallback':
     function () {
			categorySortable();
			subcategorySortable();
			itemSortable();
	 }
	});
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
	
	/*
	var output = "";
	for (var i=800;i<input.length;i++)
	{
		output += input[i];
	}
	alert(output);
	*/
	
	$.post('./php/savemenu.php', {'input': input}, function(responseTxt)
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

function saveItem()
{
	//serializeHTML = String($("ol.category").sortable('serialize').get());
	
	var input = new Array();
	var i = 0;
	
	$("div#mainbody :input").each(function(){
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
	
	//alert(input);

	$.post('../php/saveitem.php', {'input': input}, function(responseTxt)
	{
		//alert(responseTxt);
		
		var saveItemDialog = $('<div id="dialogDiv" title="Options Saved!"><span id="dialogMsg">'+responseTxt+'</span></div>');
	
		saveItemDialog.dialog({
			modal: true,
			draggable: false,
			resizable: false,
			buttons: [ { text: "Thanks", click: function() { $( this ).dialog( "close" ); } } ]
		});
	
		//$('#dialogMsg').text("Are you sure you want to delete this item?");
		saveItemDialog.dialog("open");
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

function optionSortable()
{

	$(function() {
		var adjustment

		$("ol.option").sortable({
		  group: 'option',
		  handle: 'i.icon-move-option',
		  // animation on drop
		  onDrop: function  (item, container, _super) {
			var clonedItem = $('<li/>').css({height: 0})
			item.before(clonedItem)
			clonedItem.animate({'height': item.height()})
		
			item.animate(clonedItem.position(), function  () {
			  clonedItem.detach()
			  _super(item)
			})
		
			highoption++
			var itemli = "<i class='icon-move-option'></i><table id='menutable'><tr><td class='firstcol'><input type='hidden' id='option_id' placeholder='option_id' value=',,"+highoption+"' /><input type='text' id='option' placeholder='Option' /></td><td><input type='text' id='option_cost' placeholder='Price' /></td><td><input type='checkbox' id='option_checked' />Check by default</td></tr></table><i onClick='var li = this.parentNode; var ol = li.parentNode; deleteoption(li,ol)' class='icon-cancel'></i>"
			var itemlipallet = "New Option"
		
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

}

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
		var itemli = "<i class='icon-move-item'></i><table id='menutable'><colgroup><col width='100%'><col span='5' nowrap></colgroup><tr><td class='firstcol'><input type='hidden' id='item_id' placeholder='item_id' value=',,"+highitem+"' /><input type='text' id='item' placeholder='Item' value='' /></td><td><input type='text' id='price_1' class='size' placeholder='Price 1' value='' /></td><td><input type='text' id='price_2' class='size' placeholder='Price 2' value='' /></td><td><input type='text' id='price_3' class='size' placeholder='Price 3' value='' /></td><td><input type='text' id='price_4' class='size' placeholder='Price 4' value='' /></td><td><input type='text' id='price_5' class='size' placeholder='Price 5' value='' /></td></tr><tr><td class='allcol' colspan='6'><input type='text' id='item_desc' placeholder='Item Description' class='description' value='' /><br><input type='text' id='allergen' placeholder='allergen' value='' /></td></tr></table><i onClick='var li = this.parentNode; var ol = li.parentNode; deleteitem(li,ol)' class='icon-cancel'></i>"
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

$(function() {
	var adjustment

	$("ol.group").sortable({
	  group: 'group',
	  handle: 'i.icon-move-group',
	  // animation on drop
	  onDrop: function  (item, container, _super) {
		var clonedItem = $('<li/>').css({height: 0})
		item.before(clonedItem)
		clonedItem.animate({'height': item.height()})
	
		item.animate(clonedItem.position(), function  () {
		  clonedItem.detach()
		  _super(item)
		})
		
		highgroup++
		var subcatli = "<i class='icon-move-group'></i><table id='menutable'><tr><td class='firstcol' width='25%'><input type='hidden' id='group_id' placeholder='group_id' value=';;"+highgroup+"' /><input type='text' id='groupname' placeholder='Group Name' value='' /></td><td width='25%'><input type='radio' name='grouptype"+highgroup+"' id='check_boxes' checked>Check Boxes</td><td width='25%'><input type='radio' name='grouptype"+highgroup+"' id='radio_buttons' >Radio Buttons</td><td width='19%'><input type='radio' name='grouptype"+highgroup+"' id='quantity' >Quantities</td><td width='6%'><input type='number' id='quanitynumber' placeholder='12' /></td></tr></table><i onClick='var li = this.parentNode; var ol = li.parentNode; deletegroup(li,ol)' class='icon-cancel'></i><ol class='option'></ol>"
		var subcatlipallet = "New Group"
		
		if(item.text()==subcatlipallet)
		{
			item.html(subcatli)
		}
		
		optionSortable()
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
			  $("<li><i class='icon-move-group'></i>New Group</li>").insertAfter(item)
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
	$("ol.group-pallet").sortable({
	  drop: false,
	  group: 'group',
	  handle: 'i.icon-move-group'
	})
});

$(function() {
	var adjustment

	$("ol.option").sortable({
	  group: 'option',
	  handle: 'i.icon-move-option',
	  // animation on drop
	  onDrop: function  (item, container, _super) {
		var clonedItem = $('<li/>').css({height: 0})
		item.before(clonedItem)
		clonedItem.animate({'height': item.height()})
		
		item.animate(clonedItem.position(), function  () {
		  clonedItem.detach()
		  _super(item)
		})
		
		//alert(1)
		
		highoption++
		
		//alert(1.1)
		
		var itemli = "<i class='icon-move-option'></i><table id='menutable'><tr><td class='firstcol'><input type='hidden' id='option_id' placeholder='option_id' value=',,"+highoption+"' /><input type='text' id='option' placeholder='Option' /></td><td><input type='text' id='option_cost' placeholder='Price' /></td><td><input type='checkbox' id='option_checked' />Check by default</td></tr></table><i onClick='var li = this.parentNode; var ol = li.parentNode; deleteoption(li,ol)' class='icon-cancel'></i>"
		var itemlipallet = "New Option"
		
		//alert(2)
		
		if(item.text()==itemlipallet)
		{
			item.html(itemli)
		}
		
		//alert(3)
		
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
	$("ol.option-pallet").sortable({
	  drop: false,
	  group: 'option',
	  handle: 'i.icon-move-option'
	})
});