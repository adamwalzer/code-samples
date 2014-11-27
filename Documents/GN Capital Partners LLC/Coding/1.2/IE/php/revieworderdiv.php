<?php

session_start();

function statesList() {
	$states = array('AL'=>"Alabama",
					'AK'=>"Alaska",
					'AZ'=>"Arizona",
					'AR'=>"Arkansas",
					'CA'=>"California",
					'CO'=>"Colorado",
					'CT'=>"Connecticut",
					'DE'=>"Delaware",
					'DC'=>"District Of Columbia",
					'FL'=>"Florida",
					'GA'=>"Georgia",
					'HI'=>"Hawaii",
					'ID'=>"Idaho",
					'IL'=>"Illinois",
					'IN'=>"Indiana",
					'IA'=>"Iowa",
					'KS'=>"Kansas",
					'KY'=>"Kentucky",
					'LA'=>"Louisiana",
					'ME'=>"Maine",
					'MD'=>"Maryland",
					'MA'=>"Massachusetts",
					'MI'=>"Michigan",
					'MN'=>"Minnesota",
					'MS'=>"Mississippi",
					'MO'=>"Missouri",
					'MT'=>"Montana",
					'NE'=>"Nebraska",
					'NV'=>"Nevada",
					'NH'=>"New Hampshire",
					'NJ'=>"New Jersey",
					'NM'=>"New Mexico",
					'NY'=>"New York",
					'NC'=>"North Carolina",
					'ND'=>"North Dakota",
					'OH'=>"Ohio",
					'OK'=>"Oklahoma",
					'OR'=>"Oregon",
					'PA'=>"Pennsylvania",
					'RI'=>"Rhode Island",
					'SC'=>"South Carolina",
					'SD'=>"South Dakota",
					'TN'=>"Tennessee",
					'TX'=>"Texas",
					'UT'=>"Utah",
					'VT'=>"Vermont",
					'VA'=>"Virginia",
					'WA'=>"Washington",
					'WV'=>"West Virginia",
					'WI'=>"Wisconsin",
					'WY'=>"Wyoming");
	return $states;
}

include "connect.php";

//$loc_id = $_GET['loc_id'];

$order_id = $_SESSION['order_id'];
$loc_id = $_SESSION['loc_id'];
$rest_id = $_SESSION['rest_id'];

$query_get_order = "
	SELECT * FROM Customer_Order 
	WHERE order_id = '$order_id'
	";

$result_get_order = mysqli_query($dbc, $query_get_order);
if(!$result_get_order)
{//If the QUery Failed 
	$outputtext .= 'Query Failed ';
}

if (@mysqli_num_rows($result_get_order) > 0)//if Query is successfull 
{ // A match was made.
	//$_SESSION = mysqli_fetch_array($result_get_category, MYSQLI_ASSOC);//Assign the result of this query to SESSION Global Variable
   
	$outputtext .= "
		<div id='main'>
		";
		
	while($order_row = mysqli_fetch_array($result_get_order, MYSQLI_ASSOC))
	{
		$outputtext .= "
			<br>
			<br>
			<a href='".'javascript:setMain("restaurantpage.php?loc_id='.$loc_id.'&rest_id='.$rest_id.'");showSide()'."' class='orderinfo'>Back To Menu</a>
			<br>
			<br>
			<form id='placeorderform' onSubmit='placeOrderPopup(this);return false;' method='post'>
			<div id='summarytablediv'>
			<table id='fullorder' border='0'>
				<colgroup>
					<col width='7%'>
				</colgroup>
				<colgroup>
					<col width='5%'>
				</colgroup>
				<colgroup>
					<col width='15%'>
				</colgroup>
				<colgroup>
					<col width='20%'>
				</colgroup>
				<colgroup>
					<col width='20%'>
				</colgroup>
				<colgroup>
					<col width='26%'>
				</colgroup>
				<colgroup>
					<col width='7%'>
				</colgroup>
				<tr>
					<th>
						&nbsp;
					</th>
					<th>
						Qty
					</th>
					<th>
						Size
					</th>
					<th>
						Item
					</th>
					<th>
						Options
					</th>
					<th>
						Notes
					</th>
					<th>
						Price
					</th>
				</tr>
			";
		
		$query_get_item = "
			SELECT * FROM Customer_Order_Item 
			WHERE order_id='$order_id' 
			ORDER BY order_item_id
			";
		$result_get_item = mysqli_query($dbc, $query_get_item);
		
		while($item_row = mysqli_fetch_array($result_get_item, MYSQLI_ASSOC))
		{
			$encodedname = rawUrlEncode($item_row['item_name']);
			$order_item_id = $item_row['order_item_id'];
			
			$query_get_option = "
				SELECT * FROM Customer_Order_Item_Option 
				WHERE order_id='$order_id' AND order_item_id='$order_item_id' 
				ORDER BY option_order
				";
			$result_get_option = mysqli_query($dbc, $query_get_option);
			$options = '';
			while($option_row = mysqli_fetch_array($result_get_option, MYSQLI_ASSOC))
			{
				if($option_row['option_quantity'])
				{
					if($options == '')
					{
						$options = $option_row['option_quantity']." ".$option_row['option_name'];
					}
					else
					{
						$options .= ',<br>'.$option_row['option_quantity']." ".$option_row['option_name'];
					}
				}
				elseif($option_row['checked'] == 'checked')
				{
					if($options == '')
					{
						$options = $option_row['option_name'];
					}
					else
					{
						$options .= ',<br>'.$option_row['option_name'];
					}
				}
			}
			
			$outputtext .= "
					<tr>
						<td>
							<a onClick='editOrderItemPlaceOrderPopup(".$item_row['order_item_id'].',"'.$encodedname.'"'.")'>edit</a>
							<a onClick='deleteItemPlaceOrderPopup(".$item_row['order_item_id'].")'>delete</a>
						</td>
						<td>
							".$item_row['quantity']."
						</td>
						<td>
							".$item_row['size_name']."
						</td>
						<td>
							".$item_row['item_name']."
						</td>
						<td>
							".$options."
						</td>
						<td>
							".$item_row['notes']."
						</td>
						<td class='money'>
							$".$item_row['total_item_cost']."
						</td>
					</tr>
				";
		}
		
		$outputtext .= "
				<tr>
					<td colspan='6' class='money'>
						subtotal:
					</td>
					<td class='money'>
						$".$order_row['subtotal']."
					</td>
				</tr>
				";
					
		if($order_row['delivery']=='delivery' && $order_row['subtotal'] < $order_row['delivery_minimum'])
		{
			$tominimum = $order_row['delivery_minimum'] - $order_row['subtotal'];
			
			$outputtext .= " 
				<tr>
					<td colspan='7' class='money'>
						$".$tominimum." to delivery minimum
					</td>
				</tr>
				";
		}
		
		if($order_row['delivery']=='pickup' && $order_row['subtotal'] < $order_row['pickup_minimum'])
		{
			$tominimum = $order_row['pickup_minimum'] - $order_row['subtotal'];
			
			$outputtext .= " 
				<tr>
					<td colspan='7' class='money'>
						$".$tominimum." to order minimum
					</td>
				</tr>
				";
		}
		
		$outputtext .= "
				<tr>
					<td colspan='6' class='money'>
						tax:
					</td>
					<td class='money'>
						$".$order_row['tax']."
					</td>
				</tr>
				";
				
		if($order_row['delivery'] == 'delivery')
		{
			$outputtext .= "
				<tr>
					<td colspan='6' class='money'>
						delivery fee:
					</td>
					<td class='money'>
						$".$order_row['delivery_fee']."
					</td>
				</tr>
				";
		}
				
		$outputtext .= "
				<tr>
					<td colspan='6' class='money'>
						tip:
					</td>
					<td class='money'>
						$".$order_row['tip']."
					</td>
				</tr>
				<tr>
					<td colspan='6' class='money'>
						total:
					</td>
					<td class='money'>
						$".$order_row['total']."
					</td>
				</tr>
			</table>
			</div>
			<br>
			<center>
			<div id='orderinfodiv'>
			";
			
		if($order_row['delivery'] == 'delivery')
		{
			$outputtext .= "
				<a href='javascript:editOrderPlaceOrderPopup()' class='orderinfo'>
					<h3>Order Info</h3>
					".$order_row['first_name']." ".$order_row['last_name']."
					<br>
					".$order_row['email']."
					<br>
					".$order_row['phone']."
					<br>
					<br>
					<h3>Delivery Address</h3>
					";
   
		   if($order_row['del_on_beach']=='checked')
		   {
				$outputtext .= "On The Beach At ";
		   }
   
		   $outputtext .= $order_row['del_address']."
					<br>
					".$order_row['del_city'].", ".$order_row['del_state']." ".$order_row['del_zip']."
				</a>
				";
		}
		elseif($order_row['delivery'] == 'pickup')
		{
			$outputtext .= "
				<a href='javascript:editOrderPlaceOrderPopup()' class='orderinfo'>
					<h3>Order Info</h3>
					".$order_row['first_name']." ".$order_row['last_name']."
					<br>
					".$order_row['email']."
					<br>
					".$order_row['phone']."
					<br>
					Pickup Order
				</a>
				";
		}

		$outputtext .= "
			</div>
			<br>
			<br>
			<br>
			";
			
		$outputtext .= "
			<table id='orderinfo'>
				<colgroup>
					<col width='50%'>
				</colgroup>
				<colgroup>
					<col width='50%'>
				</colgroup>
				<tr>
					<td class='optional' colspan='2'>
						<h3>Billing Info</h3>
					</td>
				</tr>
				<tr>
					<td colspan='1' class='test' id='card_type_test'>
						Card Type
					</td>
					<td align='center'>
						<select type='text' id='card_type' name='card_type' onchange='testCardTypePlaceOrder(this)' tabindex=15 >
						<option value=''>Select your card type...</option>
						";
	
		$query_get_card_types = "
			SELECT * FROM Card_Type 
			WHERE rest_id='".$order_row['rest_id']."' 
			ORDER BY card_type
			";
		$result_get_card_types = mysqli_query($dbc, $query_get_card_types);
		while($card_type_row = mysqli_fetch_array($result_get_card_types, MYSQLI_ASSOC))
		{
			$outputtext .= "<option value='".$card_type_row['card_type']."'>".$card_type_row['card_type']."</option>";
		}

		$outputtext .= "
						</select>
						<div></div>
					</td>
				</tr>
				<tr>
					<td colspan='1' class='test' id='card_number_test'>
						Card Number
					</td>
					<td align='center'>
						<input type='text' id='card_number' class='card_number' onkeyup='testCardNumberPlaceOrder(this)' placeholder='0000 0000 0000 0000' tabindex=16 />
						<div></div>
					</td>
				</tr>
				<tr>
					<td colspan='1' class='test' id='confirm_number_test'>
						Confirm Number
					</td>
					<td align='center'>
						<input type='text' id='confirm_number' onkeyup='testConfirmCardNumberPlaceOrder(this)' placeholder='0000 0000 0000 0000' tabindex=17 />
						<div></div>
					</td>
				</tr>
				<tr>
					<td colspan='1' class='test' id='exp_date_test'>
						Exp. Date
					</td>
					<td align='center'>
						<input type='text' id='exp_date' class='exp_month' onkeyup='testExpMonth(this)' placeholder='MM' tabindex=18 /> / 
						<input type='text' id='exp_date' class='exp_year' onkeyup='testExpYear(this)' placeholder='YY' tabindex=19 />
						<div></div>
					</td>
				</tr>
				<tr>
					<td colspan='1' class='test' id='cvc_test'>
						CVC
					</td>
					<td align='center'>
						<input type='text' id='cvc' class='short cvc' onkeyup='testCVC(this)' placeholder='CVC' tabindex=19 />
						<div></div>
					</td>
				</tr>
				<tr>
					<td colspan='2'>&nbsp</td>
				</tr>
				<tr>
			";
			
		if($order_row['delivery']=='delivery' && $order_row['subtotal'] < $order_row['delivery_minimum'])
		{
			$outputtext .= "
					<td colspan='2' class='test' id='stylebuttontest'>
						Order subtotal must be at least $".$order_row['delivery_minimum']." to place order.
					</td>
				</tr>
				<tr>
					<td align='center' colspan='2'>
						<input type='submit' id='stylebutton' name='Place Order' value='Place Order' disabled tabindex=19 />
					</td>
				";
		}
		elseif($order_row['delivery']=='pickup' && $order_row['subtotal'] < $order_row['pickup_minimum'])
		{
			$outputtext .= "
					<td colspan='2' class='test' id='stylebuttontest'>
						Order subtotal must be at least $".$order_row['pickup_minimum']." to place order.
					</td>
				</tr>
				<tr>
					<td align='center' colspan='2'>
						<input type='submit' id='stylebutton' name='Place Order' value='Place Order' disabled tabindex=19 />
					</td>
				";
		}
		else
		{
			$outputtext .= "
					<td align='center' colspan='2'>
						<input type='submit' id='stylebutton' name='Place Order' value='Place Order' tabindex=19 />
					</td>
				";
		}
		
		$outputtext .= "
				</tr>
				<tr>
					<td colspan='2' class='payment_errors'>
						&nbsp;
					</td>
				</tr>
				<tr>
					<td colspan='2'>
						&nbsp;
					</td>
				</tr>
			</table>
			</center>
			</form>
			<script>
				testFormPlaceOrder();
				checkForm('#placeorderform');
				Stripe.setPublishableKey('pk_test_qIraQ26cGMDkvXfS5FqXlwLC');
				$(document).ready(function() {
					// adding the input field names is the last step, in case an earlier step errors                
					addInputNames();
				});
			</script>
			</div>
			";
	}
}
else
{ 
	$outputtext .= "No Categories In Database";
}

//mysqli_close($dbc);

?>