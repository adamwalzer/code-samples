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

$loc_id = $_GET['loc_id'];
$rest_id = $_GET['rest_id'];
$item_id = $_GET['item_id'];

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
	if($delivery_fee==0)
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

//$outputtext .= $delivery_minimum.','.$delivery_fee.','.$delivery['delivery'].','.$delivery['pickup'];

if($loc_id && $rest_id && $item_id)
{

	$outputtext .= "
	<center>
	<form id='start_new_order_form' action='javascript:startNewOrder(".'"'.$loc_id.'"'.",".'"'.$rest_id.'"'.",".'"'.$item_id.'"'.")' method='post' class='updateinfo_form'>
		<br>
		
		<table id='registrationtable'>
		
		<tr>
		<th colspan='2' class='optional' id='delivery_test'>Delivery?</th>
		<th colspan='2' class='center'>
		";

	$outputtext .= "<select type='text' id='delivery' name='delivery' onchange='' >";

	foreach($delivery as $key=>$value)
	{
		$outputtext .= "<option value='".$key."'>".$value."</option>";
	}

	$outputtext .= "</select>";
		
	$outputtext .= "
		</th>
		</tr>
		
		<tr>
		<th colspan='2' class='test' id='email_test'>Email Address</th>
		<th colspan='2' class='center'><input type='email' id='email' name='email' onkeyup='testEmail(this)' placeholder='Email Address' value='".$_SESSION['email']."' size='30' /></th>
		</tr>
		
		<tr>
		<th colspan='2' class='test' id='phone_number_test'>Phone Number</th>
		<th colspan='2' class='center'><input type='tel' id='phone_number' name='phone_number' onkeyup='testPhoneNumber(this)' placeholder='xxx-xxx-xxxx' value='".$_SESSION['phone']."' size='30' /></th>
		</tr>
		
		<tr>
		<th colspan='2' class='test' id='first_name_test'>First Name</th>
		<th colspan='2' class='center'><input type='text' id='first_name' name='first_name' onkeyup='testFirstName(this)' placeholder='First Name' value='".$_SESSION['first_name']."' size='30' /></th>
		</tr>
		
		<tr>
		<th colspan='2' class='test' id='last_name_test'>Last Name</th>
		<th colspan='2' class='center'><input type='text' id='last_name' name='last_name' onkeyup='testLastName(this)' placeholder='Last Name' value='".$_SESSION['last_name']."' size='30' /></th>
		</tr>
					
		<tr>
		<th colspan='2' class='optional' id='current_on_beach_test'>On The Beach</th>
		<th colspan='2' class='center'><input type='checkbox' id='current_on_beach' name='current_on_beach' onchange='testOnBeach(this)' placeholder='On The Beach' size='30' ".$_SESSION['current_on_beach']." /></th>
		</tr>
		
		<tr>
		<th colspan='2' class='test' id='address_test'>Street Address</th>
		<th colspan='2' class='center'><input type='text' id='address' name='address' onkeyup='testAddress(this)' placeholder='Street Address' value='".$_SESSION['current_address']."' size='30' /></th>
		</tr>
		
		<tr>
		<th colspan='2' class='test' id='city_test'>City</th>
		<th colspan='2' class='center'><input type='text' id='city' name='city' onkeyup='testCity(this)' placeholder='City' value='".$_SESSION['current_city']."' size='30' /></th>
		</tr>
		
		<tr>
		<th colspan='2' class='test' id='state_test'>State</th>
		<th colspan='2' class='center'>
		";
		
	$states = statesList();

	$outputtext .= "<select type='text' id='state' name='state' onchange='testState(this)' >";
	
	if($_SESSION['current_state'])
	{
		$stateletters = $_SESSION['current_state'];
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

	//<input type='text' id='newstate' name='newstate' onkeyup='testState()' placeholder='Virginia' size='30' />
		
	$outputtext .= "
		</th>
		</tr>
		
		<tr>
		<th colspan='2' class='test' id='zip_test'>Zip Code</th>
		<th colspan='2' class='center'><input type='text' id='zip' name='zip' onkeyup='testZip(this)' placeholder='Zip Code' value='".$_SESSION['current_zip']."' size='30' /></th>
		</tr>
		
		<tr>
		<th colspan='4' class='center'>&nbsp;</th>
		</tr>
		
		<tr>
		<th colspan='1' class='center'>&nbsp;</th>
		<th colspan='2' id='registerbutton_test' class='center'><input type='submit' id='registerbutton' value='Start Order' title='Start Order' /></th>
		<th colspan='1' class='center'>&nbsp;</th>
		</tr>
		
		</table>
		
	</form>
	<center>
	<script>
		//testNewOrder();
		checkForm('#start_new_order_form');
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