<?php

namespace EdenAI\Laravel\Facades;

use EdenAi\Api;
use EdenAI\Objects\DeepfakeObject;
use EdenAI\Objects\DetectedObject;
use EdenAI\Objects\ExplicitContent as ExplicitContentObject;
use EdenAI\Objects\GeneratedCode;
use EdenAI\Objects\ModeratedText;
use Illuminate\Support\Facades\Facade;

/**
 * @see Api
 * @method static ExplicitContentObject checkExplicitContent(array $params)
 * @method static DeepfakeObject detectDeepfake(array $params)
 * @method static DetectedObject detectObject(array $params)
 * @method static ModeratedText moderateText(array $params)
 * @method static GeneratedCode generateCode(array $params)
 */
class EdenAI extends Facade
{
    /**
     * Gets the facade accessor to access the method.
     * @return string
     */
    protected static function getFacadeAccessor(): string
    {
        return 'edenai';
    }
}