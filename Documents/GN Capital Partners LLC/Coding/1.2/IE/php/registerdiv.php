<?php

$outputtext .= "
<script>
	window.onload=loadRegDiv;
</script>
<center>
<form action='javascript:register()' method='post' class='updateinfo_form' autocomplete='off'>
	<br>
	<br>
	
	<table id='registrationtable'>
	
	<tr>
	<th class='center'>Username</th>
	<th class='center'><input type='text' id='newusername' name='newusername' onkeyup='testUsername()' placeholder='Username' size='30' /></th>
	<th class='test' id='usernametest'>Required</th>
	</tr>
	
	<tr>
	<th class='center'>Email Address</th>
	<th class='center'><input type='text' id='newemail' name='newemail' onkeyup='testEmail()' placeholder='Email Address' size='30' /></th>
	<th class='test' id='emailtest'>Required</th>
	</tr>
	
	<tr>
	<th class='center'>Confirm Email</th>
	<th class='center'><input type='text' id='confirmemail' name='confirmemail' onkeyup='testConfirmEmail()' placeholder='Confirm Email' size='30' /></th>
	<th class='test' id='confirmemailtest'>Required</th>
	</tr>
	
	<tr>
	<th class='center'>Phone Number</th>
	<th class='center'><input type='text' id='newphonenumber' name='newphonenumber' onkeyup='testPhoneNumber()' placeholder='xxx-xxx-xxxx' size='30' /></th>
	<th class='test' id='phonenumbertest'>Required</th>
	</tr>
	
	<tr>
	<th class='center'>First Name</th>
	<th class='center'><input type='text' id='newfirstname' name='newfirstname' onkeyup='testFirstName()' placeholder='First Name' size='30' /></th>
	<th class='optional' id='firstnametest'>Optional</th>
	</tr>
	
	<tr>
	<th class='center'>Last Name</th>
	<th class='center'><input type='text' id='newlastname' name='newlastname' onkeyup='testLastName()' placeholder='Last Name' size='30' /></th>
	<th class='optional' id='lastnametest'>Optional</th>
	</tr>
	
	<tr>
	<th class='center'>Password</th>
	<th class='center'><input type='password' id='newpassword' name='newpassword' onkeyup='testPassword()' placeholder='Password' size='30' /></th>
	<th class='test' id='passwordtest'>Required</th>
	</tr>
	
	<tr>
	<th class='center'>Confirm Password</th>
	<th class='center'><input type='password' id='confirmpassword' name='confirmpassword' onkeyup='testConfirmPassword()' placeholder='Confirm Password' size='30' /></th>
	<th class='test' id='confirmpasswordtest'>Required</th>
	</tr>
	
	<tr>
	<th colspan='3' class='center'>&nbsp;</th>
	</tr>
	
	<tr>
	<th colspan='2' class='center'><input type='checkbox' onClick='checkToEnable()' id='termsofuse' name='termsofuse' value='termsofuse'>I agree with the <a href='javascript:window.open(".'"http://gottanom.com/termsofuse.php"'.")'><b>terms of use</b></a>.</th>
	<th class='center'><input type='submit' id='registerbutton' value='Register' title='Register' /></th>
	</tr>
	
	</table>
	
	<br>
	
</form>
<center>";


/*
	del_address	varchar(60)	utf8_general_ci		No			 Browse distinct values	 Change	 Drop	 Primary	 Unique	 Index	 Fulltext
	del_city	varchar(30)	utf8_general_ci		No			 Browse distinct values	 Change	 Drop	 Primary	 Unique	 Index	 Fulltext
	del_zip	mediumint(5)			No			 Browse distinct values	 Change	 Drop	 Primary	 Unique	 Index	Fulltext
	bill_address	varchar(60)	utf8_general_ci		No			 Browse distinct values	 Change	 Drop	 Primary	 Unique	 Index	 Fulltext
	bill_city	varchar(30)	utf8_general_ci		No			 Browse distinct values	 Change	 Drop	 Primary	 Unique	 Index	 Fulltext
	bill_zip	mediumint(5)			No			 Browse distinct values	 Change	 Drop	 Primary	 Unique	 Index	Fulltext
	phone	int(10)			No			 Browse distinct values	 Change	 Drop	 Primary	 Unique	 Index	Fulltext
	greek
	
*/

?>