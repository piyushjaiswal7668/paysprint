<?php

namespace PiyushJaiswal\Services;

use Illuminate\Support\Facades\Http;

class CashBalanceService extends BaseService
{
    /**
     * Check the cash balance via the Paysprint API.
     *
     * @return array
     */
    public function checkCashBalance()
    {
         // Define the API endpoint for IP check
         $url = $this->url . '/api/v1/service/balance/balance/cashbalance';

         $payload = [];
         // Make the API request using the BaseService method
         $response = $this->makeRequest($url, $payload);
 
         // Return the API response
         return $response;
    }
}
