<?php

session_start();

/*Define constant to connect to database */
DEFINE('DATABASE_USER', 'INOMREADONLY');
DEFINE('DATABASE_PASSWORD', 'iN0md@tc0m');
DEFINE('DATABASE_HOST', 'INOMDB.db.11450909.hostedresource.com');
DEFINE('DATABASE_NAME', 'INOMDB');
/*Default time zone ,to be able to send mail */
date_default_timezone_set('US/Eastern');

/*You might not need this */
ini_set('SMTP', "mail.myt.mu"); // Overide The Default Php.ini settings for sending mail


//This is the address that will appear coming from ( Sender )
define('EMAIL', 'noreply@gottanom.com');

/*Define the root url where the script will be found such as http://website.com or http://website.com/Folder/ */
DEFINE('WEBSITE_URL', 'http://gottanom.com');


// Make the connection:
$dbc = @mysqli_connect(DATABASE_HOST, DATABASE_USER, DATABASE_PASSWORD, DATABASE_NAME);

if (!$dbc)
{
    trigger_error('Could not connect to MySQL: ' . mysqli_connect_error());
}

include 'sanitize.php';

?>