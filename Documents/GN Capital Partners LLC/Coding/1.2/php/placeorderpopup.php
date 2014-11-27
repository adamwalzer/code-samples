<?php

$outputtext = "
	<div type='popup' name='place_order' title='Place Your Order?'>
		<button>
			{ text: 'Yes', click: function() { placeOrder('place_order_form'); $( this ).dialog( 'close' ); } }, { text: 'No', click: function() { $( this ).dialog( 'close' ); } }
		</button>
		<text>
			Are you sure you would like to place your order?
		</text>
	</div>
	";

?>