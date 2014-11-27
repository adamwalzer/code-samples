<?php

session_start();

include_once "connect.php";

$myusername = mysql_real_escape_string($_POST['username']);
$mypassword = mysql_real_escape_string(md5($_POST['password']));

$result = mysql_query("SELECT * FROM members WHERE username='$myusername' AND password='$mypassword'");
$count = mysql_num_rows($result);

if($count == 0) {
	echo "<form action='javascript:login()' method='post' class='login_form'>
	<div id='fieldset'>
	&nbsp;
      <input type='text' id='email' name='email' placeholder='Email' size='20' />
      <input type='password' id='password' name='password' placeholder='Password' size='20' />
     <input type='hidden' name='formsubmitted' value='TRUE' />
      <input type='submit' value='Login' />
	&nbsp;
    <a href='javascript:setRight('register.html')'>Register</a>
	&nbsp;
	<a href='javascript:setRight('register.html')'>Forgot Password?</a>
	&nbsp;
	</div>
	</form>";
}
else
{
	$query_check_credentials = "
		SELECT * FROM members 
		WHERE (Email='$e' AND password='$p') AND Activation IS NULL
		";
	$result_check_credentials = mysqli_query($dbc, $query_check_credentials);
	$_SESSION = mysqli_fetch_array($result_check_credentials, MYSQLI_ASSOC);
	
	echo "<form action='javascript:login()' method='post' class='login_form'>
	<span id='fieldset'>
	&nbsp;";
	echo "Welcome, ";
	echo $_SESSION['Username'];
	echo "&nbsp;
		</span>
	</form>";
}

?>