<?php

namespace EdenAI\Methods\Image;

use EdenAI\Exceptions\EdenAIException;
use EdenAI\Objects\DetectedObject;
use EdenAI\Traits\Http;
use Illuminate\Support\Facades\Config;
use JsonException;

/**
 * Class ExplicitContent.
 *
 * @mixin Http
 */
trait ObjectDetection
{
    /**
     * @throws EdenAIException|JsonException
     */
    public function detectObject(array $params): DetectedObject
    {
        if (!isset($params["file_url"])) {
            throw new EdenAIException("Missing required parameter 'file_url'");
        }
        if (!isset($params["providers"])) {
            $params["providers"] = Config::get('edenai.object_detection.providers', 'google,amazon');
        }

        return new DetectedObject($this->post('image/object_detection', $params)->getDecodedBody());
    }
}