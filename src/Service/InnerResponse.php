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

        $token = $this->getJwtToken($hostName);


        $urlAddress = sprintf("http://%s/api/album/all", $hostName);
        $response = $this->client->request(
            'GET',
            $urlAddress,

            [
                "auth_bearer" => $token["token"],
                'headers' => [
                    'Accept' => 'application/json',
                ],
            ]
        );

        return $response->getContent();
    }

    public function getJwtToken($hostName)
    {


        $urlAddress = sprintf("http://%s/api/login_check", $hostName);

        return $this->client->request(
            'POST',
            $urlAddress,
            [
                'body' => ["username" => "test@test.pl", "password" => "1234"],
                'headers' => [
                    'Accept' => 'application/json',
                ],
            ]
        );
    }
}
