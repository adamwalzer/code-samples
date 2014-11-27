<?php

session_start();

include "stateslist.php";

include "connect.php";

//$loc_id = $_GET['loc_id'];

$order_id = $_SESSION['order_id'];
$loc_id = $_SESSION['loc_id'];
$rest_id = $_SESSION['rest_id'];

include "reviewordertopdiv.php";

$outputtext .= "
	<div id='main_right' class='col span_3_of_4' scrollTo='true'>
		<a name='main_right'></a>
	";

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
			<h2>Order Summary</h2>
			<form id='place_order_form' onSubmit='executePage(".'"placeorderpopup"'.");return false;' method='post'>
			<div id='summarytablediv'>
			<div class='section group header desktop'>
				<div class='col span_1_of_20'>
					&nbsp;
				</div>
				<div class='col span_2_of_20'>
					Qty
				</div>
				<div class='col span_3_of_20'>
					Size
				</div>
				<div class='col span_4_of_20'>
					Item
				</div>
				<div class='col span_4_of_20'>
					Options
				</div>
				<div class='col span_4_of_20'>
					Notes
				</div>
				<div class='col span_2_of_20'>
					Price
				</div>
			</div>
			";
		
		$query_get_item = "
			SELECT * FROM Customer_Order_Item 
			WHERE order_id='$order_id' 
			ORDER BY order_item_id
			";
		$result_get_item = mysqli_query($dbc, $query_get_item);
		
		$row = 0;
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
						$options .= ',<br/>'.$option_row['option_quantity']." ".$option_row['option_name'];
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
						$options .= ',<br/>'.$option_row['option_name'];
					}
				}
			}
			
			$outputtext .= "
					<div class='section group row_".$row." desktop'>
						<div class='col span_1_of_20'>
							<i onClick='executePage(".'"editorderitempopup&review_order=true&order_item_id='.$item_row['order_item_id'].'"'.")' class='icon-pencil'></i>
							<i onClick='executePage(".'"deleteitem&review_order=true&order_item_id='.$item_row['order_item_id'].'"'.")' class='icon-cancel'></i>
						</div>
						<div class='col span_2_of_20'>
							".$item_row['quantity']."
						</div>
						<div class='col span_3_of_20'>
							".$item_row['size_name']."&nbsp;
						</div>
						<div class='col span_4_of_20'>
							".$item_row['item_name']."
						</div>
						<div class='col span_4_of_20'>
							".$options."&nbsp;
						</div>
						<div class='col span_4_of_20'>
							".$item_row['notes']."&nbsp;
						</div>
						<div class='col span_2_of_20 money'>
							$".$item_row['total_item_cost']."
						</div>
					</div>
					<div class='section group row_".$row." tablet'>
						<div class='col span_2_of_20'>
							<i onClick='executePage(".'"editorderitempopup&review_order=true&order_item_id='.$item_row['order_item_id'].'"'.")' class='icon-pencil'></i>
							<i onClick='executePage(".'"deleteitem&review_order=true&order_item_id='.$item_row['order_item_id'].'"'.")' class='icon-cancel'></i>
						</div>
						<div class='col span_18_of_20'>
							".$item_row['quantity']."
							&nbsp;".$item_row['size_name']."
							&nbsp;".$item_row['item_name']."
							<br/>
							";
			if($options)
			{
				$outputtext .= $options."
							<br/>
							";
			}
			
			if($item_row['notes'])
			{				
				$outputtext .= $item_row['notes']."
							<br/>
							";
			}
							
			$outputtext .= "$".$item_row['total_item_cost']."
						</div>
					</div>
				";
			
			$row++;
			$row = $row % 2;
		}
		
		$outputtext .= "
				<div class='section group subtotal'>
					<div class='col span_20_of_20'>
						subtotal: $".$order_row['subtotal']."
						<br/>
				";
				
		if($order_row['delivery']=='delivery' && $order_row['subtotal'] < $order_row['delivery_minimum'])
		{
			$tominimum = $order_row['delivery_minimum'] - $order_row['subtotal'];
			
			$outputtext .= " 
				$".$tominimum." to delivery minimum
				<br/>
				";
		}
		
		if($order_row['delivery']=='pickup' && $order_row['subtotal'] < $order_row['pickup_minimum'])
		{
			$tominimum = $order_row['pickup_minimum'] - $order_row['subtotal'];
			
			$outputtext .= " 
				$".$tominimum." to order minimum
				<br/>
				";
		}
		
		$outputtext .= "
				tax: $".$order_row['tax']."
				";
				
		if($order_row['delivery'] == 'delivery')
		{
			$outputtext .= "
				<br/>
				delivery fee: ";
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
		}
				
		$outputtext .= "
					<br/>
					<a href='javascript:executePage(".'"editordertippopup"'.")' class='orderinfo'>tip: $".$order_row['tip']."</a>
					<br/>
					total: $".$order_row['total']."
					<br/>
				</div>
			</div>
			<br/>
			<center>
			<div id='orderinfodiv'>
			";
			
		if($order_row['delivery'] == 'delivery')
		{
			$outputtext .= "
				<a href='javascript:executePage(".'"editorderpopup"'.")' class='orderinfo'>
					<h3>Order Info</h3>
					".$order_row['first_name']." ".$order_row['last_name']."
					<br/>
					".$order_row['email']."
					<br/>
					".$order_row['phone']."
					<br/>
					<br/>
					<h3>Delivery Address</h3>
					";
   
		   if($order_row['del_on_beach']=='checked')
		   {
				$outputtext .= "On The Beach At ";
		   }
   
		   $outputtext .= $order_row['del_address']."
					<br/>
					".$order_row['del_city'].", ".$order_row['del_state']." ".$order_row['del_zip']."
				</a>
				";
		}
		elseif($order_row['delivery'] == 'pickup')
		{
			$outputtext .= "
				<a href='javascript:executePage(".'"editorderpopup"'.")' class='orderinfo'>
					<h3>Order Info</h3>
					".$order_row['first_name']." ".$order_row['last_name']."
					<br/>
					".$order_row['email']."
					<br/>
					".$order_row['phone']."
					<br/>
					Pickup Order
				</a>
				";
		}

		$outputtext .= "
			</div>
			<br/>
			<br/>
			<br/>
			";
			
		$outputtext .= "
			<table id='registrationtable'>
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
					<td colspan='2' class='test' id='card_type_test'>
						<select type='text' id='card_type' name='card_type' onchange='testCardType(this)' tabindex=15 >
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
					<td colspan='2' class='test' id='card_number_test'>
						<input type='text' id='card_number' class='card_number' onkeyup='testCardNumber(this)' placeholder='0000 0000 0000 0000' tabindex=16 />
						<div></div>
					</td>
				</tr>
				<tr>
					<td colspan='2' class='test' id='confirm_number_test'>
						<input type='text' id='confirm_number' onkeyup='testConfirmCardNumber(this)' placeholder='0000 0000 0000 0000' tabindex=17 />
						<div></div>
					</td>
				</tr>
				<tr>
					<td colspan='1' class='test' id='exp_date_test'>
						<input type='text' id='exp_date' class='exp_month' onkeyup='testExpMonth(this)' placeholder='MM' tabindex=18 /> / 
						<input type='text' id='exp_date' class='exp_year' onkeyup='testExpYear(this)' placeholder='YY' tabindex=19 />
						<div></div>
					</td>
					<td colspan='1' class='test' id='cvc_test'>
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
				testForm('#place_order_form');
				checkForm('#place_order_form');
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

$outputtext .= "</div>";

//mysqli_close($dbc);

?>