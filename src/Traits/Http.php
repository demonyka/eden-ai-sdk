<?php

namespace EdenAI\Traits;

use EdenAI\Exceptions\EdenAIException;
use EdenAI\HttpClients\HttpClientInterface;
use EdenAI\EdenAIClient;
use EdenAI\EdenAIRequest;
use EdenAI\EdenAIResponse;
use JsonException;

/**
 * Http.
 */
trait Http
{
    /** @var string API Access Token. */
    protected string $accessToken;

    /** @var EdenAIClient|null The client service. */
    protected ?EdenAIClient $client = null;

    /** @var HttpClientInterface|null Http Client Handler */
    protected ?HttpClientInterface $httpClientHandler = null;

    /** @var string|null Base Bot Url */
    protected ?string $baseUrl = null;

    /** @var int Timeout of the request in seconds. */
    protected int $timeOut = 60;

    /** @var int Connection timeout of the request in seconds. */
    protected int $connectTimeOut = 10;

    /** @var EdenAIResponse|null Stores the last request made to API. */
    protected ?EdenAIResponse $lastResponse = null;

    public function setHttpClientHandler(HttpClientInterface $httpClientHandler): self
    {
        $this->httpClientHandler = $httpClientHandler;

        return $this;
    }

    public function setBaseUrl(string $baseUrl): self
    {
        $baseUrl = str_replace('/bot', '', $baseUrl);
        $this->baseUrl = rtrim($baseUrl, '/');

        return $this;
    }

    /**
     * Returns the last response returned from API request.
     */
    public function getLastResponse(): ?EdenAIResponse
    {
        return $this->lastResponse;
    }

    /**
     * Sends a GET request to API and returns the result.
     *
     * @throws EdenAIException|JsonException
     */
    protected function get(string $endpoint, array $params = []): EdenAIResponse
    {
        $params = $this->replyMarkupToString($params);

        return $this->sendRequest('GET', $endpoint, $params);
    }

    /**
     * Converts a reply_markup field in the $params to a string.
     */
    protected function replyMarkupToString(array $params): array
    {
        if (isset($params['reply_markup'])) {
            $params['reply_markup'] = (string) $params['reply_markup'];
        }

        return $params;
    }

    /**
     * Sends a request to API and returns the result.
     *
     * @throws EdenAIException
     * @throws JsonException
     */
    protected function sendRequest(string $method, string $endpoint, array $params = []): EdenAIResponse
    {
        $request = $this->resolveRequest($method, $endpoint, $params);

        return $this->lastResponse = $this->getClient()->sendRequest($request);
    }

    /**
     * Instantiates a new EdenAIRequest entity.
     */
    protected function resolveRequest(string $method, string $endpoint, array $params = []): EdenAIRequest
    {
        return (new EdenAIRequest(
            $this->getAccessToken(),
            $method,
            $endpoint,
            $params
        ))
            ->setTimeOut($this->getTimeOut())
            ->setConnectTimeOut($this->getConnectTimeOut());
    }

    /**
     * Returns EdenAI API Access Token.
     */
    public function getAccessToken(): string
    {
        return $this->accessToken;
    }

    /**
     * Sets the bot access token to use with API requests.
     *
     * @param  string  $accessToken  The bot access token to save.
     */
    public function setAccessToken(string $accessToken): self
    {
        $this->accessToken = $accessToken;

        return $this;
    }

    public function getTimeOut(): int
    {
        return $this->timeOut;
    }

    public function setTimeOut(int $timeOut): self
    {
        $this->timeOut = $timeOut;

        return $this;
    }

    public function getConnectTimeOut(): int
    {
        return $this->connectTimeOut;
    }

    public function setConnectTimeOut(int $connectTimeOut): self
    {
        $this->connectTimeOut = $connectTimeOut;

        return $this;
    }

    /**
     * Returns the EdenAIClient service.
     */
    public function getClient(): EdenAIClient
    {
        if ($this->client === null) {
            $this->client = new EdenAIClient($this->httpClientHandler, $this->baseUrl);
        }

        return $this->client;
    }

    /**
     * Sends a POST request to API and returns the result.
     *
     * @throws EdenAIException|JsonException
     */
    public function post(string $endpoint, array $params = []): EdenAIResponse
    {
        $params = $this->normalizeParams($params);

        return $this->sendRequest('POST', $endpoint, $params);
    }

    private function normalizeParams(array $params): array
    {
        return ['json' => $this->replyMarkupToString($params)];
    }
}