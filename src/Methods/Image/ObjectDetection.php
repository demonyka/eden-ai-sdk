<?php

namespace EdenAI\Methods\Image;

use EdenAI\Exceptions\EdenAIException;
use EdenAI\Objects\DetectedObject;
use EdenAI\Traits\Http;
use Illuminate\Support\Facades\Config;
use JsonException;

/**
 * Class ObjectDetection.
 *
 * @mixin Http
 */
trait ObjectDetection
{
    /**
     * Object detection
     *
     * <code>
     * $params = [
     *       'file_url'                    => '',  // string     - Required. direct URL to photo
     *       'providers'                   => '',  // string     - (Optional). providers separated by commas (Default in Config)
     * ]
     * </code>
     *
     * @link https://app.edenai.run/bricks/image/object-detection
     *
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