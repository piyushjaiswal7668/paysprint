<?php

namespace PiyushJaiswal\Services;

class MobileDTHRechargeService extends BaseService
{


    public function getOperators()
    {
        // Set the API endpoint
        $url = $this->url . "/api/v1/service/recharge/recharge/getoperator";

        // No payload is needed for this request, so an empty array is passed
        $payload = [];

        // Make the request using the BaseService method
        $response = $this->makeRequest($url, $payload);

        // Return the API response
        return $response;
    }

    // Method to fetch DTH information
    public function dthCheck($canumber, $operator)
    {
        // Prepare the payload with canumber and operator
        $payload = [
            'canumber' => $canumber,
            'op' => $operator
        ];

        // Set the API endpoint
        $url = $this->url . "/api/v1/service/recharge/hlrapi/dthinfo";

        // Make the request using the BaseService method
        $response = $this->makeRequest($url, $payload);

        // Return the API response
        return $response;
    }

    public function hlrCheck($number, $type)
    {
        $payload = [
            'number' => $number,
            'type' => $type
        ];

        $url = $this->url . "/api/v1/service/recharge/hlrapi/hlrcheck";
        return $this->makeRequest($url, $payload);
    }

    public function browsePlan($circle, $operator)
    {
        $payload = [
            'circle' => $circle,
            'op' => $operator
        ];

        $url = $this->url . "/api/v1/service/recharge/hlrapi/browseplan";
        return $this->makeRequest($url, $payload);
    }

    // Method to handle both DTH and Mobile recharge
    public function doRecharge($operatorId, $amount, $phone,$referenceid)
    {
    
        // Prepare the payload with the necessary data
        $formData = [
            'operator' => $operatorId,
            'amount' => $amount,
            'canumber' => $phone,  // Can be either a phone number or DTH customer account number
            'referenceid' => $referenceid
        ];

        // Set the API endpoint for recharge
        $url = $this->url . "/api/v1/service/recharge/recharge/dorecharge";

        // Make the API request using the shared method from BaseService
        $response = $this->makeRequest($url, $formData);

        // Return the response from the API
        return $response;
    }
    // Method to check the status of a recharge
    public function rechargeStatus($referenceId)
    {
        // Prepare the payload with the reference ID
        $payload = [
            'referenceid' => $referenceId
        ];

        // Set the API endpoint
        $url = $this->url . "/api/v1/service/recharge/recharge/status";

        // Make the API request using the shared method from BaseService
        $response = $this->makeRequest($url, $payload);

        // Return the API response
        return $response;
    }
}
