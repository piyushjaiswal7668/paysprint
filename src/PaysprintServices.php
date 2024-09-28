<?php

namespace PiyushJaiswal;

use Illuminate\Http\Request;
use Carbon\Carbon;

class PaysprintServices
{
    protected $Authorisedkey;
    protected $url;
    protected $partnerId;
    protected $jwtKey;

    public function __construct()
    {
        // Load the authorized key from the config
        $this->Authorisedkey = config('paysprint.authorised_key');

        // Load the API URL based on the mode (live/uat) from the config
        $mode = config('paysprint.mode');
        $this->url = config("paysprint.urls.$mode");

        // Load the Partner ID and JWT key from the config
        $this->partnerId = config('paysprint.sprint_partner_id');
        $this->jwtKey = config('paysprint.sprint_jwt_key');
    }

    // Function to check IP via external API
    public function checkIP()
    {
        // Generate JWT token before API request
        $jwt = $this->generateJWT();

        // Proceed with cURL request to external API
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $this->url . '/api/v1/service/balance/balance/authenticationcheck',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_HTTPHEADER => array(
                "Authorisedkey: $this->Authorisedkey",
                "Content-Type: application/json",
                "Token: $jwt"
            ),
        ));

        // Get the API response
        $response = curl_exec($curl);
        $response = json_decode($response);
        // Return the API response directly (assuming it's JSON)
        return $response;
    }

    public function checkBalance()
    {
        // Generate JWT token before API request
        $jwt = $this->generateJWT();

        // Proceed with cURL request to check balance
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $this->url . '/api/v1/service/balance/balance/cashbalance',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_HTTPHEADER => array(
                "Authorisedkey: $this->Authorisedkey",
                "Content-Type: application/json",
                "Token: $jwt"
            ),
        ));

        // Get the API response and handle errors
        $response = curl_exec($curl);
        $response = json_decode($response);
        // Return the API response directly (assuming it's JSON)
        return $response;
    }
    public function hlrCheck($number, $type)
    {
        // Generate JWT token before API request
        $jwt = $this->generateJWT();
        $Authorisedkey = $this->Authorisedkey;

        // Prepare the payload
        $payload = [
            'number' => $number,
            'type' => $type
        ];

        // Set up the cURL request
        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL => $this->url . "/api/v1/service/recharge/hlrapi/hlrcheck",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => json_encode($payload),
            CURLOPT_HTTPHEADER => [
                "Authorisedkey: $Authorisedkey",
                "Token: $jwt",
                "accept: text/plain",
                "content-type: application/json",
            ],
        ]);

        // Get the API response and handle errors
        $response = curl_exec($curl);
        $response = json_decode($response);
        // Return the API response directly (assuming it's JSON)
        return $response;
    }

    public function browseplan($circle, $operator)
{
    // Generate JWT token before API request
    $jwt = $this->generateJWT();
    $Authorisedkey = $this->Authorisedkey;

    // Prepare the payload
    $payload = [
        'circle' => $circle,
        'op' => $operator
    ];

    // Set up the cURL request
    $curl = curl_init();
    curl_setopt_array($curl, [
        CURLOPT_URL => $this->url . "/api/v1/service/recharge/hlrapi/browseplan",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS => json_encode($payload),
        CURLOPT_HTTPHEADER => [
            "Authorisedkey: $Authorisedkey",
            "Token: $jwt",
            "accept: text/plain",
            "content-type: application/json",
        ],
    ]);

    // Get the API response and handle errors
    $response = curl_exec($curl);
    $error = curl_error($curl);
    curl_close($curl);

    // If there's an error, return it
    if ($error) {
        return response()->json(['error' => $error], 500);  // Return error as JSON
    }

    // Decode the response as an associative array
    $response = json_decode($response, true);

    // Return the API response
    return $response;
}




    // JWT generation function
    protected function generateJWT()
    {
        // Create token header as a JSON string
        $header = json_encode(['typ' => 'JWT', 'alg' => 'HS256']);

        // Create token payload as a JSON string
        $timeStamp = strtotime(Carbon::now());
        $generate_order_id = hash('sha256', microtime());
        $generate_order_id = substr($generate_order_id, 0, 10);

        $payload = json_encode([
            "timestamp" => $timeStamp,
            "partnerId" => $this->partnerId,
            "reqid" => $generate_order_id,
        ]);

        // Encode Header to Base64Url String
        $base64UrlHeader = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($header));
        // Encode Payload to Base64Url String
        $base64UrlPayload = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($payload));
        // Create Signature Hash
        $signature = hash_hmac('sha256', $base64UrlHeader . "." . $base64UrlPayload, $this->jwtKey, true);
        // Encode Signature to Base64Url String
        $base64UrlSignature = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($signature));

        // Create JWT
        $jwt = $base64UrlHeader . "." . $base64UrlPayload . "." . $base64UrlSignature;

        return $jwt;
    }
}
