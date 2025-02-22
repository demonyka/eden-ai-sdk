<?php

namespace Demonyga\EdenAiSdk\Laravel\Facades;

use Demonyga\EdenAiSdk\Technologies\Image\ExplicitContent;
use Illuminate\Support\Facades\Facade;

/**
 * @method static ExplicitContent explicitContent()
 */
class EdenAI extends Facade
{
    /**
     * Получение фасадного разрешения на доступ к методу.
     *
     * @return string
     */
    protected static function getFacadeAccessor(): string
    {
        return 'edenai';
    }
}