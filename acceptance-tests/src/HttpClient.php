<?php

namespace Fefas\BeRinha2023\AcceptanceTests;

use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\RequestOptions as GuzzleRequestOptions;
use Psr\Http\Message\ResponseInterface as PsrResponse;

final class HttpClient
{
    private readonly GuzzleClient $client;
    private ?PsrResponse $lastResponse = null;

    public function __construct()
    {
        $this->client = new GuzzleClient([
            'base_uri' => $_ENV['APP_URL'],
            GuzzleRequestOptions::HTTP_ERRORS => false,
        ]);
    }

    public function postPerson(array $data): string
    {
        var_dump($data);
        $this->lastResponse = $this->client->post("/pessoas", [
            GuzzleRequestOptions::JSON => $data,
        ]);

        return 'replace-me-with-returned-uuid';
    }

    public function getPerson(string $id): void
    {
        $this->lastResponse = $this->client->get("/pessoas/$id");
    }

    public function lastResponseStatusCode(): int
    {
        return $this->lastResponse->getStatusCode();
    }
}
