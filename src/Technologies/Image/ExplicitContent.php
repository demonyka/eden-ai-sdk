<?php

namespace Demonyga\EdenAiSdk\Technologies\Image;

use InvalidArgumentException;

class ExplicitContent
{
    /**
     * Checks an image for explicit content.
     *
     * <code>
     * $params = [
     *      'file_url' => 'http://example.com/photo.jpg', // string - Required. The URL of the image to check for explicit content.
     *      'providers' => 'clarifai,google', // string  - Optional. List of providers for content check. If not provided, the default value from the configuration will be used.
     * ];
     * </code>
     *
     * @param array $params Parameters for the content check.
     *
     * @throws InvalidArgumentException If any of the required parameters are missing.
     */
    public function check(array $params)
    {
        if (empty($params['file_url'])) {
            throw new InvalidArgumentException('The "file_url" parameter is required.');
        }

        return $params;
    }
}