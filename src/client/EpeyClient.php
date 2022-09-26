<?php

require_once dirname(__FILE__) . '/../service/ResponseService.php';

class EpeyClient
{
    private $client;
    private $responseService;

    public function __construct()
    {
        $this->responseService = new ResponseService();

        $this->client = curl_init();

        $options = [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_TIMEOUT => 900,
            CURLOPT_HEADER => [
                'Authorization: ' . getenv('EPEY_AUTH_TOKEN'),
                'Content-Type: application/xml',
                'Accept: application/xml'
            ]
        ];

        curl_setopt_array($this->client, $options);
    }

    public function SyncProductList($xml)
    {
        curl_setopt($this->client, CURLOPT_POSTFIELDS, 'xml=' . $xml);
        curl_exec($this->client);

        if (curl_errno($this->client)) {
            return $this->responseService->Send(422, false, 'EPEY_CLIENT_FAILED', null);
        }

        return $this->responseService->Send(201, true, 'EPEY_CLIENT_SUCCESS', null);
    }
}