<?php
	
	if(!$_SESSION)
	{
		session_start();
	}
	
	$loc_id = $_SESSION['loc_id'];
	$rest_id = $_SESSION['rest_id'];
	
	if($loc_id && $rest_id)
	{
        $outputtext .= "
			<table id='topbartable'>
				<tr>
					<td valign='middle' align='center' style='font-size:35px;'>
						Edit ".$_SESSION['name']." Menu Items
					</td>
				</tr>
			</table>
			";
	}
	else
	{
		$outputtext .= "
			<center>
				No Restaurant ID
			</center>
			";
    }
?>