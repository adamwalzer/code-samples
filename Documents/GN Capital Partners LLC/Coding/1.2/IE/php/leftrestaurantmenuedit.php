<?php

include "leftrestaurantmenu.php";
	
$outputtext .= "
	<br>
	<div id='pallet'>
		<ol class='category-pallet'>
			<li><i class='icon-move-category'></i>New Category</li>
		</ol>
	
		<ol class='subcategory-pallet'>
			<li><i class='icon-move-subcategory'></i>New Subcategory</li>
		</ol>
	
		<ol class='item-pallet'>
			<li><i class='icon-move-item'></i>New Item</li>
		</ol>
	
		<br>
	
		<center>
		<input type='button' id='stylebutton' onClick='saveMenu()' name='Save Menu' value='Save Menu' />
		</center>
	</div>
	";

?>