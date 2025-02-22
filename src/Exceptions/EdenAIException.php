<?php

namespace Demonyga\EdenAiSdk\Exceptions;

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
}