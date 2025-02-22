<?php

namespace EdenAI\Laravel\Facades;

use EdenAI\Objects\DeepfakeObject;
use EdenAI\Objects\ExplicitContent as ExplicitContentObject;
use Illuminate\Support\Facades\Facade;

/**
 *
 * @method static ExplicitContentObject checkExplicitContent(array $params)
 * @method static DeepfakeObject checkDeepfake(array $params)
 */
class EdenAI extends Facade
{
    /**
     * Получение фасадного разрешения на доступ к методу.
     * @return string
     */
    protected static function getFacadeAccessor(): string
    {
        return 'edenai';
    }
}