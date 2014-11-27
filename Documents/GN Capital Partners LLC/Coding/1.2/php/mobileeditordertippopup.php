<?php

set_time_limit(0);
ignore_user_abort(1);

session_start();

include ('connect.php');

$order_id = $_SESSION['order_id'];

$query_get_order = "
	SELECT * FROM Customer_Order 
	WHERE (order_id='$order_id') 
	LIMIT 1
	";
$result_get_order = mysqli_query($dbc, $query_get_order);
$order_row = mysqli_fetch_array($result_get_order, MYSQLI_ASSOC);//Assign the result of this query to SESSION Global Variable


$tip = $order_row['tip'];
$loc_id = $order_row['loc_id'];
$rest_id = $order_row['rest_id'];

$outputtext .= "
<script>
	subtotal = ".$order_row['subtotal'].";
	tax = ".$order_row['tax'].";
	";
	
if($order_row['delivery'] == 'delivery')
{
	$outputtext .= "
		delivery_fee = ".$order_row['delivery_fee'].";
		";
}
else
{
	$outputtext .= "
		delivery_fee = 0;
		";
}

$outputtext .= "
	tip = ".$order_row['tip'].";
	total = ".$order_row['total'].";
</script>

<center>
<form onSubmit='mobileEditOrderTip(this);return false;' method='post' class='updateinfo_form'>
	<br>
	
	<table id='registrationtable'>
	
	<tr>
	<th colspan='1' class='center'>&nbsp;</th>
	<th colspan='2' class='optional'>Current Subtotal</th>
	<th colspan='2' class='center'>$".$order_row['subtotal']."</th>
	<th colspan='1' class='center'>&nbsp;</th>
	</tr>
	
	<tr>
	<th colspan='1' class='center'>&nbsp;</th>
	<th colspan='2' class='optional'>Current Tax</th>
	<th colspan='2' class='center'>$".$order_row['tax']."</th>
	<th colspan='1' class='center'>&nbsp;</th>
	</tr>
	";
	
if($order_row['delivery'] == 'delivery')
		{
			$outputtext .= "	
				<tr>
				<th colspan='1' class='center'>&nbsp;</th>
				<th colspan='2' class='optional'>Delivery Fee</th>
				<th colspan='2' class='center'>
				";
				
			if($order_row['delivery_fee']==0)
			{
				$outputtext .= "free
					";
			}
			else
			{
				$outputtext .= "$".$order_row['delivery_fee']."
				";
			}
				
			$outputtext .= "
				</th>
				<th colspan='1' class='center'>&nbsp;</th>
				</tr>
				";
		}	
	
$outputtext .= "
	<tr>
	<th colspan='1' class='center'>&nbsp;</th>
	<th colspan='2' class='test' id='zipcodetest'>Tip</th>
	<th colspan='2' class='center'><input type='number' step='0.01' id='orderTip' name='orderTip' onkeyup='calculateTotal(subtotal,tax,delivery_fee,this.value);' onchange='calculateTotal(subtotal,tax,delivery_fee,this.value);' placeholder='tip' value='".$order_row['tip']."' size='30' /></th>
	<th colspan='1' class='center'>&nbsp;</th>
	</tr>
	
	<tr>
	<th colspan='1' class='center'>&nbsp;</th>
	<th colspan='2' class='optional'>Current Total</th>
	<th colspan='2' id='orderTotal' class='center'>$".$order_row['total']."</th>
	<th colspan='1' class='center'>&nbsp;</th>
	</tr>
	
	<tr>
	<th colspan='1' class='center'>&nbsp;</th>
	<th colspan='2' class='center'><input type='button' onclick='calculateTip(10,subtotal,tax,delivery_fee)' id='registerbutton' value='10%' title='10%' /></th>
	<th colspan='2' class='center'><input type='button' onclick='calculateTip(15,subtotal,tax,delivery_fee)' id='registerbutton' value='15%' title='15%' /></th>
	<th colspan='1' class='center'>&nbsp;</th>
	</tr>
	
	<tr>
	<th colspan='1' class='center'>&nbsp;</th>
	<th colspan='2' class='center'><input type='button' onclick='calculateTip(20,subtotal,tax,delivery_fee)' id='registerbutton' value='20%' title='20%' /></th>
	<th colspan='2' class='center'><input type='button' onclick='roundTotal(total,subtotal,tax,delivery_fee)' id='registerbutton' value='Round Total' title='Round Total' /></th>
	<th colspan='1' class='center'>&nbsp;</th>
	</tr>
	
	<tr>
	<th colspan='6' class='center'>&nbsp;</th>
	</tr>
	
	<tr>
	<th colspan='2' class='center'>&nbsp;</th>
	<th colspan='2' class='center'><input type='submit' id='registerbutton' value='Save Changes' title='Save Changes' /></th>
	<th colspan='2' class='center'>&nbsp;</th>
	</tr>
	
	</table>
	
</form>
<center>
";



/// var_dump($error);
mysqli_close($dbc);

echo $outputtext;

?>