<?php

function statesList()
{
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


$loc_id = $order_row['loc_id'];
$rest_id = $order_row['rest_id'];

$query_get_restaurant = "
	SELECT * FROM Restaurant 
	WHERE (loc_id='$loc_id' AND rest_id='$rest_id') 
	LIMIT 1
	";
$result_get_restaurant = mysqli_query($dbc, $query_get_restaurant);
$rest_row = mysqli_fetch_array($result_get_restaurant, MYSQLI_ASSOC);//Assign the result of this query to SESSION Global Variable

if($rest_row['delivers'])
{
	if($_SESSION['current_city'])
	{
		$current_city = $_SESSION['current_city'];
		$current_state = $_SESSION['current_state'];
		$current_zip = $_SESSION['current_zip'];
		$query_get_delivers = "
			SELECT * FROM Delivery_Info 
			WHERE (rest_id='$rest_id' AND state='$current_state' AND (city='$current_city' OR zip='$current_zip')) 
			LIMIT 1
			";
   		$result_get_delivers = mysqli_query($dbc, $query_get_delivers);
		if (@mysqli_num_rows($result_get_delivers) == 1)
		{
			$delivers = 1;
			$delivers_row = mysqli_fetch_array($result_get_delivers, MYSQLI_ASSOC);
			$delivery_minimum = $delivers_row['delivery_minimum'];
			$delivery_fee = $delivers_row['delivery_fee'];
		}
		else
		{
			$delivers = 0;
			$delivery_minimum = $rest_row['delivery_minimum'];
			$delivery_fee = $rest_row['delivery_fee'];
		}
	}
	else
	{
		$delivers = 0;
		$delivery_minimum = $rest_row['delivery_minimum'];
		$delivery_fee = $rest_row['delivery_fee'];
	}
}
else
{
	$delivers = 0;
	$delivery_minimum = $rest_row['delivery_minimum'];
	$delivery_fee = $rest_row['delivery_fee'];
}

$delivery = array();
    
if($delivers==1)
{
	$delivery['delivery']='Delivery';
	if($rest_row['delivery_fee']==0)
	{
		$delivery['delivery'] .= ' (no fee)';
	}
	else
	{
		$delivery['delivery'] .= ' ($'.$delivery_fee.')';
	}
}

if($rest_row['pickup']==1)
{
	$delivery['pickup']='Pick Up';
}

if($loc_id && $rest_id)
{

	$outputtext .= "
	<center>
	<form id='neworderform' onSubmit='editOrder(this);return false;' method='post' class='updateinfo_form'>
		<br>
		
		<table id='registrationtable'>
		
		<tr>
		<th colspan='2' class='optional' id='deliverytest'>Delivery?</th>
		<th colspan='2' class='center'>
		";

	$outputtext .= "<select type='text' id='delivery' name='delivery' onchange='testDeliveryPlaceOrder(this)' >";
	
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
		</th>
		<th colspan='1' class='center'>&nbsp;</th>
		</tr>
		
		<tr>
		<th colspan='2' class='test' id='emailtest'>Email Address</th>
		<th colspan='2' class='center'><input type='email' id='email' name='email' onkeyup='testEmailNewOrder()' placeholder='Email Address' value='".$order_row['email']."' /></th>
		</tr>
		
		<tr>
		<th colspan='2' class='test' id='phonenumbertest'>Phone Number</th>
		<th colspan='2' class='center'><input type='tel' id='phonenumber' name='phonenumber' onkeyup='testPhoneNumberNewOrder()' placeholder='xxx-xxx-xxxx' value='".$order_row['phone']."' /></th>
		</tr>
		
		<tr>
		<th colspan='2' class='test' id='firstnametest'>First Name</th>
		<th colspan='2' class='center'><input type='text' id='firstname' name='firstname' onkeyup='testFirstNameNewOrder()' placeholder='First Name' value='".$order_row['first_name']."' /></th>
		</tr>
		
		<tr>
		<th colspan='2' class='test' id='lastnametest'>Last Name</th>
		<th colspan='2' class='center'><input type='text' id='lastname' name='lastname' onkeyup='testLastNameNewOrder()' placeholder='Last Name' value='".$order_row['last_name']."' /></th>
		</tr>
					
		<tr>
		<th colspan='2' class='optional' id='current_on_beach_test'>On The Beach</th>
		<th colspan='2' class='center'><input type='checkbox' id='current_on_beach' name='current_on_beach' onchange='testOnBeach(this)' placeholder='On The Beach' size='30' ".$order_row['del_on_beach']." /></th>
		</tr>
		
		<tr>
		<th colspan='2' class='test' id='deliveryaddresstest'>Street Address</th>
		<th colspan='2' class='center'><input type='text' id='deliveryaddress' name='deliveryaddress' onkeyup='testAddressNewOrder()' placeholder='Street Address' value='".$order_row['del_address']."' /></th>
		</tr>
		
		<tr>
		<th colspan='2' class='test' id='deliverycitytest'>City</th>
		<th colspan='2' class='center'><input type='text' id='deliverycity' name='deliverycity' onkeyup='testCityNewOrder()' placeholder='City' value='".$order_row['del_city']."' /></th>
		</tr>
		
		<tr>
		<th colspan='2' class='test' id='deliverystatetest'>State</th>
		<th colspan='2' class='center'>
		";
		
	$states = statesList();

	$outputtext .= "<select type='text' id='deliverystate' name='deliverystate' onchange='testStateNewOrder()' >";
	
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
		</th>
		</tr>
		
		<tr>
		<th colspan='2' class='test' id='deliveryzipcodetest'>Zip Code</th>
		<th colspan='2' class='center'><input type='text' id='deliveryzipcode' name='deliveryzipcode' onkeyup='testZipCodeNewOrder()' placeholder='Zip Code' value='".$order_row['del_zip']."' /></th>
		</tr>
		
		<tr>
		<th colspan='4' id='registerbuttontest' class='optional'>&nbsp;</th>
		</tr>
		
		<tr>
		<th colspan='1' class='center'>&nbsp;</th>
		<th colspan='2' class='center'><input type='submit' id='registerbutton' value='Save Changes' title='Save Changes' /></th>
		<th colspan='1' class='center'>&nbsp;</th>
		</tr>
		
		</table>
		
	</form>
	<center>
	<script>
		testForm('neworderform');
	</script>
	";
	
}
else
{
	$outputtext .= "No Restaurant ID";
}

/// var_dump($error);
mysqli_close($dbc);

echo $outputtext;

?>