<?php

namespace Demonyga\EdenAiSdk\Exceptions;

use Demonyga\EdenAiSdk\EdenAIResponse;
use Exception;

/**
 * Class TelegramSDKException.
 */
class EdenAIException extends Exception
{
    /**
     * Thrown when token is not provided.
     */
    public static function tokenNotProvided(string $tokenEnvName): self
    {
        return new static('Required "token" not supplied in config and could not find fallback environment variable '.$tokenEnvName);
    }

    public static function create(EdenAIResponse $response): self
    {
        $data = $response->getDecodedBody();

        $code = null;
        $message = null;
        if (isset($data['ok'], $data['error_code']) && $data['ok'] === false) {
            $code = $data['error_code'];
            $message = $data['description'] ?? 'Unknown error from API.';
        }

        // Others
        return new self($message, $code);
    }
}