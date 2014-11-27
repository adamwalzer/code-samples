<?php

$outputtext = "
	<div type='popup' name='delete_order' title='Delete Order?'>
		<button>
			{ text: 'Yes', click: function() { executePage('deleteorder'); executePage('categoriesdiv&loc_id=".$_SESSION['loc_id']."'); $( this ).dialog( 'close' ); } }, { text: 'No', click: function() { $( this ).dialog( 'close' ); } }
		</button>
		<text>
			In order to view other restaurants, you will have to delete this order. Do you want to delete this order?
		</text>
	</div>
	";

?>