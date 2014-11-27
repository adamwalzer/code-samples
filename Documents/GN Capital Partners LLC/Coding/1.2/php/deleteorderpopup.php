<?php

$outputtext = "
	<div type='popup' name='delete_order' title='Delete Order?'>
		<button>
			{ text: 'Yes', click: function() { executePage('deleteorder'); $( this ).dialog( 'close' ); } }, { text: 'No', click: function() { $( this ).dialog( 'close' ); } }
		</button>
		<text>
			Are you sure you want to delete this order?
		</text>
	</div>
	";

?>