<?php

namespace Demonyga\EdenAiSdk\Managers;

use Demonyga\EdenAiSdk\Api;
use Demonyga\EdenAiSdk\Exceptions\EdenAIException;

class EdenAIManager
{
    /**
     * @throws EdenAIException
     */
    public function __call(string $method, array $parameters)
    {
        $token = data_get('edenai', 'token');

        $api = new Api($token);
        return $api->$method(...$parameters);
    }
}
