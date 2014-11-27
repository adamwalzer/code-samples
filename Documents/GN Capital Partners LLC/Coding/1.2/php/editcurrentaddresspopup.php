<?php

include "stateslist.php";

set_time_limit(0);
ignore_user_abort(1);

session_start();

$outputtext .= "
<div id='main_left_content'>
<center>
<form id='currentaddressform' onSubmit='submitForm(this,".'"updatecurrentaddress"'.");return false;' method='post' class='updateinfo_form'>
	<br>
	
	<table id='registrationtable'>
					
	<tr>
	<th colspan='2' class='optional' id='current_on_beach_test'><input type='checkbox' id='current_on_beach' name='current_on_beach' onchange='testOnBeach(this)' placeholder='On The Beach' size='30' ".$_SESSION['current_on_beach']." /><label for='current_on_beach'>On The Beach</label></th>
	</tr>
	
	<tr>
	<th colspan='2' class='optional' id='current_address_test'><input type='text' id='current_address' name='current_address' onkeyup='testAddress(this);' placeholder='Street Address' value='".$_SESSION['current_address']."' /><div></div></th>
	</tr>
	
	<tr>
	<th colspan='2' class='optional' id='current_city_test'><input type='text' id='current_city' name='current_city' onkeyup='testCity(this)' placeholder='City' value='".$_SESSION['current_city']."' /><div></div></th>
	</tr>
	
	<tr>
	<th colspan='2' class='optional' id='current_state_test'>
	";
	
$states = statesList();

$outputtext .= "<select type='text' id='current_state' name='current_state' onchange='testState(this)' >";

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
	
$outputtext .= "
	<div></div></th>
	</tr>
	
	<tr>
	<th colspan='2' class='optional' id='current_zip_test'><input type='text' id='current_zip' name='current_zip' onkeyup='testZipCode(this)' placeholder='Zip Code' value='".$_SESSION['current_zip']."' /><div></div></th>
	</tr>
	
	<tr>
	<th colspan='2' id='registerbuttontest' class='optional'>&nbsp;</th>
	</tr>
	
	<tr>
	<th colspan='1' class='center'><input type='button' class='registerbutton' onclick='executePage(".'"leftcategories"'.")' value='Cancel' title='Cancel' /></th>
	<th colspan='1' class='center'><input type='submit' id='registerbutton' class='registerbutton' value='Save Changes' title='Save Changes' /></th>
	</tr>
	
	</table>
	
</form>
</center>
<script>
	testForm('#currentaddressform');
	checkForm('#currentaddressform');
	positionLeft();
</script>
</div>
";

?>