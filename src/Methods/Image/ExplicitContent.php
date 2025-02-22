<?php

namespace EdenAI\Methods\Image;

use EdenAI\Exceptions\EdenAIException;
use EdenAI\Objects\ExplicitContent\ExplicitContent as ExplicitContentObject;
use EdenAI\Traits\Http;
use Illuminate\Support\Facades\Config;
use JsonException;

/**
 * Class ExplicitContent.
 *
 * @mixin Http
 */
trait ExplicitContent
{
    /**
     * @throws EdenAIException|JsonException
     */
    public function checkExplicitContent(array $params): ExplicitContentObject
    {
        if (!isset($params["file_url"])) {
            throw new EdenAIException("Missing required parameter 'file_url'");
        }
        if (!isset($params["providers"])) {
            $params["providers"] = Config::get('edenai.exclipt_content.providers', 'clarifai,google');
        }

        return new ExplicitContentObject($this->post('image/explicit_content', $params)->getDecodedBody());
    }
}