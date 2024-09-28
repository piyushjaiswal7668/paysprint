<?php

namespace PiyushJaiswal\Services;

use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
class LICService extends BaseService
{
    // Method to fetch LIC bill
    public function fetchLicBill($canumber, $mode, $ad1 = null, $ad2 = null)
    {
        // Prepare the payload
        $payload = [
            'canumber' => $canumber,
            'mode' => $mode
        ];

        // Add optional fields if provided
        if ($ad1) {
            $payload['ad1'] = $ad1;
        }
        if ($ad2) {
            $payload['ad2'] = $ad2;
        }

        // Set the API endpoint for fetching LIC bill
        $url = $this->url . "/api/v1/service/bill-payment/bill/fetchlicbill";

        // Make the API request using the BaseService method
        $response = $this->makeRequest($url, $payload);

        // Return the API response
        return $response;
    }

    // Method to pay LIC bill
    public function payLicBill($payload)
    {
        // Define validation rules
    $rules = [
        'canumber' => 'required|string|max:15',  // Can be alphanumeric (vehicle number)
        'amount' => 'required|numeric|min:1',    // The amount must be a positive number
        'operator' => 'required|integer',        // Operator ID must be an integer
        'latitude' => 'required|numeric',        // Latitude should be numeric
        'longitude' => 'required|numeric',       // Longitude should be numeric
        'referenceid' => 'required|string',       // Can be alphanumeric
        'ad1' => 'required|email',       // Can be alphanumeric
        'ad2' => 'required|string',       // Can be alphanumeric

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
        'canumber.required' => 'Policy Number is required.',
        'amount.required' => 'Amount is required.',
        'operator.required' => 'Operator ID is required.',
        'latitude.required' => 'Latitude is required.',
        'longitude.required' => 'Longitude is required.',
        'referenceid.required' => 'ReferenceId is required.',
        'bill_fetch.required' => 'Bill fetch details are required.',
        'ad1.required' => 'Email is required.',
        'ad2.required' => 'Date of birth is required.',
    ];

    // Validate the payload
    $validator = Validator::make($payload, $rules, $messages);

    // If validation fails, throw an exception
    if ($validator->fails()) {
        throw new ValidationException($validator);
    }
        // Set the API endpoint for paying the LIC bill
        $url = $this->url . "/api/v1/service/bill-payment/bill/paylicbill";

        // Make the API request using the BaseService method
        $response = $this->makeRequest($url, $payload);

        // Return the API response
        return $response;
    }

    // Method to check LIC bill payment status
    public function licBillStatus($referenceId)
    {
        // Prepare the payload
        $payload = [
            'referenceid' => $referenceId
        ];

        // Set the API endpoint for checking the LIC bill status
        $url = $this->url . "/api/v1/service/bill-payment/bill/licstatus";

        // Make the API request using the BaseService method
        $response = $this->makeRequest($url, $payload);

        // Return the API response
        return $response;
    }
}
