<?php

namespace EdenAI\Methods\Image;

use EdenAI\Exceptions\EdenAIException;
use EdenAI\Objects\DeepfakeObject;
use EdenAI\Traits\Http;
use Illuminate\Support\Facades\Config;
use JsonException;

/**
 * Class ExplicitContent.
 *
 * @mixin Http
 */
trait DeepfakeDetection
{
    /**
     * @throws EdenAIException|JsonException
     */
    public function checkDeepfake(array $params): DeepfakeObject
    {
        if (!isset($params["file_url"])) {
            throw new EdenAIException("Missing required parameter 'file_url'");
        }
        if (!isset($params["providers"])) {
            $params["providers"] = Config::get('edenai.deepfake_detection.providers', 'sightengine');
        }

        return new DeepfakeObject($this->post('image/deepfake_detection', $params)->getDecodedBody());
    }
}