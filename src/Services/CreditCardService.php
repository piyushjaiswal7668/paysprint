<?php
namespace PiyushJaiswal\Services;

use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class CreditCardService extends BaseService
{
    public function generateOTP($name, $card_number, $remarks, $network, $mobile, $amount)
    {
        
         // Custom validation messages
         $messages = [
            'name.required' => 'The name field is required.',
            'card_number.required' => 'Please provide the credit card number.',
            'remarks.required' => 'Please enter your remarks.',
            'network.required' => 'The card network (e.g., Visa, Mastercard) is required.',
            'mobile.required' => 'The mobile number is required.',
            'mobile.integer' => 'The mobile number must be a valid integer.',
            'amount.required' => 'The amount field is required.',
            'amount.integer' => 'The amount must be an integer.',
            'amount.lte' => 'The amount must be less than or equal to 49,999.',
        ];

        // Form validation
        $validator = Validator::make([
            'name' => $name,
            'card_number' => $card_number,
            'remarks' => $remarks,
            'network' => $network,
            'mobile' => $mobile,
            'amount' => $amount
        ], [
            'name' => 'required|string',
            'card_number' => 'required|string',
            'remarks' => 'required|string',
            'network' => 'required|string',
            'mobile' => 'required|integer',
            'amount' => 'required|integer|lte:49999',
        ], $messages); // Passing the custom messages here


        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        // Generate a unique reference ID
        $refid = substr(uniqid(), 0, 20);

        // Prepare the payload for the API
        $payload = [
            'refid' => $refid,
            'name' => $name,
            'mobile' => $mobile,
            'card_number' => $card_number,
            'amount' => $amount,
            'remarks' => $remarks,
            'network' => $network,
        ];

        $url = $this->url . "/api/v1/service/cc-payment/ccpayment/generateotp";

        // Make the API request using the BaseService method
        $response = $this->makeRequest($url, $payload);
       
        // Return the API response
        return $response;
    }

    public function payBill($refid, $name, $mobile, $card_number, $amount, $remarks, $network, $otp)
    {
        // Custom validation messages
        $messages = [
            'refid.required' => 'The reference ID is required.',
            'name.required' => 'The name field is required.',
            'mobile.required' => 'The mobile number is required.',
            'mobile.integer' => 'The mobile number must be a valid integer.',
            'card_number.required' => 'Please provide the credit card number.',
            'amount.required' => 'The amount field is required.',
            'amount.integer' => 'The amount must be an integer.',
            'remarks.required' => 'Please provide the payment remarks.',
            'network.required' => 'The card network is required (e.g., Visa, Mastercard).',
            'otp.required' => 'The OTP is required to complete the payment.',
        ];

        // Validate the input
        $validator = Validator::make([
            'refid' => $refid,
            'name' => $name,
            'mobile' => $mobile,
            'card_number' => $card_number,
            'amount' => $amount,
            'remarks' => $remarks,
            'network' => $network,
            'otp' => $otp,
        ], [
            'refid' => 'required|string',
            'name' => 'required|string',
            'mobile' => 'required|integer',
            'card_number' => 'required|string',
            'amount' => 'required|integer',
            'remarks' => 'required|string',
            'network' => 'required|string',
            'otp' => 'required|string',
        ], $messages);

        // Check for validation errors
        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        // Prepare the data for the API
        $payload = [
            'refid' => $refid,
            'name' => $name,
            'mobile' => $mobile,
            'card_number' => $card_number,
            'amount' => $amount,
            'remarks' => $remarks,
            'network' => $network,
            'otp' => $otp,
        ];

        $url = $this->url . "/api/v1/service/cc-payment/ccpayment/paybill";

        // Make the API request using the BaseService method
        $response = $this->makeRequest($url, $payload);
       
        // Return the API response
        return $response;
        // JWT and Authorization
      
    }

      // Method to check Fastag bill payment status
      public function checkCreditCardStatus($referenceId)
      {
          // Prepare the payload
          $payload = [
              'referenceid' => $referenceId
          ];
  
          // Set the API endpoint for checking Fastag status
          $url = $this->url . "/api/v1/service/cc-payment/ccpayment/status";
          // Make the API request using the BaseService method
          $response = $this->makeRequest($url, $payload);
  
          // Return the API response
          return $response;
      }

      public function resendOTP($refid, $ackno)
      {
          // Validation
          $validator = Validator::make([
              'refid' => $refid,
              'ackno' => $ackno,
          ], [
              'refid' => 'required|string',
              'ackno' => 'required|string',
          ]);
  
          if ($validator->fails()) {
            throw new ValidationException($validator);
        }
          // Prepare form data
          $payload = [
              'refid' => $refid,
              'ackno' => $ackno,
          ];
  
          $url = $this->url . "/api/v1/service/cc-payment/ccpayment/resendotp";
        
          // Make the API request using the BaseService method
          $response = $this->makeRequest($url, $payload);
  
          // Return the API response
          return $response;
        
      }
  
      // Method to claim a refund
      public function claimRefund($refid, $ackno, $otp)
      {
          // Validation
          $validator = Validator::make([
              'refid' => $refid,
              'ackno' => $ackno,
              'otp' => $otp,
          ], [
              'refid' => 'required|string',
              'ackno' => 'required|string',
              'otp' => 'required|string',
          ]);
  
          if ($validator->fails()) {
            throw new ValidationException($validator);
        }
  
          // Prepare form data
          $payload = [
              'refid' => $refid,
              'ackno' => $ackno,
              'otp' => $otp,
          ];
  
          $url = $this->url . "/api/v1/service/cc-payment/ccpayment/claimrefund";
        
          // Make the API request using the BaseService method
          $response = $this->makeRequest($url, $payload);
  
          // Return the API response
          return $response;
        
        
      }
}
