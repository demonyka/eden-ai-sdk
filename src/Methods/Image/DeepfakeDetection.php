<?php

namespace EdenAI\Methods\Image;

use EdenAI\Exceptions\EdenAIException;
use EdenAI\Objects\DeepfakeObject;
use EdenAI\Traits\Http;
use Illuminate\Support\Facades\Config;
use JsonException;

/**
 * Class DeepfakeDetection.
 *
 * @mixin Http
 */
trait DeepfakeDetection
{
    /**
     * Deepfake Detection
     *
     * <code>
     * $params = [
     *       'file_url'                    => '',  // string     - Required. direct URL to photo
     *       'providers'                   => '',  // string     - (Optional). providers separated by commas (Default in Config)
     * ]
     * </code>
     *
     * @link https://app.edenai.run/bricks/text/moderation
     *
     * @throws EdenAIException|JsonException
     */
    public function detectDeepfake(array $params): DeepfakeObject
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