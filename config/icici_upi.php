<?php
return [
	// Production
    'api_url' => 'https://api.icicibank.com:8443/api/MerchantAPI/UPI/v1', // no trailing slash
    'api_endpoint_collect_pay' => 'CollectPay',
    'api_endpoint_transaction_status' => 'TransactionStatus',
    'merchant_id' => '125295',
    'merchant_name' => 'Foodklub',
    'terminal_id' => '5411', // hardcoded as of now
    'icici_public_key' => __DIR__ . '/upi-keys/icici-public-key.pem',
    'foodklub_private_key' => __DIR__ . '/upi-keys/api.foodklub.in.key',
	
	// Staging
	/*
	'api_url' => 'https://apigwuat.icicibank.com:8443', // no trailing slash
    'api_endpoint_collect_pay' => 'newCollectPay',
    'api_endpoint_transaction_status' => 'newTransactionStatus',
    'merchant_id' => '107678', //'107714';
    'merchant_name' => 'Foodklub',
    'terminal_id' => '5411', // hardcoded as of now
    'icici_public_key' => __DIR__ . '/upi-keys/icici-public-key.pem',
    'foodklub_private_key' => __DIR__ . '/upi-keys/api.foodklub.in.key',
	*/
	
];
