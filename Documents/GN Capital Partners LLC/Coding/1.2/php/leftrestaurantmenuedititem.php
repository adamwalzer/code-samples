<?php

include "leftrestaurantmenu.php";

$item_id = $_GET['item_id'];
	
$outputtext .= "
	<br>
	<div id='pallet'>
		<ol class='group-pallet'>
			<li><i class='icon-move-group'></i>New Group</li>
		</ol>
	
		<ol class='option-pallet'>
			<li><i class='icon-move-option'></i>New Option</li>
		</ol>
	
		<br>
	
		<center>
		<input type='button' id='stylebutton' onClick='copyOptionsPopup($item_id)' name='Copy Options' value='Copy Options' />
		<br><br>
		<input type='button' id='stylebutton' onClick='saveItem()' name='Save Item' value='Save Item' />
		</center>
	</div>
	";

?>