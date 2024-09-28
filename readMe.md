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

### Do Recharge for Mobile/DTH
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
