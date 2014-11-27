<?php
	
$outputtext .= "
<div id='topcontent'>

	<div id='toplogo'>
		<a href='javascript:location.reload()'><img src='../img/burger.png' id='burger' width='100%'></a>
	</div>
	
	<div id='topbar'>
	";
if($_SESSION['order_id'])
{
	include "mobileviewrestauranttop.php";
}
elseif($_SESSION['current_address'])
{
	$outputtext .= "
			<table id='topbartable'>
				<tr>
					<td valign='middle' align='center'>
						<h3>$location Categories</h3>
					</td>
				</tr>
			</table>
		";
}
else
{
	$outputtext .= "
			<table id='topbartable'>
				<tr>
					<td valign='middle' align='center'>
						<h3>Welcome</h3>
					</td>
				</tr>
			</table>
		";
}
	
$outputtext .= "
	</div>	
</div>
	";

?>