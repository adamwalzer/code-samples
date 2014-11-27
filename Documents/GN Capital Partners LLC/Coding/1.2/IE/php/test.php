<?php

$filename = 'filename.html';

$separator = '-----'.md5(time()).'-----';

//$headers_for_restaurant = "MIME-Version: 1.0\r\n";
//	$headers_for_restaurant .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
	$headers_for_restaurant .= "From: restaurant@gottanom.com\r\n";
	$headers_for_restaurant .= "Content-Type: multipart/mixed; boundary=\"$separator\"";

$message_for_restaurant = "this is a short message.";
	
	$attachment = chunk_split(base64_encode($message_for_restaurant));
	
	$body_for_restaurant = "--$separator\r\n"
        . "Content-Type: text/html; charset=ISO-8859-1; format=flowed\r\n"
        . "Content-Transfer-Encoding: 7bit\r\n"
        . "\r\n"
        . "$message_for_restaurant\r\n"
        . "--$separator\r\n"
        . "Content-Type: text/html\r\n"
        . "Content-Transfer-Encoding: base64\r\n"
        . "Content-Disposition: attachment; filename=\"$filename\"\r\n"
        . "\r\n"
        . "$attachment\r\n"
        . "--$separator--";
			
	$mail_for_restaurant = mail('adamwalzer@gmail.com', 'Your Gottanom Order', $body_for_restaurant, $headers_for_restaurant);

/*
$message_for_restaurant = "this is a test message";

require("class.phpmailer.php");
$fax = new PHPMailer();
$fax->IsSMTP();
$fax->Host     = 'smtpout.secureserver.net';
$fax->IsHTML(true);
$fax->From      = 'restaurant@gottanom.com';
$fax->FromName  = 'Gottanom';
$fax->Subject   = 'Your Gottanom Order';
$fax->Body      = $message_for_restaurant;
$fax->AddAddress('16092576122@nextivafax.com');

//$fax->AddStringAttachment($message_for_restaurant,$filename,'base64','text/html')

//$mail_for_restaurant = $fax->Send();

if(!($fax->Send()))
{
	echo $fax->ErrorInfo;
}
*/

?>