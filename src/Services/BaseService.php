<?php

namespace PiyushJaiswal\Services;

use Carbon\Carbon;

class BaseService
{
    protected $Authorisedkey;
    protected $url;
    protected $partnerId;
    protected $jwtKey;

    public function __construct()
    {
        $this->Authorisedkey = config('paysprint.authorised_key');
        $mode = config('paysprint.mode');
        $this->url = config("paysprint.urls.$mode");
        $this->partnerId = config('paysprint.sprint_partner_id');
        $this->jwtKey = config('paysprint.sprint_jwt_key');
    }

    protected function generateJWT()
    {
        $header = json_encode(['typ' => 'JWT', 'alg' => 'HS256']);
        $timeStamp = strtotime(Carbon::now());
        $generate_order_id = hash('sha256', microtime());
        $generate_order_id = substr($generate_order_id, 0, 10);

        $payload = json_encode([
            "timestamp" => $timeStamp,
            "partnerId" => $this->partnerId,
            "reqid" => $generate_order_id,
        ]);

        $base64UrlHeader = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($header));
        $base64UrlPayload = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($payload));
        $signature = hash_hmac('sha256', $base64UrlHeader . "." . $base64UrlPayload, $this->jwtKey, true);
        $base64UrlSignature = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($signature));

        return $base64UrlHeader . "." . $base64UrlPayload . "." . $base64UrlSignature;
    }

    protected function makeRequest($url, $payload, $method = 'POST')
    {
        $jwt = $this->generateJWT();
        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => $method,
            CURLOPT_POSTFIELDS => json_encode($payload),
            CURLOPT_HTTPHEADER => [
                "Authorisedkey: $this->Authorisedkey",
                "Token: $jwt",
                "Content-Type: application/json",
                "Accept: text/plain",
            ],
        ]);

        $response = curl_exec($curl);
        return json_decode($response, true);
    }
}
