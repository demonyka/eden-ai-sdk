<?php

namespace EdenAI;

use EdenAI\Exceptions\EdenAIException;
use GuzzleHttp\Promise\PromiseInterface;
use JsonException;
use Psr\Http\Message\ResponseInterface;
use EdenAI\HttpClients\GuzzleHttpClient;
use EdenAI\HttpClients\HttpClientInterface;

final class EdenAIClient
{
    public const BASE_URL = 'https://api.EdenAI.run/v2';
    private HttpClientInterface $httpClientHandler;
    private string $baseUrl;

    public function __construct(?HttpClientInterface $httpClientHandler = null, ?string $baseBotUrl = null)
    {
        $this->httpClientHandler = $httpClientHandler ?? new GuzzleHttpClient;

        $this->baseUrl = $baseBotUrl ?? self::BASE_URL;
    }

    public function getHttpClientHandler(): HttpClientInterface
    {
        return $this->httpClientHandler ?? new GuzzleHttpClient;
    }

    public function setHttpClientHandler(HttpClientInterface $httpClientHandler): self
    {
        $this->httpClientHandler = $httpClientHandler;

        return $this;
    }

    /**
     * @throws EdenAIException
     * @throws JsonException
     */
    public function sendRequest(EdenAIRequest $request): EdenAIResponse
    {
        [$url, $method, $headers] = $this->prepareRequest($request);
        $options = $this->getOptions($request, $method);

        $rawResponse = $this->httpClientHandler
            ->setTimeOut($request->getTimeOut())
            ->setConnectTimeOut($request->getConnectTimeOut())
            ->send($url, $method, $headers, $options);

        $response = $this->getResponse($request, $rawResponse);

        if ($response->isError()) {
            throw $response->getThrownException();
        }

        return $response;
    }

    public function prepareRequest(EdenAIRequest $request): array
    {
        $url = $this->baseUrl.'/'.$request->getEndpoint();

        return [$url, $request->getMethod(), $request->getHeaders()];
    }

    public function getBaseUrl(): string
    {
        return $this->baseUrl;
    }

    /**
     * @throws JsonException
     */
    private function getResponse(EdenAIRequest $request, ResponseInterface|PromiseInterface|null $response): EdenAIResponse
    {
        return new EdenAIResponse($request, $response);
    }

    private function getOptions(EdenAIRequest $request, string $method): array
    {
        return $method === 'POST' ? $request->getPostParams() : ['query' => $request->getParams()];
    }
}