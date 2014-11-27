<?php

// =====================
// account information
// =====================
$username = "60774";
$pin = "2031";
 
// =====================
// 1 = Announcement
// 2 = Survey
// 3 = SMS 
// =====================
$broadcast_type = "1";
 
// =================================
// 1 = listID
// 2 = CSV Binary attachment
// 3 = CommaDelimited Phonenumbers
// =================================
$phone_number_source = "3";
 
// =====================
// Name of the broadcast
// =====================
$broadcast_name = "Order API Broadcast with TTS";
 
// ========================================
// leave blank to use default from account
// ========================================
$caller_id = "8024492027";
 
// ==============================================================================================
// Phonenumbers to call or text (for text/SMS people have to Opt-In to receive)
//
// Extended usage: phonenumber|firstname|lastname|notes|secondaryPhone|tertiaryPhone>
//
// ex "9725551212|Grandma|Moses|Some notes here|9725552300,9725552300|test|number,9725554536" 
// ==============================================================================================
//$PhoneNumbers = "9725551313, 9725551212, 9305551212";
 
// ===========================================
// Use Text to Speech to generate the message
// ===========================================
/*
$TTSText = "<prosody volume='x-loud'>";
for ($i=0; $i<=5; $i++)
{
	$TTSText .= "You have an incoming order from Gotta Nom... Please check your fax... ";
} 
$TTSText .= "</prosody>";
*/
 
//$proxy = "https://staging-api.call-em-all.com/webservices/ceaapi_v3-2-13.asmx?wsdl";
$proxy = "https://api.call-em-all.com/webservices/ceaapi_v3-2-13.asmx?wsdl";

//$messageID = "ae2694";
$messageID = "dp0148";

$client = new SoapClient($proxy, array("trace" => true));
 
$request = array (
                  "username" => $username,
                  "pin" => $pin,
                  "broadcastType" => $broadcast_type,
                  "phoneNumberSource" => $phone_number_source,
                  "broadcastName" => $broadcast_name,
                  "phoneNumberCSV" => "",
                  "launchDateTime" => "",
                  "checkCallingWindow" => "0",
                  "callerID" => $caller_id,
                  "commaDelimitedPhoneNumbers" => $PhoneNumbers,
                  "messageID" => $messageID,
                 );
                                                    
$response = $client->ExtCreateBroadcast(array("myRequest" => $request));
 
// =====================
// var_dump($response);
// =====================
/*
print "errorCode                :" . $response->ExtCreateBroadcastResult->errorCode . "\n";
print "errorMessage             :" . $response->ExtCreateBroadcastResult->errorMessage . "\n";
print "broadcastID              :" . $response->ExtCreateBroadcastResult->broadcastID . "\n";
print "messageRecordingID       :" . $response->ExtCreateBroadcastResult->messageRecordingID . "\n";
print "tollFreeNumber           :" . $response->ExtCreateBroadcastResult->tollFreeNumber . "\n";
print "goodRecordCountOnFile    :" . $response->ExtCreateBroadcastResult->goodRecordCountOnFile . "\n";
print "badRecordCountonFile     :" . $response->ExtCreateBroadcastResult->badRecordCountOnFile . "\n";
print "duplicateRecordCount     :" . $response->ExtCreateBroadcastResult->duplicateRecrodCountOnFile . "\n";
print "totalRecordCount         :" . $response->ExtCreateBroadcastResult->totalRecordCountOnFile . "\n";
*/

if (!IsNullOrEmptyString($response->ExtCreateBroadcastResult->fileUploadErrors->ExtFileUploadError))
{
	$isError = 1;
        /*
        foreach ($response->ExtCreateBroadcastResult->fileUploadErrors->ExtFileUploadError as $detail) {
 
                print "===================================== \n";
                print "Line Number      :" . $detail->lineNumber . "\n";
                print "errorCode        :" . $detail->errorCode . "\n";
                print "errorMessage     :" . $detail->errorMessage . "\n";
        }
        */
 
}

$x=1;
while($isError)
{
	$response = $client->ExtCreateBroadcast(array("myRequest" => $request));
	if (!IsNullOrEmptyString($response->ExtCreateBroadcastResult->fileUploadErrors->ExtFileUploadError))
	{
		$isError = 1;
		$x++;
	}
	
	if($x==3)
	{
		$isError = 0;
		$mail_for_error = mail('adamwalzer@gmail.com', 'Order Placed - Call Not', "Order ID: ".$order_id."\n Restaurant: ".$rest_row['name']."\n Call-in-order: ".$PhoneNumbers, $headers_for_customer);
	}
}
 
function IsNullOrEmptyString($question) {
    return (!isset($question) || trim($question)==='');
}

?>