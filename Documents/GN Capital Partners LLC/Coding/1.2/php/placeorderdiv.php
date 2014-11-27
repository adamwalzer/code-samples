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
			<form id='placeorderform' onSubmit='placeOrder(this);return false;' method='post'>
			<table id='fullorder'>
				<colgroup>
					<col width='5%'>
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
					<col width='28%'>
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
				if($option_row['checked'] == 'checked')
				{
					if($options == '')
					{
						$options = $option_row['option_name'];
					}
					else
					{
						$options .= '<br>'.$option_row['option_name'];
					}
				}
			}
			
			$outputtext .= "
					<tr>
						<td>
							<i onClick='editOrderItemPlaceOrderPopup(".$item_row['order_item_id'].',"'.$encodedname.'"'.")' class='icon-pencil'></i>
							<i onClick='deleteItemPlaceOrderPopup(".$item_row['order_item_id'].")' class='icon-cancel'></i>
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
			<br>
			<table id='fullorder'>
				<tr>
					<td colspan='1' rowspan='9'>
					";
					
		if($order_row['delivery'] == 'delivery')
		{
			$outputtext .= "
				<a href='javascript:popupEditOrder()' class='orderinfo'>
					Delivery Order
					<br>
					".$order_row['first_name']." ".$order_row['last_name']."
					<br>
					".$order_row['phone']."
					<br>
					".$order_row['email']."
					<br>
					".$order_row['del_address']."
					<br>
					".$order_row['del_city'].", ".$order_row['del_state']." ".$order_row['del_zip']."
				</a>
				";
		}
		elseif($order_row['delivery'] == 'pickup')
		{
			$outputtext .= "
				<a href='javascript:popupEditOrder()' class='orderinfo'>
				Pickup Order
				<br>
				".$order_row['first_name']." ".$order_row['last_name']."
				<br>
				".$order_row['phone']."
				<br>
				".$order_row['email']."
				</a>
				";
		}	
					
		$outputtext .= "
					</td>
					<td colspan='1'>
						Name On Card
					</td>
					<td>
						<input type='text' id='nameoncard' placeholder='Name On Card' value='".$order_row['bill_name']."' size='18' />
					</td>
				</tr>
				<tr>
					<td colspan='1'>
						Billing Address
					</td>
					<td>
						<input type='text' id='billaddress' placeholder='Billing Address' value='".$order_row['bill_address']."' size='18' />
					</td>
				</tr>
				<tr>
					<td colspan='1'>
						Billing City
					</td>
					<td>
						<input type='text' id='billcity' placeholder='Billing City' value='".$order_row['bill_city']."' size='18' />
					</td>
				</tr>
				<tr>
					<td colspan='1'>
						Billing State
					</td>
					<td>
					";
		
		$states = statesList();
		$stateletters = $_SESSION['bill_state'];
		
		if($stateletters)
		{
			$statename = $states[$stateletters];
		}
		else
		{
			$statename = "Select Your State...";
		}

		$outputtext .= "<select type='text' id='billstate' name='billstate' onchange='' >";
		$outputtext .= "<option selected='selected' value='".$stateletters."'>".$statename."</option>";
						
		$outputtext .= "</option>";

		foreach($states as $key=>$value)
		{
			$outputtext .= "<option value='".$key."'>".$value."</option>";
		}

		$outputtext .= "</select>";
		
		$outputtext .= "
					</td>
				</tr>
				<tr>
					<td colspan='1'>
						Billing Zip Code
					</td>
					<td>
						<input type='text' id='billzip' placeholder='Billing Zip Code' value='".$order_row['bill_zip']."' size='18' />
					</td>
				</tr>
				<tr>
					<td colspan='1'>
						Credit Card Type
					</td>
					<td colspan='1'>
						<select type='text' id='cardtype' name='cardtype' onchange='' >";
	
		$query_get_card_types = "SELECT * FROM Card_Type WHERE loc_id='".$order_row['loc_id']."' AND rest_id='".$order_row['rest_id']."' ORDER BY card_type";
		$result_get_card_types = mysqli_query($dbc, $query_get_card_types);
		while($card_type_row = mysqli_fetch_array($result_get_card_types, MYSQLI_ASSOC))
		{
			$outputtext .= "<option value='".$card_type_row['card_type']."'>".$card_type_row['card_type']."</option>";
		}

		$outputtext .= "
						</select>
					</td>
				</tr>
				<tr>
					<td colspan='1'>
						Credit Card Number
					</td>
					<td>
						<input type='text' id='cardnumber' placeholder='0000 0000 0000 0000' size='18' />
					</td>
				</tr>
				<tr>
					<td colspan='1'>
						Confirm Card Number
					</td>
					<td>
						<input type='text' id='confirmnumber' placeholder='0000 0000 0000 0000' size='18' />
					</td>
				</tr>
				<tr>
					<td>
						Exp. Date
					</td>
					<td>
						<input type='text' id='expdate' placeholder='10/20' size='4'/>
					</td>
				</tr>
				<tr>
					<td colspan='3'>&nbsp</td>
				</tr>
				<tr>
					<td colspan='3'>
			";
			
		if($order_row['delivery']=='delivery' && $order_row['subtotal'] < $order_row['delivery_minimum'])
		{
			$outputtext .= "
				Order subtotal must be at least $".$order_row['delivery_minimum']." to place order.
				<br>
				<input type='submit' id='stylebutton' name='Place Order' value='Place Order' disabled />
				";
		}
		else
		{
			$outputtext .= "
				<input type='submit' id='stylebutton' name='Place Order' value='Place Order' />
				";
		}
		
		$outputtext .= "
					</td>
				</tr>
				<tr>
					<td colspan='3'>
						<a href='javascript:popupDeleteOrder()'>delete order</a>
					</td>
				</tr>
			</table>
			</form>
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