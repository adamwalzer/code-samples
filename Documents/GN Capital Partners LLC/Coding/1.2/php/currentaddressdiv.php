<?php

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
		
	$outputtext .= "
			<center>	
				
				<form onSubmit='submitForm(this,".'"setcurrentaddress.php"'.");return false;' method='post' class='login_form'>
	
					<table id='registrationtable'>
					
					<tr>
					<th colspan='4' class='optional'>Current Address</th>
					</tr>
					
					<tr>
					<th colspan='2' class='optional' id='current_on_beach_test'>On The Beach</th>
					<th colspan='2' class='center'><input type='checkbox' id='current_on_beach' name='current_on_beach' onchange='testOnBeach(this)' placeholder='On The Beach' size='30' /></th>
					</tr>
					
					<tr>
					<th colspan='2' class='test' id='current_address_test'>Street Address</th>
					<th colspan='2' class='center'><input type='text' id='current_address' name='current_address' onchange='testAddress(this)' onkeyup='testAddress(this)' placeholder='Street Address' value='".$_SESSION['del_address']."' size='30' /></th>
					</tr>
	
					<tr>
					<th colspan='2' class='test' id='current_city_test'>City</th>
					<th colspan='2' class='center'><input type='text' id='current_city' name='current_city' onchange='testCity(this)' onkeyup='testCity(this)' placeholder='City' value='".$_SESSION['del_city']."' size='30' /></th>
					</tr>
	
					<tr>
					<th colspan='2' class='test' id='current_state_test'>State</th>
					<th colspan='2' class='center'>
		";
	
	$states = statesList();
	$stateletters = $_SESSION['del_state'];
	
	if($stateletters)
	{
		$statename = $states[$stateletters];
	}
	else
	{
		$statename = "Select Your State...";
	}

	$outputtext .= "<select type='text' id='current_state' name='current_state' onchange='testState(this)' >";
	$outputtext .= "<option selected='selected' value='".$stateletters."'>".$statename."</option>";
					
	$outputtext .= "</option>";

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
					<th colspan='2' class='test' id='current_zip_test'>Zip Code</th>
					<th colspan='2' class='center'><input type='text' id='current_zip' name='current_zip' onchange='testZip(this)' onkeyup='testZip(this)' placeholder='Zip Code' value='".$_SESSION['del_zip']."' size='30' /></th>
					</tr>
					
					<tr>
					<th colspan='4' class='center'><input type='submit' id='stylebutton' value='Enter Address' title='Enter Address' disabled /></th>
					</tr>
					
					<tr>
					<th colspan='4' class='center'>&nbsp;</th>
					</tr>
	
					</table>
	
				</form>
			</center>
			<script>
				testFormCurrentAddress();
			</script>
		";
	
	$outputtext .= "
		<p>
		Hi and welcome to Gottanom.com! What is Gottanom.com you may ask...well that kind of hunger for answers is exactly why we have this savory bit of information for you!
		</p>
		<p>
		<b>Who we are:</b><br>
		We are a website that allows users to order directly from local restaurants.  No longer do you have to stash countless menus or make phone calls trying to a find the right place.
		</p>
		<p>
		<b>How it works:</b><br>
		You place your order through our website after finding your favorite restaurant with the perfect food to fit your mood. We then process your order and payment and the restaurant will deliver it right to you.
		</p>
		<p>
		<b>What we're about:</b><br>
		Our mission is to bridge the community of eaters with local restaurants and charities, making ordering easier and a more enjoyable experience.  Not only do we strive to have a convenient and useful product, but every order you place has a portion of the proceeds going directly to a charity within your community.
		</p>
		<p>
		Hope that covers whatever questions you may have. If you would like more information, please place inquiries to: info@gottanom.com.
		<br>
		<br>
		<br>
		Let's make the world a better place, one mouthful at a time!
		</p>
		<p>
		Keep calm, and nom nom nom!
		<br>
		-Your friendly neighborhood noms and nomettes
		</p>
		<br>
		<center>
		<table>
		<tr>
		<th class='center'>
		<input type='submit' id='stylebutton' onclick='registerPopup()' value='Sign Me Up!' title='Register' />
		</th>
		</tr>
		</table>
		</center>

		";
	
    /// var_dump($error);
    // mysqli_close($dbc);
    
    //echo $outputtext;
?>