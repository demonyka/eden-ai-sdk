<?php

namespace Demonyga\EdenAiSdk\Methods\Image;

use Demonyga\EdenAiSdk\EdenAIResponse;
use Demonyga\EdenAiSdk\Exceptions\EdenAIException;
use Demonyga\EdenAiSdk\Traits\Http;
use JsonException;
use Illuminate\Support\Facades\Config;

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
    public function checkExplicitContent(array $params): EdenAIResponse
    {
        if (!isset($params["file_url"])) {
            throw new EdenAIException("Missing required parameter 'file_url'");
        }
        if (!isset($params["providers"])) {
            $params["providers"] = Config::get('edenai.exclipt_content.providers');
        }

        return $this->post('image/explicit_content', $params);
    }
}