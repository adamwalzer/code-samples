<?php
	//include "connect.php";
	
	$loc_id = $_SESSION['loc_id'];
	$rest_id = $_SESSION['rest_id'];
	
	if($loc_id && $rest_id)
	{
        $outputtext .= "
			<div id='top'>
				<table id='topbartable'>
					<tr>
						<td valign='middle' align='center' style='font-size:35px;'>
							".$_SESSION['name']." Information
						</td>
					</tr>
				</table>
			</div>
			";
    }
	else
	{
		$outputtext .= "
			<div id='top'>
				<table id='topbartable'>
					<tr>
						<td valign='middle' align='center' style='font-size:35px;'>
							No Restaurant ID
						</td>
					</tr>
				</table>
			</div>
			";
    }
	
    /// var_dump($error);
    //mysqli_close($dbc);
?>