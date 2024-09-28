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

