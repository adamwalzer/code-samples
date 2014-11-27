<?php
	
$outputtext .= "
			<div id='topbar'>
				<br>
				<a href='http://gottanom.com/gmu'><img src='../img/logo.svg' width='100%'></a>
			</div>
			<div id='toplogin'>
			<div id='tabs'>
			  <ul>
				<li><a href='#tabs-1'>Customer</a></li>
				<li><a href='#tabs-2'>Restaurant</a></li>
			  </ul>
			  <div id='tabs-1'>
				<center>
				<form action='javascript:customerlogin()' method='post' class='login_form'>
					<div id='topform'>
					Username <input type='text' id='email' name='email' placeholder='Username' size='20' />
					<br>
					Password <input type='password' id='password' name='password' placeholder='Password' size='20' />
					<br>
					<div align='right'><input type='submit' value='Login' /></div>
					<a href='javascript:setMain(".'"forgotpasswordpage.php"'.")'>Forgot Password?</a>
					&nbsp; &nbsp;
					<a href='javascript:setMain(".'"registerpage.php"'.")'>Register</a>
					</div>
				</form>
				</center>
			  </div>
			  <div id='tabs-2'>
				<center>
				<form action='javascript:restaurantlogin()' method='post' class='login_form'>
					<div id='topform'>
					Username <input type='text' id='email' name='email' placeholder='Username' size='20' />
					<br>
					Password <input type='password' id='password' name='password' placeholder='Password' size='20' />
					<br>
					<div align='right'><input type='submit' value='Login' /></div>
					<a href='javascript:setMain(".'"forgotpasswordpage.php"'.")'>Forgot Password?</a>
					&nbsp; &nbsp;
					<a href='javascript:setMain(".'"registerpage.php"'.")'>Register</a>
					</div>
				</form>
				</center>
			  </div>
			</div>
			</div>\n";

?>