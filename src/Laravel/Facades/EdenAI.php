<?php

namespace Demonyga\EdenAiSdk\Laravel\Facades;

use Illuminate\Support\Facades\Facade;

/**
 *
 * @method static mixed checkExplicitContent(array $params)
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