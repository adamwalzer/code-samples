<?php
	
$outputtext .= "

<div id='top_nav' class='top_nav content'>
	<div id='top_nav_content' class='top_nav_content'>
		<div id='top_nav_table_row' class='top_nav_table_row'>
			<div id='top_logo' class='logo_div'>
				<a onclick='changeURLVariable(".'"loc_id=false&rest_id=false"'.",{".'"reload"'.":true});'><img src='./img/burger.png' class='logo' id='logo''></a>
			</div>
			<div id='slogan_outer_div' class='slogan_outer_div'>
				<div id='slogan_inner_div' class='slogan_inner_div'>
					1% OF EVERY ORDER GOES TO CHARITY!
				</div>
			</div>
			<div id='nav_outer_div' class='nav_outer_div'>
				<div class='nav_inner_div'>
				";
			
if($_SESSION['username'])
{
	$outputtext .= "
				Welcome, ".$_SESSION['first_name']."<br/>
				<a onclick='executePage(".'"logout"'.")'>Logout</a> &bull;
				<a onclick='executePage(".'"accountinfopage"'.")'>Settings</a>
				";
}
else
{
	$outputtext .= "
				<a onclick='executePage(".'"logindiv"'.")'>Login</a> &bull;
				<a onclick='executePage(".'"registerdiv"'.")'>Register</a>
				";
}
				
$outputtext .= "
				</div>
			</div>
		</div>
	</div>
</div>

";

?>