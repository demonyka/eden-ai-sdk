<?php

namespace EdenAI\Methods\Text;

use EdenAI\Exceptions\EdenAIException;
use EdenAI\Objects\DetectedObject;
use EdenAI\Objects\ModeratedText;
use EdenAI\Traits\Http;
use Illuminate\Support\Facades\Config;
use JsonException;

/**
 * Class ExplicitContent.
 *
 * @mixin Http
 */
trait TextModeration
{
    /**
     * @throws EdenAIException|JsonException
     */
    public function moderateText(array $params): ModeratedText
    {
        if (!isset($params["text"])) {
            throw new EdenAIException("Missing required parameter 'text'");
        }
        if (!isset($params['language'])) {
            $params['language'] = Config::get('edenai.moderate_text.fallbackLanguage', 'en');
        }
        if (!isset($params["providers"])) {
            $params["providers"] = Config::get('edenai.object_detection.providers', 'google,amazon');
        }

        return new ModeratedText($this->post('text/moderation', $params)->getDecodedBody());
    }
}