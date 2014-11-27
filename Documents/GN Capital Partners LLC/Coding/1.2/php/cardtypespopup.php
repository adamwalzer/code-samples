<?php

session_start();

include "connect.php";

$rest_id = $_SESSION['rest_id'];
$query_get_info = "
	SELECT * FROM Card_Type 
	WHERE rest_id = '$rest_id'
	";
$result_get_info = mysqli_query($dbc, $query_get_info);

$outputtext .= "
	<center>
		<form onSubmit='submitInput(this,".'"setcardtypes.php"'.");return false;' method='post' class='login_form'>

			<table id='registrationtable'>
	";

if (@mysqli_num_rows($result_get_info) > 0)//if Query is successfull 
{ // A match was made.
	while($info_row = mysqli_fetch_array($result_get_info, MYSQLI_ASSOC))
	{
	
		$outputtext .= "
					<tr class='delivery-location'>
						<td>
							<input type='hidden' value='::C' />
							<i onClick='deleteCardType(this)' class='icon-cancel'></i>
							<i onClick='addCardType(this)' class='icon-add'></i>
						</td>
						<td>
							<table id='registrationtable'>
								<tr>
									<th colspan='2' class='optional' id='card_type_test'>City</th>
									<th colspan='2' class='center'><input type='text' id='card_type' name='card_type' placeholder='Card Type' value='".$info_row['card_type']."' size='30' /></th>
								</tr>
							</table>
						</td>
					</tr>
			";
	}
}
else
{
	$outputtext .= "
				<tr class='delivery-location'>
					<td>
						<input type='hidden' value='::C' />
						<i onClick='deleteCardType(this)' class='icon-cancel'></i>
						<i onClick='addCardType(this)' class='icon-add'></i>
					</td>
					<td>
						<table id='registrationtable'>
							<tr>
								<th colspan='2' class='optional' id='card_type_test'>City</th>
								<th colspan='2' class='center'><input type='text' id='card_type' name='card_type' placeholder='Card Type' size='30' /></th>
							</tr>
						</table>
					</td>
				</tr>
		";
}
$outputtext .= "
				<tr>
					<th colspan='4' class='optional' ><input type='submit' id='stylebutton' name='Save Card Types' value='Save Card Types' /></th>
				</tr>

			</table>

		</form>
	</center>
	";
	
mysqli_close($dbc);
    
echo $outputtext;

?>