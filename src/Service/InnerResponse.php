<?php

namespace App\Service;

use Symfony\Contracts\HttpClient\HttpClientInterface;

class InnerResponse
{
    private $client;

    public function __construct(HttpClientInterface $client)
    {
        $this->client = $client;
    }

    public function fetchAllAlbums(): string
    {

        $hostName = $_SERVER["HOSTNAME"];
        
        $urlAddress = sprintf("http://%s/api/album/all", $hostName);

        $response = $this->client->request(
            'GET',
            $urlAddress,
            [
                'headers' => [
                    'Accept' => 'application/json',
                ],
            ]
        );

        return $response->getContent();
    }
}
