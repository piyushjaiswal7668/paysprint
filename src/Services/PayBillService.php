<?php

namespace PiyushJaiswal\Services;

class BillPaymentService extends BaseService
{
    // Method to get bill payment operators
    public function getBillPaymentOperators()
    {
        // Set the API endpoint for fetching bill operators
        $url = $this->url . "/api/v1/service/bill-payment/bill/getoperator";

        // No payload is needed for this request, so we pass an empty array
        $payload = [];

        // Make the API request using the BaseService method
        $response = $this->makeRequest($url, $payload);

        // Return the API response
        return $response;
    }

    // Method to fetch a bill
    public function fetchBill($operator, $canumber, $ad1 = null, $ad2 = null, $ad3 = null)
    {
        // Prepare the payload
        $payload = [
            'operator' => $operator,
            'canumber' => $canumber,
            'mode' => 'online',  // The mode is hardcoded to 'online' as per your logic
        ];

        // Add optional parameters if they are provided
        if ($ad1) {
            $payload['ad1'] = $ad1;
        }
        if ($ad2) {
            $payload['ad2'] = $ad2;
        }
        if ($ad3) {
            $payload['ad3'] = $ad3;
        }

        // Set the API endpoint for fetching the bill
        $url = $this->url . "/api/v1/service/bill-payment/bill/fetchbill";

        // Make the API request using the shared method from BaseService
        $response = $this->makeRequest($url, $payload);

        // Return the API response
        return $response;
    }

    public function payBill($payload)
    {
    // Method to pay a bill
    $rules = [
        'canumber' => 'required|string|max:15',  // Can be alphanumeric (Account Number)
        'amount' => 'required|numeric|min:1',    // The amount must be a positive number
        'operator' => 'required|integer',        // Operator ID must be an integer
        'latitude' => 'required|numeric',        // Latitude should be numeric
        'longitude' => 'required|numeric',       // Longitude should be numeric
        'referenceid' => 'required|string',      // Can be alphanumeric

        // Optional fields
        'ad1' => 'nullable',               // ad1 is optional, but if present, must be an email
        'ad2' => 'nullable',      // ad2 is optional, string with max 255 characters
        'ad3' => 'nullable',      // ad3 is optional, string with max 255 characters

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

    // Define custom validation messages
    $messages = [
        'canumber.required' => 'Account Number is required.',
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
       

        // Set the API endpoint for paying the bill
        $url = $this->url . "/api/v1/service/bill-payment/bill/paybill";

        // Make the API request using the BaseService method
        $response = $this->makeRequest($url, $payload);

        // Return the API response
        return $response;
    }

    public function billStatus($referenceId)
    {
        // Prepare the payload with the reference ID
        $payload = [
            'referenceid' => $referenceId
        ];

        // Set the API endpoint for fetching bill payment status
        $url = $this->url . "/api/v1/service/bill-payment/bill/status";

        // Make the API request using the shared method from BaseService
        $response = $this->makeRequest($url, $payload);

        // Rename 'response_code' to 'responsecode' in the response
        if (isset($response['response_code'])) {
            $response['responsecode'] = $response['response_code'];
            unset($response['response_code']); // Remove the old key
        }

        // Return the modified API response
        return $response;
    }
}
