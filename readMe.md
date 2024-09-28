# Paysprint API Integration for Laravel

This package integrates Paysprint API for various services like recharge, bill payments, DTH, LIC, Fastag, and more into a Laravel application.

## Installation

To install this package, follow these steps:

### Step 1: Install via Composer

Run the following command in your Laravel project directory to install the package:

```bash
composer require piyush-jaiswal/paysprint
```
### Step 2: Publish the Configuration File

Once the package is installed, publish the configuration file to set up your API keys and URLs:

```bash
php artisan vendor:publish --tag=paysprint-config
```
This will create a file called config/paysprint.php where you can customize your Paysprint API settings.

Configuration
config/paysprint.php
This is the configuration file where you set up your Paysprint API environment. After publishing the configuration file, you will find it in config/paysprint.php. Below is an explanation of the configuration options:

```bash
return [

    /*
    |--------------------------------------------------------------------------
    | API Mode
    |--------------------------------------------------------------------------
    |
    | This sets the mode for the API. Options: 'live', 'uat'
    |
    */
    'mode' => env('API_MODE', 'uat'),  // Default to UAT, can be changed to 'live' in .env file

    /*
    |--------------------------------------------------------------------------
    | API URLs
    |--------------------------------------------------------------------------
    |
    | URLs for the live and UAT environments.
    |
    */
    'urls' => [
        'live' => env('API_LIVE_URL', 'https://api.paysprint.in'),
        'uat' => env('API_UAT_URL', 'https://sit.paysprint.in/service-api'),
    ],

    /*
    |--------------------------------------------------------------------------
    | API Authorized Key
    |--------------------------------------------------------------------------
    |
    | This is the authorized key to use for making API calls.
    |
    */
    'authorised_key' => env('API_AUTHORISED_KEY', 'your-authorised-key'),

    /*
    |--------------------------------------------------------------------------
    | JWT Settings
    |--------------------------------------------------------------------------
    |
    | Partner ID and JWT Key for generating JWT.
    |
    */
    'sprint_partner_id' => env('SPRINT_PARTNER_ID', 'your-partner-id'),
    'sprint_jwt_key' => env('SPRINT_JWT_KEY', 'your-jwt-key'),

];
```
### Step 3: Update Your .env File
In your Laravel project's .env file, add the following environment variables for your Paysprint API credentials:

```bash
API_MODE=uat
API_LIVE_URL=https://api.paysprint.in
API_UAT_URL=https://sit.paysprint.in/service-api
API_AUTHORISED_KEY=your-authorised-key
SPRINT_PARTNER_ID=your-partner-id
SPRINT_JWT_KEY=your-jwt-key

```
API_MODE: The mode in which the API should operate. It can be either 'uat' (User Acceptance Testing) or 'live'.
API_LIVE_URL: The base URL for the live Paysprint API.
API_UAT_URL: The base URL for the UAT Paysprint API.
API_AUTHORISED_KEY: The API authorized key, which will be provided by Paysprint.
SPRINT_PARTNER_ID: Your partner ID for generating JWT tokens.
SPRINT_JWT_KEY: The secret key used for generating JWT tokens.

### Step 4: Use the Package

## Available API Methods

### 1. **IP Check Service**

#### Description:
This service checks whether the current request's IP address is authorized via the Paysprint API.

#### Method:
```php
public function checkIP()
```
```php

use PiyushJaiswal\Services\IPCheckService;

$ipCheckService = new IPCheckService();
$response = $ipCheckService->checkIP();

```
if response is success
```json
{"status":true,"response_code":1,"message":"Autheticated User"}
```
else
```json
{"status":false,"response_code":2,"message":"IP is not valid"}
```
## Available API Methods

### 2. **Cash Balance Check Service**

#### Description:
This service retrieves the current cash balance from the Paysprint API.

#### Method:
```php
public function checkCashBalance()
```
```php
use PiyushJaiswal\Services\CashBalanceService;

$cashBalanceService = new CashBalanceService();
$response = $cashBalanceService->checkCashBalance();

```
if response is success
```json
{"status":true,"response_code":1,"cdwallet":"43.50","message":"Balance successfully fetched"}
```
else
```json
{"status":false,"response_code":2,"message":"Authentication failed"}
```

### Available API Methods
1. Get Operators
Description:
The Get Operator Service allows you to fetch the list of available operators for a specific service (e.g., mobile, DTH, etc.).

Method:
```php
public function getOperators($serviceType)
```
```php
use PiyushJaiswal\Services\MobileDTHRechargeService;

$operatorService = new MobileDTHRechargeService();
$response = $operatorService->getOperators('mobile');

```

2. HLR Check Service for Mobile
Description:
The HLR (Home Location Register) Check Service allows you to verify the status of a mobile number before proceeding with a recharge.

Method:
```php
public function hlrCheck($mobileNumber, $type)
```
### Usage Example:
```php
use PiyushJaiswal\Services\MobileDTHRechargeService;

$hlrCheckService = new MobileDTHRechargeService();
$response = $hlrCheckService->hlrCheck('9876543210', 'mobile');


```json

{"status":true,"response_code":1,"info":{"operator":"Jio","circle":"UP West"},"message":"Successful"}
```

### 2. DTH Check Service
Description:
The DTH Check Service allows you to verify the status of a DTH account number before proceeding with a recharge.

Method:
```php 
public function dthCheck($caNumber, $operator)

```
```php
use PiyushJaiswal\Services\MobileDTHRechargeService;

$dthCheckService = new MobileDTHRechargeService();
$response = $dthCheckService->dthCheck('123456789', 'TataSky');

```
```json
{
    "status": true,
    "response_code": 1,
    "info": [
        {
            "MonthlyRecharge": 499,
            "Balance": "11.27",
            "customerName": "EFDSF AXA",
            "status": "Active",
            "NextRechargeDate": "11-12-2024",
            "lastrechargeamount": "1500",
            "lastrechargedate": "2024-09-08T18:38:10.337",
            "planname": "Royale Sports Kids HSM HD"
        }
    ],
    "message": "Fetch Successful"
}
```
### 3. Browse Plan for Mobile Recharge
Description:
This service allows you to browse available mobile recharge plans based on the user's circle and operator.

Method:
```php
public function browsePlan($circle, $operator)
```
Usage Example:
```php
use PiyushJaiswal\Services\MobileDTHRechargeService;

$browsePlanService = new MobileDTHRechargeService();
$response = $browsePlanService->browsePlan('Delhi', 'Airtel');
```

### 4.Do Recharge for Mobile/DTH
Description:
This service allows you to perform mobile recharges and DTH by providing the mobile/Dth number, operator, and amount.

Method:
```php
public function doRecharge($operatorId, $mobileNumber, $amount,$refrenceId)
```
```php
use PiyushJaiswal\Services\MobileDTHRechargeService;

$mobileRechargeService = new MobileDTHRechargeService();
$response = $mobileRechargeService->doRecharge('Airtel', '9876543210', 199,'qdd2233wfa');
```
### 5. Get Bill Payment Operators
Description:
This service fetches a list of available operators for bill payment services such as electricity, water, gas, etc.

Method:
```php
public function getBillPaymentOperators()
```
Usage Example:
```php
use PiyushJaiswal\Services\BillPaymentService;

$billPaymentService = new BillPaymentService();
$response = $billPaymentService->getBillPaymentOperators();

if ($response['success']) {
    // Operators fetched successfully
    print_r($response['data']);
} else {
    // Handle error
    echo $response['error'];
}
```
Response:
On Success:
```json
{
    "status": true,
    "response_code": 1,
    "data": {
        "operators": [
            { "operator_id": "1", "operator_name": "Tata Power" },
            { "operator_id": "2", "operator_name": "Adani Electricity" },
            { "operator_id": "3", "operator_name": "BSES Rajdhani" }
        ]
    }
}
```
On Failure:
```json
{
    "status": false,
    "response_code": 2,
    "message": "Failed to fetch operators"
}
```
### 6. Fetch Bill
Description:
This service fetches a bill for the specified operator and consumer account number.

Method:
```php
public function fetchBill($operator, $canumber, $ad1 = null, $ad2 = null, $ad3 = null)
```
Required Parameters:
Parameter	Type	Description
operator	string	The operator code (e.g., 1 for Tata Power)
canumber	string	Consumer account number (e.g., 9876543210)
ad1	string	Optional additional parameter 1
ad2	string	Optional additional parameter 2
ad3	string	Optional additional parameter 3
Usage Example:
```php
use PiyushJaiswal\Services\BillPaymentService;

$billPaymentService = new BillPaymentService();
$response = $billPaymentService->fetchBill('1', '9876543210');

if ($response['success']) {
    // Bill fetched successfully
    print_r($response['data']);
} else {
    // Handle error
    echo $response['error'];
}
```
Response:
On Success:
```json
{
    "status": true,
    "response_code": 1,
    "billAmount": "200.00",
    "dueDate": "2024-10-10",
    "customerName": "John Doe",
    "message": "Bill fetched successfully"
}
```
On Failure:
```json
{
    "status": false,
    "response_code": 2,
    "message": "Failed to fetch bill"
}
```
### 7. Pay Bill
Description:
This service allows you to pay a bill by providing the required payment information, such as consumer number, operator, and payment amount.

Method:
```php

public function payBill($payload)
Payload Structure:
Field	Type	Description
canumber	string	Consumer account number
amount	float	Amount to pay
operator	integer	Operator ID
latitude	float	Latitude of user
longitude	float	Longitude of user
referenceid	string	Unique transaction reference ID
bill_fetch.billAmount	float	The bill amount fetched earlier
bill_fetch.dueDate	date	Due date of the bill
bill_fetch.acceptPayment	boolean	Whether the bill accepts payment
bill_fetch.cellNumber	string	Cell number associated with the bill
bill_fetch.userName	string	Name of the consumer
```
Usage Example:
```php

use PiyushJaiswal\Services\BillPaymentService;

$billPaymentService = new BillPaymentService();
$payload = [
    'canumber' => '9876543210',
    'amount' => 200,
    'operator' => 1,
    'latitude' => 27.2046,
    'longitude' => 77.4977,
    'referenceid' => 'REF123456',
    'bill_fetch' => [
        'billAmount' => 200,
        'billnetamount' => 200,
        'billdate' => '2024-09-01',
        'dueDate' => '2024-10-10',
        'acceptPayment' => true,
        'cellNumber' => '9876543210',
        'userName' => 'John Doe'
    ]
];
$response = $billPaymentService->payBill($payload);

if ($response['success']) {
    // Bill payment successful
    print_r($response['data']);
} else {
    // Handle error
    echo $response['error'];
}
```
Response:
On Success:

```json

{
    "status": true,
    "response_code": 1,
    "message": "Payment successful",
    "transaction_id": "TXN123456789"
}
```
On Failure:
```json

{
    "status": false,
    "response_code": 2,
    "message": "Payment failed"
}
```
### 8. Check Bill Payment Status
Description:
This service checks the status of a previously made bill payment using the reference ID.

Method:
```php

public function billStatus($referenceId)
Required Parameters:
Parameter	Type	Description
referenceId	string	The unique transaction reference ID
```
Usage Example:
```php

use PiyushJaiswal\Services\BillPaymentService;

$billPaymentService = new BillPaymentService();
$response = $billPaymentService->billStatus('REF123456');

if ($response['success']) {
    // Payment status retrieved
    print_r($response['data']);
} else {
    // Handle error
    echo $response['error'];
}

```
Response:
On Success:
```json

{
    "status": true,
    "responsecode": 1,
    "transactionStatus": "Success",
    "message": "Payment was successful"
}
```
On Failure:
```json

{
    "status": false,
    "responsecode": 2,
    "message": "Payment status not found"
}
```

### 9. Fetch LIC Bill
Description:
This service fetches the LIC bill based on the policy number (canumber) and mode (e.g., online or offline).

Method:
```php
public function fetchLicBill($canumber, $mode, $ad1 = null, $ad2 = null)
Required Parameters:
Parameter	Type	Description
canumber	string	LIC policy number
mode	string	Mode of the transaction (e.g., online/offline)
ad1	string	(Optional) Additional parameter 1
ad2	string	(Optional) Additional parameter 2
```
Usage Example:
```php
use PiyushJaiswal\Services\LICService;

$licService = new LICService();
$response = $licService->fetchLicBill('1234567890', 'online');

if ($response['status']) {
    // Bill fetched successfully
    print_r($response['data']);
} else {
    // Handle error
    echo $response['error'];
}
```
Response:
On Success:
```json
{
    "status": true,
    "response_code": 1,
    "billAmount": "500.00",
    "dueDate": "2024-10-10",
    "policyHolderName": "John Doe",
    "message": "Bill fetched successfully"
}
```
On Failure:
```json
{
    "status": false,
    "response_code": 2,
    "message": "Failed to fetch bill"
}
```
### 10. Pay LIC Bill
Description:
This service allows you to pay an LIC bill by providing the necessary payment details, such as policy number, amount, and additional information.

Method:
```php
public function payLicBill($payload)
Payload Structure:
Field	Type	Description
canumber	string	LIC policy number
amount	float	Amount to pay
operator	integer	Operator ID
latitude	float	Latitude of the user
longitude	float	Longitude of the user
referenceid	string	Unique transaction reference ID
ad1	string	Email of the user
ad2	string	Date of birth
bill_fetch.billAmount	float	The fetched bill amount
bill_fetch.billnetamount	float	The net amount to pay
bill_fetch.billdate	date	Date the bill was generated
bill_fetch.dueDate	date	Due date for payment
bill_fetch.acceptPayment	boolean	Whether payment is accepted
bill_fetch.acceptPartPay	boolean	Whether part payment is accepted
bill_fetch.cellNumber	string	Cell number of the policyholder
bill_fetch.userName	string	Name of the policyholder
```
Usage Example:
```php
use PiyushJaiswal\Services\LICService;

$licService = new LICService();
$payload = [
    'canumber' => '1234567890',
    'amount' => 500,
    'operator' => 1,
    'latitude' => 27.2046,
    'longitude' => 77.4977,
    'referenceid' => 'REF123456',
    'ad1' => 'john@example.com',
    'ad2' => '1990-01-01',
    'bill_fetch' => [
        'billAmount' => 500,
        'billnetamount' => 500,
        'billdate' => '2024-09-01',
        'dueDate' => '2024-10-10',
        'acceptPayment' => true,
        'acceptPartPay' => false,
        'cellNumber' => '1234567890',
        'userName' => 'John Doe'
    ]
];
$response = $licService->payLicBill($payload);

if ($response['status']) {
    // Payment successful
    print_r($response['data']);
} else {
    // Handle error
    echo $response['error'];
}
```
Response:
On Success:
```json
{
    "status": true,
    "response_code": 1,
    "message": "Payment successful",
    "transaction_id": "TXN123456789"
}
```
On Failure:
```json
{
    "status": false,
    "response_code": 2,
    "message": "Payment failed"
}
```
### 11. Check LIC Bill Payment Status
Description:
This service checks the payment status of a previously made LIC bill payment using the reference ID.

Method:
```php
public function licBillStatus($referenceId)
Required Parameters:
Parameter	Type	Description
referenceId	string	The unique transaction reference ID
```
Usage Example:
```php
use PiyushJaiswal\Services\LICService;

$licService = new LICService();
$response = $licService->licBillStatus('REF123456');

if ($response['status']) {
    // Payment status retrieved
    print_r($response['data']);
} else {
    // Handle error
    echo $response['error'];
}
```
Response:
On Success:
```json
{
    "status": true,
    "response_code": 1,
    "transactionStatus": "Success",
    "message": "Payment was successful"
}
```
On Failure:
```json
{
    "status": false,
    "response_code": 2,
    "message": "Payment status not found"
}
```

### 12. Get Fastag Operators
Description:
This service fetches a list of available operators for Fastag services.

Method:
```php
public function getFastagOperators()
```
Usage Example:
```php
use PiyushJaiswal\Services\FastagService;

$fastagService = new FastagService();
$response = $fastagService->getFastagOperators();

if ($response['status']) {
    // Operators fetched successfully
    print_r($response['data']);
} else {
    // Handle error
    echo $response['message'];
}
```
Response:
On Success:
```json
{
    "status": true,
    "response_code": 1,
    "operators": [
        { "operator_id": "1", "operator_name": "ICICI Fastag" },
        { "operator_id": "2", "operator_name": "SBI Fastag" }
    ]
}
```
On Failure:
```json
{
    "status": false,
    "response_code": 2,
    "message": "Failed to fetch operators"
}
```
### 13. Fetch Fastag Bill (Consumer Details)
Description:
This service fetches Fastag bill details or consumer details for a specified Fastag account number (canumber).

Method:
```php
public function fetchFastagBill($operator, $canumber, $ad1 = null, $ad2 = null, $ad3 = null)
Required Parameters:
Parameter	Type	Description
operator	string	The Fastag operator code
canumber	string	Fastag account number (vehicle number)
ad1	string	(Optional) Additional parameter 1
ad2	string	(Optional) Additional parameter 2
ad3	string	(Optional) Additional parameter 3
```
Usage Example:
```php
use PiyushJaiswal\Services\FastagService;

$fastagService = new FastagService();
$response = $fastagService->fetchFastagBill('1', 'KA01AB1234');

if ($response['status']) {
    // Bill fetched successfully
    print_r($response['data']);
} else {
    // Handle error
    echo $response['message'];
}
```
Response:
On Success:
```json
{
    "status": true,
    "response_code": 1,
    "billAmount": "1000.00",
    "dueDate": "2024-12-15",
    "vehicleNumber": "KA01AB1234",
    "message": "Bill fetched successfully"
}
```
On Failure:
```json
{
    "status": false,
    "response_code": 2,
    "message": "Failed to fetch bill"
}
```
### 14. Pay Fastag Bill (Recharge)
Description:
This service allows you to pay or recharge a Fastag account by providing the required payment details.

Method:
```php
public function payFastagBill($payload)
Payload Structure:
Field	Type	Description
canumber	string	Fastag account number (vehicle registration number)
amount	float	Amount to recharge
operator	integer	Operator ID
latitude	float	Latitude of the user
longitude	float	Longitude of the user
referenceid	string	Unique transaction reference ID
bill_fetch.billAmount	float	The fetched bill amount
bill_fetch.billnetamount	float	The net amount to pay
bill_fetch.billdate	date	Date the bill was generated
bill_fetch.dueDate	date	Due date for payment
bill_fetch.acceptPayment	boolean	Whether payment is accepted
bill_fetch.acceptPartPay	boolean	Whether part payment is accepted
bill_fetch.cellNumber	string	Vehicle number or mobile number of the customer
bill_fetch.userName	string	Name of the vehicle owner
```
Usage Example:
```php
use PiyushJaiswal\Services\FastagService;

$fastagService = new FastagService();
$payload = [
    'canumber' => 'KA01AB1234',
    'amount' => 1000,
    'operator' => 1,
    'latitude' => 12.9716,
    'longitude' => 77.5946,
    'referenceid' => 'REF123456',
    'bill_fetch' => [
        'billAmount' => 1000,
        'billnetamount' => 1000,
        'billdate' => '2024-11-01',
        'dueDate' => '2024-12-15',
        'acceptPayment' => true,
        'acceptPartPay' => false,
        'cellNumber' => 'KA01AB1234',
        'userName' => 'John Doe'
    ]
];
$response = $fastagService->payFastagBill($payload);

if ($response['status']) {
    // Payment successful
    print_r($response['data']);
} else {
    // Handle error
    echo $response['message'];
}
```
Response:
On Success:
```json
{
    "status": true,
    "response_code": 1,
    "message": "Payment successful",
    "transaction_id": "TXN123456789"
}
```
On Failure:
```json
{
    "status": false,
    "response_code": 2,
    "message": "Payment failed"
}
```
### 15. Check Fastag Bill Payment Status
Description:
This service checks the payment status of a previously made Fastag bill payment using the reference ID.

Method:
```php
public function checkFastagStatus($referenceId)
Required Parameters:
Parameter	Type	Description
referenceId	string	The unique transaction reference ID
```
Usage Example:
```php
use PiyushJaiswal\Services\FastagService;

$fastagService = new FastagService();
$response = $fastagService->checkFastagStatus('REF123456');

if ($response['status']) {
    // Payment status retrieved
    print_r($response['data']);
} else {
    // Handle error
    echo $response['message'];
}
```
Response:
On Success:
```json
{
    "status": true,
    "response_code": 1,
    "transactionStatus": "Success",
    "message": "Payment was successful"
}
```
On Failure:
```json
{
    "status": false,
    "response_code": 2,
    "message": "Payment status not found"
}
```

### 16. Get LPG Operators
Description:
This service fetches a list of available operators for LPG services.

Method:
```php

public function getLPGOperators()
```
Usage Example:
```php

use PiyushJaiswal\Services\LPGService;

$lpgService = new LPGService();
$response = $lpgService->getLPGOperators();

if ($response['status']) {
    // Operators fetched successfully
    print_r($response['data']);
} else {
    // Handle error
    echo $response['message'];
}
```
Response:
On Success:
```json

{
    "status": true,
    "response_code": 1,
    "operators": [
        { "operator_id": "1", "operator_name": "Indane" },
        { "operator_id": "2", "operator_name": "Bharat Gas" }
    ]
}
```
On Failure:
```json

{
    "status": false,
    "response_code": 2,
    "message": "Failed to fetch operators"
}
```
### 17. Fetch LPG Bill
Description:
This service fetches the LPG bill for a specified customer account number (canumber) and operator.

Method:
```php

public function fetchLPGBill($operator, $canumber, $ad1 = null, $ad2 = null, $ad3 = null)
Required Parameters:
Parameter	Type	Description
operator	string	The LPG operator code
canumber	string	LPG consumer number
ad1	string	(Optional) Additional parameter 1
ad2	string	(Optional) Additional parameter 2
ad3	string	(Optional) Additional parameter 3
```
Usage Example:
```php

use PiyushJaiswal\Services\LPGService;

$lpgService = new LPGService();
$response = $lpgService->fetchLPGBill('1', '9876543210');

if ($response['status']) {
    // Bill fetched successfully
    print_r($response['data']);
} else {
    // Handle error
    echo $response['message'];
}
```
Response:
On Success:
```json

{
    "status": true,
    "response_code": 1,
    "billAmount": "750.00",
    "dueDate": "2024-12-15",
    "consumerNumber": "9876543210",
    "message": "Bill fetched successfully"
}
```
On Failure:
```json

{
    "status": false,
    "response_code": 2,
    "message": "Failed to fetch bill"
}
```
### 18. Pay LPG Bill
Description:
This service allows you to pay an LPG bill by providing the necessary payment details.

Method:
```php

public function payLPGBill($payload)
Payload Structure:
Field	Type	Description
canumber	string	LPG consumer number
amount	float	Amount to pay
operator	integer	Operator ID
latitude	float	Latitude of the user
longitude	float	Longitude of the user
referenceid	string	Unique transaction reference ID
ad1	numeric	(Optional) State
ad2	numeric	(Optional) District
ad3	numeric	(Optional) Distributor
bill_fetch.billAmount	float	The fetched bill amount
bill_fetch.billnetamount	float	The net amount to pay
bill_fetch.billdate	date	Date the bill was generated
bill_fetch.dueDate	date	Due date for payment
bill_fetch.acceptPayment	boolean	Whether payment is accepted
bill_fetch.acceptPartPay	boolean	Whether part payment is accepted
bill_fetch.cellNumber	string	LPG consumer number
bill_fetch.userName	string	Name of the consumer
```
Usage Example:
```php

use PiyushJaiswal\Services\LPGService;

$lpgService = new LPGService();
$payload = [
    'canumber' => '9876543210',
    'amount' => 750,
    'operator' => 1,
    'latitude' => 28.7041,
    'longitude' => 77.1025,
    'referenceid' => 'REF123456',
    'ad1' => 22,
    'ad2' => 5,
    'ad3' => 123,
    'bill_fetch' => [
        'billAmount' => 750,
        'billnetamount' => 750,
        'billdate' => '2024-11-01',
        'dueDate' => '2024-12-15',
        'acceptPayment' => true,
        'acceptPartPay' => false,
        'cellNumber' => '9876543210',
        'userName' => 'John Doe'
    ]
];
$response = $lpgService->payLPGBill($payload);

if ($response['status']) {
    // Payment successful
    print_r($response['data']);
} else {
    // Handle error
    echo $response['message'];
}
```
Response:
On Success:
```json

{
    "status": true,
    "response_code": 1,
    "message": "Payment successful",
    "transaction_id": "TXN123456789"
}
```
On Failure:
```json

{
    "status": false,
    "response_code": 2,
    "message": "Payment failed"
}
```
### 19. Check LPG Bill Payment Status
Description:
This service checks the payment status of a previously made LPG bill payment using the reference ID.

Method:
```php

public function checkLPGStatus($referenceId)
Required Parameters:
Parameter	Type	Description
referenceId	string	The unique transaction reference ID

```
Usage Example:
```php

use PiyushJaiswal\Services\LPGService;

$lpgService = new LPGService();
$response = $lpgService->checkLPGStatus('REF123456');

if ($response['status']) {
    // Payment status retrieved
    print_r($response['data']);
} else {
    // Handle error
    echo $response['message'];
}
```
Response:
On Success:
```json

{
    "status": true,
    "response_code": 1,
    "transactionStatus": "Success",
    "message": "Payment was successful"
}
```
On Failure:
```json

{
    "status": false,
    "response_code": 2,
    "message": "Payment status not found"
}
```

### 20. Get Municipality Operators
Description:
This service fetches a list of available operators for municipality services.

Method:
```php
public function getOperators()
```
Usage Example:
```php
use PiyushJaiswal\Services\MunicipalityService;

$municipalityService = new MunicipalityService();
$response = $municipalityService->getOperators();

if ($response['status']) {
    // Operators fetched successfully
    print_r($response['data']);
} else {
    // Handle error
    echo $response['message'];
}
```
Response:
On Success:
```json
{
    "status": true,
    "response_code": 1,
    "operators": [
        { "operator_id": "1", "operator_name": "Municipality Operator 1" },
        { "operator_id": "2", "operator_name": "Municipality Operator 2" }
    ]
}
```
On Failure:
```json
{
    "status": false,
    "response_code": 2,
    "message": "Failed to fetch operators"
}
```
### 21. Fetch Municipality Bill
Description:
This service fetches a municipality bill for a specific consumer number (canumber) and operator.

Method:
```php
public function fetchMunicipalityBill($operator, $canumber, $ad1 = null, $ad2 = null, $ad3 = null)
Required Parameters:
Parameter	Type	Description
operator	string	The municipality operator code
canumber	string	Consumer account number
ad1	string	(Optional) Additional parameter 1
ad2	string	(Optional) Additional parameter 2
ad3	string	(Optional) Additional parameter 3
```
Usage Example:
```php
use PiyushJaiswal\Services\MunicipalityService;

$municipalityService = new MunicipalityService();
$response = $municipalityService->fetchMunicipalityBill('1', '1234567890');

if ($response['status']) {
    // Bill fetched successfully
    print_r($response['data']);
} else {
    // Handle error
    echo $response['message'];
}
```
Response:
On Success:
```json
{
    "status": true,
    "response_code": 1,
    "billAmount": "2000.00",
    "dueDate": "2024-12-15",
    "consumerNumber": "1234567890",
    "message": "Bill fetched successfully"
}
```
On Failure:
```json
{
    "status": false,
    "response_code": 2,
    "message": "Failed to fetch bill"
}
```
### 22. Pay Municipality Bill
Description:
This service allows you to pay a municipality bill by providing the necessary payment details.

Method:
```php
public function payMunicipalityBill($payload)
Payload Structure:
Field	Type	Description
canumber	string	Consumer account number
amount	float	Amount to pay
operator	integer	Operator ID
latitude	float	Latitude of the user
longitude	float	Longitude of the user
referenceid	string	Unique transaction reference ID
bill_fetch.billAmount	float	The fetched bill amount
bill_fetch.billnetamount	float	The net amount to pay
bill_fetch.billdate	date	Date the bill was generated
bill_fetch.dueDate	date	Due date for payment
bill_fetch.acceptPayment	boolean	Whether payment is accepted
bill_fetch.acceptPartPay	boolean	Whether part payment is accepted
bill_fetch.cellNumber	string	Consumer mobile number
bill_fetch.userName	string	Name of the consumer
```
Usage Example:
```php
use PiyushJaiswal\Services\MunicipalityService;

$municipalityService = new MunicipalityService();
$payload = [
    'canumber' => '1234567890',
    'amount' => 2000,
    'operator' => 1,
    'latitude' => 28.7041,
    'longitude' => 77.1025,
    'referenceid' => 'REF123456',
    'bill_fetch' => [
        'billAmount' => 2000,
        'billnetamount' => 2000,
        'billdate' => '2024-11-01',
        'dueDate' => '2024-12-15',
        'acceptPayment' => true,
        'acceptPartPay' => false,
        'cellNumber' => '1234567890',
        'userName' => 'John Doe'
    ]
];
$response = $municipalityService->payMunicipalityBill($payload);

if ($response['status']) {
    // Payment successful
    print_r($response['data']);
} else {
    // Handle error
    echo $response['message'];
}
```
Response:
On Success:
```json
{
    "status": true,
    "response_code": 1,
    "message": "Payment successful",
    "transaction_id": "TXN123456789"
}
```
On Failure:
```json
{
    "status": false,
    "response_code": 2,
    "message": "Payment failed"
}
```
### 23. Check Municipality Bill Payment Status
Description:
This service checks the payment status of a previously made municipality bill payment using the reference ID.

Method:
```php
public function checkMunicipalityStatus($referenceId)
Required Parameters:
Parameter	Type	Description
referenceId	string	The unique transaction reference ID

```
Usage Example:
```php
use PiyushJaiswal\Services\MunicipalityService;

$municipalityService = new MunicipalityService();
$response = $municipalityService->checkMunicipalityStatus('REF123456');

if ($response['status']) {
    // Payment status retrieved
    print_r($response['data']);
} else {
    // Handle error
    echo $response['message'];
}
```
Response:
On Success:
```json
{
    "status": true,
    "response_code": 1,
    "transactionStatus": "Success",
    "message": "Payment was successful"
}
```
On Failure:
```json
{
    "status": false,
    "response_code": 2,
    "message": "Payment status not found"
}
```
### 24. Generate OTP for Credit Card Payment
Description:
This service generates an OTP (One-Time Password) for initiating a credit card payment.

Method:
```php

public function generateOTP($name, $card_number, $remarks, $network, $mobile, $amount)
Required Parameters:
Parameter	Type	Description
name	string	The name of the credit card holder
card_number	string	The credit card number
remarks	string	Any additional remarks for the transaction
network	string	The card network (e.g., Visa, Mastercard)
mobile	int	The mobile number associated with the credit card
amount	int	The amount to be paid (must be less than or equal to 49,999)
```
Usage Example:
```php

use PiyushJaiswal\Services\CreditCardService;

$creditCardService = new CreditCardService();
$response = $creditCardService->generateOTP('John Doe', '4111111111111111', 'Test Payment', 'Visa', '9876543210', 5000);

if ($response['status']) {
    // OTP generated successfully
    print_r($response['data']);
} else {
    // Handle error
    echo $response['message'];
}
```
Response:
On Success:
```json

{
    "status": true,
    "response_code": 1,
    "message": "OTP sent successfully"
}
```
On Failure:
```json

{
    "status": false,
    "response_code": 2,
    "message": "Failed to generate OTP"
}
```
### 25. Pay Credit Card Bill
Description:
This service allows you to pay a credit card bill using the generated OTP.

Method:
```php

public function payBill($refid, $name, $mobile, $card_number, $amount, $remarks, $network, $otp)
Required Parameters:
Parameter	Type	Description
refid	string	The reference ID for the transaction
name	string	The name of the credit card holder
mobile	int	The mobile number associated with the credit card
card_number	string	The credit card number
amount	int	The amount to be paid
remarks	string	Additional remarks for the payment
network	string	The card network (e.g., Visa, Mastercard)
otp	string	The OTP received for the transaction
```
Usage Example:
```php

use PiyushJaiswal\Services\CreditCardService;

$creditCardService = new CreditCardService();
$response = $creditCardService->payBill('REF123456', 'John Doe', '9876543210', '4111111111111111', 5000, 'Test Payment', 'Visa', '123456');

if ($response['status']) {
    // Payment successful
    print_r($response['data']);
} else {
    // Handle error
    echo $response['message'];
}
```
Response:
On Success:
```json

{
    "status": true,
    "response_code": 1,
    "message": "Payment successful",
    "transaction_id": "TXN123456789"
}
```
On Failure:
```json

{
    "status": false,
    "response_code": 2,
    "message": "Payment failed"
}
```
### 26. Check Credit Card Payment Status
Description:
This service checks the payment status of a previously made credit card payment using the reference ID.

Method:
```php

public function checkCreditCardStatus($referenceId)
Required Parameters:
Parameter	Type	Description
referenceId	string	The unique transaction reference ID
```
Usage Example:
```php

use PiyushJaiswal\Services\CreditCardService;

$creditCardService = new CreditCardService();
$response = $creditCardService->checkCreditCardStatus('REF123456');

if ($response['status']) {
    // Payment status retrieved
    print_r($response['data']);
} else {
    // Handle error
    echo $response['message'];
}
```
Response:
On Success:
```json

{
    "status": true,
    "response_code": 1,
    "transactionStatus": "Success",
    "message": "Payment was successful"
}
```
On Failure:
```json

{
    "status": false,
    "response_code": 2,
    "message": "Payment status not found"
}
```
### 27. Resend OTP
Description:
This service allows you to resend the OTP for a credit card payment.

Method:
```php

public function resendOTP($refid, $ackno)
Required Parameters:
Parameter	Type	Description
refid	string	The reference ID of the transaction
ackno	string	The acknowledgment number of the transaction
```
Usage Example:
```php

use PiyushJaiswal\Services\CreditCardService;

$creditCardService = new CreditCardService();
$response = $creditCardService->resendOTP('REF123456', 'ACK123456');

if ($response['status']) {
    // OTP resent successfully
    print_r($response['data']);
} else {
    // Handle error
    echo $response['message'];
}
```
Response:
On Success:
```json

{
    "status": true,
    "response_code": 1,
    "message": "OTP resent successfully"
}
```
On Failure:
```json

{
    "status": false,
    "response_code": 2,
    "message": "Failed to resend OTP"
}
```
### 28. Claim Refund
Description:
This service allows you to claim a refund for a failed credit card payment.

Method:
```php

public function claimRefund($refid, $ackno, $otp)
Required Parameters:
Parameter	Type	Description
refid	string	The reference ID of the transaction
ackno	string	The acknowledgment number of the transaction
otp	string	The OTP for claiming the refund
```
Usage Example:
```php

use PiyushJaiswal\Services\CreditCardService;

$creditCardService = new CreditCardService();
$response = $creditCardService->claimRefund('REF123456', 'ACK123456', '123456');

if ($response['status']) {
    // Refund claimed successfully
    print_r($response['data']);
} else {
    // Handle error
    echo $response['message'];
}
```
Response:
On Success:
```json

{
    "status": true,
    "response_code": 1,
    "message": "Refund claimed successfully"
}
```
On Failure:
```json

{
    "status": false,
    "response_code": 2,
    "message": "Refund claim failed"
}
```

### How to Use the Services
Ensure your config/paysprint.php file contains the correct authorised_key and JWT token configuration.
Inject or instantiate the respective service (IPCheckService, CashBalanceService, MobileDTHRechargeService) wherever needed in your application.
Call the respective method to execute the desired action (IP check, cash balance check, recharge, etc.).

### Contributing
Feel free to fork this repository, submit pull requests, and report issues if you encounter any.

### License
This package is open-sourced software licensed under the MIT license.