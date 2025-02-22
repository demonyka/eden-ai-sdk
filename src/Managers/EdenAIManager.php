<?php

namespace EdenAI\Managers;

use EdenAI\Api;
use EdenAI\Exceptions\EdenAIException;

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
