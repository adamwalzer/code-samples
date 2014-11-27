<?php

$loc_id=$_SESSION['loc_id'];
$rest_id=$_SESSION['rest_id'];
$outputtext .= "
	<div id='main_left' class='col span_1_of_4 span_1_to_2_of_4'>
		<div id='main_left_content' class='content'>
			&nbsp; &bull; <a href='http://gottanom.com/viewrestaurantorders.php'>View Your Orders</a><br>
			&nbsp; &bull; <a href='http://gottanom.com/viewrestaurantpage.php'>View Your Page</a><br>
			&nbsp; &bull; <a href='http://gottanom.com/editrestaurantinfo.php'>Edit Your Info</a><br>
			&nbsp; &bull; <a href='http://gottanom.com/editmenu.php'>Edit Your Menu</a><br>
			&nbsp; &bull; <a href='http://gottanom.com/edititems.php'>Edit Your Items</a><br>
		</div>
	</div>
	";

?>