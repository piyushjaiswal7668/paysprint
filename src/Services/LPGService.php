<?php

namespace PiyushJaiswal\Services;

use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class LPGService extends BaseService
{
    // Method to get LPG operators
    public function getLPGOperators()
    {
        // Define the payload
        $payload = ['mode' => 'online/offline'];

        // Set the API endpoint for LPG operators
        $url = $this->url . "/api/v1/service/bill-payment/lpg/getoperator";

        // Make the API request using the BaseService method
        $response = $this->makeRequest($url, $payload);

        return $response;
    }

    // Method to fetch LPG bill
    public function fetchLPGBill($operator, $canumber, $ad1 = null, $ad2 = null, $ad3 = null)
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

        // Set the API endpoint for fetching the LPG bill
        $url = $this->url . "/api/v1/service/bill-payment/lpg/fetchbill";

        // Make the API request using the BaseService method
        $response = $this->makeRequest($url, $payload);

        // Convert amount to float if present
        if (isset($response['amount'])) {
            $response['amount'] = (float) $response['amount'];
        }

        return $response;
    }

    // Method to pay LPG bill
    public function payLPGBill($payload)
    {
        $rules = [
            'canumber' => 'required|string|max:15',  // Can be alphanumeric (vehicle number)
            'amount' => 'required|numeric|min:1',    // The amount must be a positive number
            'operator' => 'required|integer',        // Operator ID must be an integer
            'latitude' => 'required|numeric',        // Latitude should be numeric
            'longitude' => 'required|numeric',       // Longitude should be numeric
            'referenceid' => 'required|string',       // Can be alphanumeric
            'ad1' => 'nullable|numeric',         // ad1 is optional, but if present, must be numeric
            'ad2' => 'nullable|numeric',         // ad2 is optional, but if present, must be numeric
            'ad3' => 'nullable|numeric',         // ad3 is optional, but if present, must be numeric      // Can be alphanumeric
    
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
            'canumber.required' => 'Mobile Number is required.',
            'amount.required' => 'Amount is required.',
            'operator.required' => 'Operator ID is required.',
            'latitude.required' => 'Latitude is required.',
            'longitude.required' => 'Longitude is required.',
            'referenceid.required' => 'ReferenceId is required.',
            'bill_fetch.required' => 'Bill fetch details are required.',
            'ad1.numeric' => 'State is required.',
            'ad2.numeric' => 'District  is required.',
            'ad3.numeric' => 'Distributor is required.',
        ];
    
        // Validate the payload
        $validator = Validator::make($payload, $rules, $messages);
    
        // If validation fails, throw an exception
        if ($validator->fails()) {
            throw new ValidationException($validator);
        }
        // Set the API endpoint for paying the LPG bill
        $url = $this->url . "/api/v1/service/bill-payment/lpg/paybill";

        // Make the API request using the BaseService method
        $response = $this->makeRequest($url, $payload);

        // Add the generated reference ID to the response
        $response['referenceid'] = $payload['referenceid'];

        return $response;
    }

    // Method to check LPG bill status
    public function checkLPGStatus($referenceId)
    {
        // Prepare the payload
        $payload = ['referenceid' => $referenceId];

        // Set the API endpoint for checking LPG bill status
        $url = $this->url . "/api/v1/service/bill-payment/lpg/status";

        // Make the API request using the BaseService method
        $response = $this->makeRequest($url, $payload);

        return $response;
    }
}
