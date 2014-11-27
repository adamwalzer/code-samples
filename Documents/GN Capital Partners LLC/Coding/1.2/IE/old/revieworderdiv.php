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

$query_get_order = "SELECT * FROM Customer_Order WHERE order_id = '$order_id'";

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
		
		$query_get_item = "SELECT * FROM Customer_Order_Item WHERE order_id='$order_id' ORDER BY order_item_id";
		$result_get_item = mysqli_query($dbc, $query_get_item);
		
		while($item_row = mysqli_fetch_array($result_get_item, MYSQLI_ASSOC))
		{
			$encodedname = rawUrlEncode($item_row['item_name']);
			$order_item_id = $item_row['order_item_id'];
			
			$query_get_option = "SELECT * FROM Customer_Order_Item_Option WHERE order_id='$order_id' AND order_item_id='$order_item_id' ORDER BY option_order";
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
			<center>
			";
			
		if($order_row['delivery'] == 'delivery')
		{
			$outputtext .= "
				<a href='javascript:editOrderPlaceOrderPopup()' class='orderinfo'>
					Order Info
					<br>
					".$order_row['first_name']." ".$order_row['last_name']."
					<br>
					".$order_row['email']."
					<br>
					".$order_row['phone']."
					<br>
					<br>
					Delivery Address
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
				<a href='javascript:editOrderPlaceOrderPopup' class='orderinfo'>
					Order Info
					<br>
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
			<br>
			<br>
			<a href='javascript:editOrderPlaceOrderPopup()' class='orderinfo'>
				Billing Address
				<br>
				".$order_row['bill_name']."
				<br>
				".$order_row['bill_address']."
				<br>
				".$order_row['bill_city'].", ".$order_row['bill_state']." ".$order_row['bill_zip']."
			</a>
			";

		$outputtext .= "
			<br>
			<br>
			</center>
			";
			
		$outputtext .= "
			<table id='fullorder'>
				<colgroup>
					<col width='25%'>
				</colgroup>
				<colgroup>
					<col width='25%'>
				</colgroup>
				<colgroup>
					<col width='25%'>
				</colgroup>
				<colgroup>
					<col width='25%'>
				</colgroup>
				<tr>
				
					<td class='optional' id='deliverytest'>
						Delivery?
					</td>
					<td class='center'>
					";
		
		$query_get_restaurant = "SELECT * FROM Restaurant WHERE (loc_id='$loc_id' AND rest_id='$rest_id') LIMIT 1";
		$result_get_restaurant = mysqli_query($dbc, $query_get_restaurant);
		$rest_row = mysqli_fetch_array($result_get_restaurant, MYSQLI_ASSOC);

		$delivery = array();
    
		if($rest_row['delivers']==1)
		{
			$delivery['delivery']='Delivery';
			if($rest_row['delivery_fee']==0)
			{
				$delivery['delivery'] .= ' (no fee)';
			}
			else
			{
				$delivery['delivery'] .= ' ($'.$rest_row['delivery_fee'].')';
			}
		}

		if($rest_row['pickup']==1)
		{
			$delivery['pickup']='Pick Up';
		}
		
		$outputtext .= "<select type='text' id='delivery' name='delivery' onchange='testDeliveryPlaceOrder(this)' tabindex=1 >";
		
		foreach($delivery as $key=>$value)
		{
			if($key == $order_row['delivery'])
			{
				$outputtext .= "<option selected='selected' value='".$key."'>".$value."</option>";
			}
			else
			{
				$outputtext .= "<option value='".$key."'>".$value."</option>";
			}
		}

		$outputtext .= "</select>";
			
		$outputtext .= "
					</td>
		
					<td class='test' id='nameoncardtest'>
						Name On Card
					</td>
					<td>
						<input type='text' id='nameoncard' onkeyup='testNameOnCardPlaceOrder(this)' placeholder='Name On Card' value='".$order_row['bill_name']."' tabindex=10 />
					</td>
				
				</tr>
				<tr>
				
					<td class='test' id='emailtest'>
						Email Address
					</td>
					<td class='center'>
						<input type='email' id='email' name='email' onkeyup='testEmailPlaceOrder(this)' placeholder='Email Address' value='".$order_row['email']."' tabindex=2 />
					</td>
				
					<td class='test' id='billaddresstest'>
						Billing Address
					</td>
					<td>
						<input type='text' id='billaddress' onkeyup='testBillAddressPlaceOrder(this)' placeholder='Billing Address' value='".$order_row['bill_address']."' tabindex=11 />
					</td>
					
				</tr>
				<tr>
					
					<td class='test' id='phonenumbertest'>
						Phone Number
					</td>
					<td class='center'>
						<input type='tel' id='phonenumber' name='phonenumber' onkeyup='testPhoneNumberPlaceOrder(this)' placeholder='xxx-xxx-xxxx' value='".$order_row['phone']."' tabindex=3 />
					</td>
		
					<td class='test' id='billcitytest'>
						Billing City
					</td>
					<td>
						<input type='text' id='billcity' onkeyup='testBillCityPlaceOrder(this)' placeholder='Billing City' value='".$order_row['bill_city']."' tabindex=12 />
					</td>
					
				</tr>
				<tr>
				
					<td class='test' id='firstnametest'>
						First Name
					</td>
					<td class='center'>
						<input type='text' id='firstname' name='firstname' onkeyup='testFirstNamePlaceOrder(this)' placeholder='First Name' value='".$order_row['first_name']."' tabindex=4 />
					</td>
					
					<td class='test' id='billstatetest'>
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

		$outputtext .= "<select type='text' id='billstate' name='billstate' onchange='testBillStatePlaceOrder(this)' tabindex=13 >";
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
				
					<td class='test' id='lastnametest'>
						Last Name
					</td>
					<td class='center'>
						<input type='text' id='lastname' name='lastname' onkeyup='testLastNamePlaceOrder(this)' placeholder='Last Name' value='".$order_row['last_name']."' tabindex=5 />
					</td>
		
					<td class='test' id='billzipcodetest'>
						Billing Zip Code
					</td>
					<td>
						<input type='text' id='billzipcode' onkeyup='testBillZipCodePlaceOrder(this)' placeholder='Billing Zip Code' value='".$order_row['bill_zip']."' tabindex=14 />
					</td>
				</tr>
				<tr>
					
					<td class='test' id='deliveryaddresstest'>
						Street Address
					</td>
					<td class='center'>
						<input type='text' id='deliveryaddress' name='deliveryaddress' onkeyup='testAddressPlaceOrder(this)' placeholder='Street Address' value='".$order_row['del_address']."' tabindex=6 />
					</td>
		
					<td class='test' id='cardtypetest'>
						Credit Card Type
					</td>
					<td colspan='1'>
						<select type='text' id='cardtype' name='cardtype' onchange='testCardTypePlaceOrder(this)' tabindex=15 >
						<option value=''>Select your card type...</option>
						";
	
		$query_get_card_types = "SELECT * FROM Card_Type WHERE rest_id='".$order_row['rest_id']."' ORDER BY card_type";
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
					
					<td class='test' id='deliverycitytest'>
						City
					</td>
					<td class='center'>
						<input type='text' id='deliverycity' name='deliverycity' onkeyup='testCityPlaceOrder(this)' placeholder='City' value='".$order_row['del_city']."' tabindex=7 />
					</td>
					
					<td class='test' id='cardnumbertest'>
						Credit Card Number
					</td>
					<td>
						<input type='text' id='cardnumber' onkeyup='testCardNumberPlaceOrder(this)' placeholder='0000 0000 0000 0000' tabindex=16 />
					</td>
				</tr>
				<tr>
					
					<td class='test' id='deliverystatetest'>
						State
					</td>
					<td class='center'>
			";
		
		$states = statesList();

		$outputtext .= "<select type='text' id='deliverystate' name='deliverystate' onchange='testStatePlaceOrder(this)' tabindex=8 >";
		
		if($order_row['del_state'])
		{
			$stateletters = $order_row['del_state'];
			$outputtext .= "<option selected='selected' value='".$stateletters."'>".$states[$stateletters]."</option>";
		}
		else
		{
			$outputtext .= "<option selected='selected'>Select your state...</option>";
		}

		foreach($states as $key=>$value)
		{
			$outputtext .= "<option value='".$key."'>".$value."</option>";
		}

		$outputtext .= "</select>";

		//<input type='text' id='newstate' name='newstate' onkeyup='testState()' placeholder='Virginia' />
			
		$outputtext .= "
					</td>
		
					<td class='test' id='confirmnumbertest'>
						Confirm Card Number
					</td>
					<td>
						<input type='text' id='confirmnumber' onkeyup='testConfirmCardNumberPlaceOrder(this)' placeholder='0000 0000 0000 0000' tabindex=17 />
					</td>
				</tr>
				<tr>
					
					<td class='test' id='deliveryzipcodetest'>
						Zip Code
					</td>
					<td class='center'>
						<input type='text' id='deliveryzipcode' name='deliveryzipcode' onkeyup='testZipCodePlaceOrder(this)' placeholder='Zip Code' value='".$order_row['del_zip']."' tabindex=9 />
					</td>
					
					<td class='test' id='expdatetest'>
						Exp. Date
					</td>
					<td>
						<input type='month' id='expdate' onkeyup='testExpDatePlaceOrder(this)' tabindex=18 />
					</td>
				</tr>
				<tr>
					<td colspan='4'>&nbsp</td>
				</tr>
				<tr>
			";
			
		if($order_row['delivery']=='delivery' && $order_row['subtotal'] < $order_row['delivery_minimum'])
		{
			$outputtext .= "
					<td colspan='4' class='test' id='stylebuttontest'>
						Order subtotal must be at least $".$order_row['delivery_minimum']." to place order.
					</td>
				</tr>
				<tr>
					<td colspan='4'>
						<input type='submit' id='stylebutton' name='Place Order' value='Place Order' disabled tabindex=19 />
					</td>
				";
		}
		else
		{
			$outputtext .= "
					<td colspan='4'>
						<input type='submit' id='stylebutton' name='Place Order' value='Place Order' tabindex=19 />
					</td>
				";
		}
		
		$outputtext .= "
				</tr>
				<tr>
					<td colspan='4'>
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