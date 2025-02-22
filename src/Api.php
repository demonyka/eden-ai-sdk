<?php

namespace EdenAI;

use BadMethodCallException;
use EdenAI\Methods\Image\DeepfakeDetection;
use EdenAI\Methods\Image\ObjectDetection;
use Illuminate\Support\Traits\Macroable;
use EdenAI\Exceptions\EdenAIException;
use EdenAI\HttpClients\HttpClientInterface;
use EdenAI\Traits\Http;
use EdenAI\Methods\Image\ExplicitContent;
class Api
{
    use ExplicitContent;
    use DeepfakeDetection;
    use ObjectDetection;
    use Http;
    use Macroable {
        Macroable::__call as macroCall;
    }

    /** @var string The name of the environment variable that contains the Access Token. */
    public const TOKEN_ENV_NAME = 'EDEN_AI_TOKEN';

    /**
     * Instantiates a new super-class object.
     *
     * @param string|null $token The Access Token.
     * @param HttpClientInterface|null $httpClientHandler (Optional) Custom HTTP Client Handler.
     * @param string|null $baseUrl (Optional) Custom base bot url.
     * @throws EdenAIException
     */
    public function __construct(?string $token = null, ?HttpClientInterface $httpClientHandler = null, ?string $baseUrl = null)
    {
        $this->setAccessToken($token ?? getenv(self::TOKEN_ENV_NAME));
        $this->validateAccessToken();

        $this->httpClientHandler = $httpClientHandler;

        $this->baseUrl = $baseUrl;
    }

    /**
     * @throws EdenAIException
     */
    private function validateAccessToken(): void
    {
        if ($this->getAccessToken() === '' || $this->getAccessToken() === '0') {
            throw EdenAIException::tokenNotProvided(self::TOKEN_ENV_NAME);
        }
    }

    /**
     * Magic method to process any dynamic method calls.
     *
     * @return mixed
     */
    public function __call($method, $parameters)
    {
        if (self::hasMacro($method)) {
            return $this->macroCall($method, $parameters);
        }

        if (method_exists($this, $method)) {
            return $this->$method(...$parameters);
        }

        throw new BadMethodCallException(sprintf('Method [%s] does not exist.', $method));
    }
}