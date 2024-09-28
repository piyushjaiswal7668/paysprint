<?php

namespace PiyushJaiswal\Services;


class IPCheckService extends BaseService
{
    /**
     * Check if the IP is authorized via the Paysprint API.
     *
     * @return array
     */
    public function checkIP()
    {

        // Define the API endpoint for IP check
        $url = $this->url . '/api/v1/service/balance/balance/authenticationcheck';

        $payload = [];
        // Make the API request using the BaseService method
        $response = $this->makeRequest($url, $payload);

        // Return the API response
        return $response;
    }
}
