<?php

namespace Demonyga\EdenAiSdk;

use Demonyga\EdenAiSdk\Exceptions\EdenAIException;

/**
 * Class EdenAIRequest.
 *
 * Builds EdenAI API Request Entity.
 */
final class EdenAIRequest
{
    /** @var string|null The bot access token to use for this request. */
    private ?string $accessToken;

    private ?string $method;

    private ?string $endpoint;

    /** @var array The headers to send with this request. */
    private array $headers = [];

    /** @var array The parameters to send with this request. */
    private array $params = [];

    /** @var int Timeout of the request in seconds. */
    private int $timeOut;

    /** @var int Connection timeout of the request in seconds. */
    private int $connectTimeOut;

    /**
     * Creates a new Request entity.
     *
     * @param string|null $accessToken
     * @param string|null $method
     * @param string|null $endpoint
     * @param array $params
     */
    public function __construct(
        ?string $accessToken = null,
        ?string $method = null,
        ?string $endpoint = null,
        array $params = [],
    )
    {
        $this->setAccessToken($accessToken);
        $this->setMethod($method);
        $this->setEndpoint($endpoint);
        $this->setParams($params);
    }

    /**
     * Validate that bot access token exists for this request.
     *
     * @throws EdenAIException
     */
    public function validateAccessToken(): void
    {
        if ($this->accessToken === null) {
            throw new EdenAIException('You must provide your bot access token to make any API requests.');
        }
    }

    /**
     * Return the bot access token for this request.
     */
    public function getAccessToken(): ?string
    {
        return $this->accessToken;
    }

    /**
     * Set the bot access token for this request.
     */
    public function setAccessToken(?string $accessToken): self
    {
        $this->accessToken = $accessToken;

        return $this;
    }

    /**
     * Validate that the HTTP method is set.
     *
     * @throws EdenAIException
     */
    public function validateMethod(): void
    {
        if ($this->method === '' || $this->method === '0') {
            throw new EdenAIException('HTTP method not specified.');
        }

        if (!in_array($this->method, ['GET', 'POST'])) {
            throw new EdenAIException('Invalid HTTP method specified. Must be GET or POST');
        }
    }

    /**
     * Return the API Endpoint for this request.
     */
    public function getEndpoint(): string
    {
        return $this->endpoint;
    }

    /**
     * Set the endpoint for this request.
     */
    public function setEndpoint(?string $endpoint): self
    {
        $this->endpoint = $endpoint;

        return $this;
    }

    /**
     * Return the headers for this request.
     */
    public function getHeaders(): array
    {
        $headers = $this->getDefaultHeaders();

        return array_merge($this->headers, $headers);
    }

    /**
     * Set the headers for this request.
     */
    public function setHeaders(array $headers): self
    {
        $this->headers = array_merge($this->headers, $headers);

        return $this;
    }

    /**
     * The default headers used with every request.
     */
    public function getDefaultHeaders(): array
    {
        return [
            'User-Agent' => 'Eden AI PHP SDK (https://github.com/demonyka/eden-ai-sdk)',
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer ' . $this->getAccessToken(),
        ];
    }

    /**
     * Only return params on POST requests.
     */
    public function getPostParams(): array
    {
        if ($this->method === 'POST') {
            return $this->params;
        }

        return [];
    }

    /**
     * Return the HTTP method for this request.
     */
    public function getMethod(): string
    {
        return $this->method;
    }

    /**
     * Set the HTTP method for this request.
     */
    public function setMethod(?string $method): self
    {
        $this->method = strtoupper($method);

        return $this;
    }

    /**
     * Return the params for this request.
     */
    public function getParams(): array
    {
        return $this->params;
    }

    /**
     * Set the params for this request.
     */
    public function setParams(array $params = []): self
    {
        $this->params = array_merge($this->params, $params);

        return $this;
    }

    /**
     * Get Timeout.
     */
    public function getTimeOut(): int
    {
        return $this->timeOut;
    }

    /**
     * Set Timeout.
     */
    public function setTimeOut(int $timeOut): self
    {
        $this->timeOut = $timeOut;

        return $this;
    }

    /**
     * Get Connection Timeout.
     */
    public function getConnectTimeOut(): int
    {
        return $this->connectTimeOut;
    }

    /**
     * Set Connection Timeout.
     */
    public function setConnectTimeOut(int $connectTimeOut): self
    {
        $this->connectTimeOut = $connectTimeOut;

        return $this;
    }
}