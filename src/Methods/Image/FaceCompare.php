<?php

namespace EdenAI\Methods\Image;

use EdenAI\Exceptions\EdenAIException;
use EdenAI\Objects\FaceCompared;
use EdenAI\Traits\Http;
use Illuminate\Support\Facades\Config;
use JsonException;

/**
 * Class ObjectDetection.
 *
 * @mixin Http
 */
trait FaceCompare
{
    /**
     * Face Compare
     *
     * <code>
     * $params = [
     *       'file1_url'                    => '',  // string     - Required. direct URL to photo
     *       'file2_url'                    => '',  // string     - Required. direct URL to photo
     *       'file1'                    => '',  // string         - Required if url is not specified. file photo
     *       'file2'                    => '',  // string         - Required if url is not specified. file photo
     *       'providers'                   => '',  // string     - (Optional). providers separated by commas (Default in Config)
     * ]
     * </code>
     *
     * @link https://app.edenai.run/bricks/image/object-detection
     *
     * @throws EdenAIException|JsonException
     */
    public function compareFace(array $params): FaceCompared
    {
        if ((isset($params["file1_url"]) && !isset($params["file2_url"])) || (!isset($params["file1_url"]) && isset($params["file2_url"]))) {
            throw new EdenAIException("Both file URLs must be provided together.");
        } elseif ((isset($params["file1"]) && !isset($params["file2"])) || (!isset($params["file1"]) && isset($params["file2"]))) {
            throw new EdenAIException("Both files must be provided together.");
        }

        if (!isset($params["providers"])) {
            $params["providers"] = Config::get('edenai.face_compare.providers', 'amazon');
        }

        return new FaceCompared($this->post('image/face_compare', $params)->getDecodedBody());
    }
}