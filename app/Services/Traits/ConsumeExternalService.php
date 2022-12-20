<?php

namespace App\Services\Traits;

use GuzzleHttp\Promise\PromiseInterface;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;

trait ConsumeExternalService
{
    private $key;
    private $host;

    public function __construct()
    {
        $this->key  = config('services.micro_02.key');
        $this->host = config('services.micro_02.host');
    }

    /**
     * @param array $headers
     *
     * @return \Illuminate\Http\Client\PendingRequest
     */
    public function http(array $headers = []): PendingRequest
    {
        array_push($headers, [
            'Accept' => 'application/json',
            'Authorization' => $this->key
        ]);

        return Http::withHeaders($headers);
    }

    /**
     * @param string $method
     * @param string $endpoint
     * @param array  $params
     * @param array  $headers
     *
     * @return \Illuminate\Http\Client\Response|\GuzzleHttp\Promise\PromiseInterface
     */
    public function request(string $method, string $endpoint, array $params = [], array $headers = []): Response|PromiseInterface
    {
        return $this->http($headers)
                    ->$method($this->host . $endpoint, $params);
    }
}
