<?php

namespace PiyushJaiswal\Services;

use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class MunicipalityService extends BaseService
{
    // Method to get municipality operators
    public function getOperators()
    {
        // Set the API endpoint for getting municipality operators
        $url = $this->url . "/api/v1/service/bill-payment/municipality/getoperator";

        // Make the API request using the BaseService method
        $response = $this->makeRequest($url, []);

        return $response;
    }

    // Method to fetch municipality bill
    public function fetchMunicipalityBill($operator, $canumber, $ad1 = null, $ad2 = null, $ad3 = null)
    {
        // Prepare the payload
        $payload = [
            'operator' => $operator,
            'canumber' => $canumber
        ];

        // Add optional fields if provided
        if ($ad1) {
            $payload['ad1'] = $ad1;
        }
        if ($ad2) {
            $payload['ad2'] = $ad2;
        }
        if ($ad3) {
            $payload['ad3'] = $ad3;
        }

        // Set the API endpoint for fetching the municipality bill
        $url = $this->url . "/api/v1/service/bill-payment/municipality/fetchbill";

        // Make the API request using the BaseService method
        $response = $this->makeRequest($url, $payload);

        return $response;
    }

    // Method to pay municipality bill
    public function payMunicipalityBill($payload)
    {
        $rules = [
            'canumber' => 'required|string|max:15',  // Can be alphanumeric (House number)
            'amount' => 'required|numeric|min:1',    // The amount must be a positive number
            'operator' => 'required|integer',        // Operator ID must be an integer
            'latitude' => 'required|numeric',        // Latitude should be numeric
            'longitude' => 'required|numeric',       // Longitude should be numeric
            'referenceid' => 'required|string',       // Can be alphanumeric
            // Validation rules for the bill_fetch object
            'bill_fetch' => 'required|array',        // The bill_fetch field must be an array
            'bill_fetch.billAmount' => 'required|numeric',  // Bill amount must be numeric
            'bill_fetch.billnetamount' => 'required|numeric',  // Net bill amount must be numeric
            'bill_fetch.billdate' => 'required|date',  // Bill date must be a valid date
            'bill_fetch.dueDate' => 'required|date',   // Due date must be a valid date
            'bill_fetch.acceptPayment' => 'required|boolean',  // Must be a boolean
            'bill_fetch.acceptPartPay' => 'required|boolean',  // Must be a boolean
            'bill_fetch.cellNumber' => 'required|string|max:15',  // Cell number can be alphanumeric (vehicle number)
            'bill_fetch.userName' => 'required|string|max:255',   // User name must be a string
        ];
    
        // Define custom validation messages if necessary
        $messages = [
            'canumber.required' => 'House Number is required.',
            'amount.required' => 'Amount is required.',
            'operator.required' => 'Operator ID is required.',
            'latitude.required' => 'Latitude is required.',
            'longitude.required' => 'Longitude is required.',
            'referenceid.required' => 'ReferenceId is required.',
            'bill_fetch.required' => 'Bill fetch details are required.',
        ];
    
        // Validate the payload
        $validator = Validator::make($payload, $rules, $messages);
    
        // If validation fails, throw an exception
        if ($validator->fails()) {
            throw new ValidationException($validator);
        }
       
        // Set the API endpoint for paying the municipality bill
        $url = $this->url . "/api/v1/service/bill-payment/municipality/paybill";

        // Make the API request using the BaseService method
        $response = $this->makeRequest($url, $payload);

        // Add the generated reference ID to the response
        $response['referenceid'] = $payload['referenceid'];

        return $response;
    }

    // Method to check municipality bill payment status
    public function checkMunicipalityStatus($referenceId)
    {
        // Prepare the payload
        $payload = [
            'referenceid' => $referenceId
        ];

        // Set the API endpoint for checking municipality status
        $url = $this->url . "/api/v1/service/bill-payment/municipality/status";

        // Make the API request using the BaseService method
        $response = $this->makeRequest($url, $payload);

        return $response;
    }
}
