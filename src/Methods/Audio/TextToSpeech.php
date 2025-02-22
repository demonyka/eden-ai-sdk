<?php

namespace EdenAI\Methods\Audio;

use EdenAI\Exceptions\EdenAIException;
use EdenAI\Objects\ModeratedText;
use EdenAI\Objects\SpeechesText;
use EdenAI\Traits\Http;
use Illuminate\Support\Facades\Config;
use JsonException;

/**
 * Class TextModeration.
 *
 * @mixin Http
 */
trait TextToSpeech
{
    /**
     * Moderate text
     *
     * <code>
     * $params = [
     *       'text'                        => '',  // string     - Required. Text for speech
     *       'language'                    => '',  // string     - Optional. Language (Default in config)
     *       'option'                      => '',  // string     - Optional. Gender voice (Default in config)
     *       'providers'                   => '',  // string     - (Optional). providers separated by commas (Default in Config)
     * ]
     * </code>
     *
     * @link https://app.edenai.run/bricks/text/moderation
     *
     * @throws EdenAIException|JsonException
     */
    public function textToSpeech(array $params): SpeechesText
    {
        if (!isset($params["text"])) {
            throw new EdenAIException("Missing required parameter 'text'");
        }
        if (!isset($params['language'])) {
            $params['language'] = Config::get('edenai.text_to_speech.fallbackLanguage', 'en');
        }
        if (!isset($params['option'])) {
            $params['option'] = Config::get('edenai.text_to_speech.option', 'MALE');
        }
        if (!isset($params["providers"])) {
            $params["providers"] = Config::get('edenai.text_to_speech.providers', 'openai');
        }

        return new SpeechesText($this->post('audio/text_to_speech', $params)->getDecodedBody());
    }
}