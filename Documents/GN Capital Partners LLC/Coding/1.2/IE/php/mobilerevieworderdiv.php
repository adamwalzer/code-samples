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
			<form id='placeorderform' onSubmit='placeOrderPopup(this);return false;' method='post'>
			<table id='fullorder'>
				<colgroup>
					<col width='50%'>
				</colgroup>
				<colgroup>
					<col width='50%'>
				</colgroup>
			";
		
		$outputtext .= "
				<tr>
					<td class='money'>
						subtotal:
					</td>
					<td>
						$".$order_row['subtotal']."
					</td>
				</tr>
				";
					
		if($order_row['delivery']=='delivery' && $order_row['subtotal'] < $order_row['delivery_minimum'])
		{
			$tominimum = $order_row['delivery_minimum'] - $order_row['subtotal'];
			
			$outputtext .= " 
				<tr>
					<td colspan='2' class='center'>
						$".$tominimum." to delivery minimum
					</td>
				</tr>
				";
		}
		
		$outputtext .= "
				<tr>
					<td class='money'>
						tax:
					</td>
					<td>
						$".$order_row['tax']."
					</td>
				</tr>
				";
				
		if($order_row['delivery'] == 'delivery')
		{
			$outputtext .= "
				<tr>
					<td class='money'>
						delivery fee:
					</td>
					<td>
						$".$order_row['delivery_fee']."
					</td>
				</tr>
				";
		}
				
		$outputtext .= "
				<tr>
					<td class='money'>
						tip:
					</td>
					<td>
						$".$order_row['tip']."
					</td>
				</tr>
				<tr>
					<td class='money'>
						total:
					</td>
					<td>
						$".$order_row['total']."
					</td>
				</tr>
			</table>
			<center>
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
			<br>
			<br>
			<br>
			</center>
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
					<td class='test' id='nameoncardtest'>
						Name On Card
					</td>
					<td>
						<input type='text' id='nameoncard' onkeyup='testNameOnCardPlaceOrder(this)' placeholder='Name On Card' value='".$order_row['bill_name']."' tabindex=10 />
					</td>
				</tr>
				<tr>
					<td class='test' id='billaddresstest'>
						Billing Address
					</td>
					<td>
						<input type='text' id='billaddress' onkeyup='testBillAddressPlaceOrder(this)' placeholder='Billing Address' value='".$order_row['bill_address']."' tabindex=11 />
					</td>
				</tr>
				<tr>
					<td class='test' id='billcitytest'>
						Billing City
					</td>
					<td>
						<input type='text' id='billcity' onkeyup='testBillCityPlaceOrder(this)' placeholder='Billing City' value='".$order_row['bill_city']."' tabindex=12 />
					</td>
				</tr>
				<tr>
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
					<td class='test' id='billzipcodetest'>
						Billing Zip Code
					</td>
					<td>
						<input type='text' id='billzipcode' onkeyup='testBillZipCodePlaceOrder(this)' placeholder='Billing Zip Code' value='".$order_row['bill_zip']."' tabindex=14 />
					</td>
				</tr>
				<tr>
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
					<td class='test' id='cardnumbertest'>
						Credit Card Number
					</td>
					<td>
						<input type='text' id='cardnumber' onkeyup='testCardNumberPlaceOrder(this)' placeholder='0000 0000 0000 0000' tabindex=16 />
					</td>
				</tr>
				<tr>
					<td class='test' id='confirmnumbertest'>
						Confirm Card Number
					</td>
					<td>
						<input type='text' id='confirmnumber' onkeyup='testConfirmCardNumberPlaceOrder(this)' placeholder='0000 0000 0000 0000' tabindex=17 />
					</td>
				</tr>
				<tr>
					<td class='test' id='expdatetest'>
						Exp. Date
					</td>
					<td>
						<input type='month' id='expdate' onkeyup='testExpDatePlaceOrder(this)' onchange='testExpDatePlaceOrder(this)' tabindex=18 />
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
					<td colspan='2'>
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
					<td colspan='2'>
						<input type='submit' id='stylebutton' name='Place Order' value='Place Order' disabled tabindex=19 />
					</td>
				";
		}
		else
		{
			$outputtext .= "
					<td colspan='2'>
						<input type='submit' id='stylebutton' name='Place Order' value='Place Order' tabindex=19 />
					</td>
				";
		}
		
		$outputtext .= "
				</tr>
				<tr>
					<td colspan='2'>
						&nbsp;
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