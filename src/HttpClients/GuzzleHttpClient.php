<?php

namespace Demonyga\EdenAiSdk\HttpClients;

use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Promise\PromiseInterface;
use GuzzleHttp\Promise\Utils;
use GuzzleHttp\RequestOptions;
use Psr\Http\Client\RequestExceptionInterface;
use Psr\Http\Message\ResponseInterface;
use Demonyga\EdenAiSdk\Exceptions\EdenAIException;
use Throwable;

/**
 * Class GuzzleHttpClient.
 */
class GuzzleHttpClient implements HttpClientInterface
{
    /** @var PromiseInterface[] Holds promises. */
    protected static array $promises = [];

    /** @var Client|ClientInterface HTTP client. */
    protected ClientInterface|Client $client;

    /** @var int Timeout of the request in seconds. */
    protected int $timeOut = 30;

    /** @var int Connection timeout of the request in seconds. */
    protected int $connectTimeOut = 10;

    /**
     * GuzzleHttpClient constructor.
     */
    public function __construct(?ClientInterface $client = null)
    {
        $this->client = $client ?? new Client;
    }

    /**
     * Unwrap Promises.
     *
     * @throws Throwable
     */
    public function __destruct()
    {
        Utils::unwrap(self::$promises);
    }

    /**
     * Sets HTTP client.
     */
    public function setClient(ClientInterface $client): self
    {
        $this->client = $client;

        return $this;
    }

    /**
     * {@inheritdoc}
     *
     * @throws EdenAIException
     */
    public function send(
        string $url,
        string $method,
        array $headers = [],
        array $options = [],
    ): ResponseInterface|PromiseInterface|null {
        $body = $options['body'] ?? null;
        $options = $this->getOptions($headers, $body, $options);

        try {
            $response = $this->client->request($method, $url, $options);
        } catch (GuzzleException $guzzleException) {
            $response = null;
            if ($guzzleException instanceof RequestExceptionInterface) {
                $response = $guzzleException->getResponse();
            }

            if (! $response instanceof ResponseInterface) {
                throw new EdenAIException($guzzleException->getMessage(), $guzzleException->getCode(), $guzzleException);
            }
        }

        return $response;
    }

    /**
     * Prepares and returns request options.
     */
    private function getOptions(
        array $headers,
        mixed $body,
        array $options,
    ): array {
        $default_options = [
            RequestOptions::HEADERS => $headers,
            RequestOptions::BODY => $body,
            RequestOptions::TIMEOUT => $this->timeOut,
            RequestOptions::CONNECT_TIMEOUT => $this->connectTimeOut,
        ];

        return array_merge($default_options, $options);
    }

    /**
     * {@inheritdoc}
     */
    public function getTimeOut(): int
    {
        return $this->timeOut;
    }

    /**
     * {@inheritdoc}
     */
    public function setTimeOut(int $timeOut): static
    {
        $this->timeOut = $timeOut;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getConnectTimeOut(): int
    {
        return $this->connectTimeOut;
    }

    /**
     * {@inheritdoc}
     */
    public function setConnectTimeOut(int $connectTimeOut): static
    {
        $this->connectTimeOut = $connectTimeOut;

        return $this;
    }
}