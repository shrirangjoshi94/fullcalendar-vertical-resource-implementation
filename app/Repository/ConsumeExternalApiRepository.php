<?php

namespace App\Repository;

use GuzzleHttp\Client;

class ConsumeExternalApiRepository extends Repository
{

    /**
     * Function to call 3rd party API
     * @param  string
     * @param  string
     * @param  array
     * @return object
     */
    public function callExternalApi(string  $method, string $url, array  $inputParams)
    {
        $client = new Client();

        $response = $client->request($method, $url, [
            'verify' => false ,   //to disable ssl certificates
            'json' => $inputParams
        ]);

        $response = $response->getBody()->getContents();

        return json_decode($response);
    }
}