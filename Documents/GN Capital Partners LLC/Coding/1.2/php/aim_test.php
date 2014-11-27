<?php
	require_once 'AuthorizeNet.php'; // Make sure this path is correct.
	$transaction = new AuthorizeNetAIM('9p5nJ37UvTZX', '4V4h8M6h87UsM353');
	$transaction->amount = '9.99';
	$transaction->card_num = '4007000000027';
	$transaction->exp_date = '10/16';

	$response = $transaction->authorizeAndCapture();

	if ($response->approved) {
	  echo "<h1>Success! The test credit card has been charged!</h1>";
	  echo "Transaction ID: " . $response->transaction_id;
	} else {
	  echo $response->error_message;
	}
?>