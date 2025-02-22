<?php

namespace EdenAI\Objects;

class SpeechesText extends BaseObject
{
    public function relations(): array
    {
        return [
            'providers' => $this->providers,
        ];
    }

    /**
     * Get the audio resource URL.
     *
     * If a provider is specified, it returns the `audio_resource_url` for that specific provider.
     * If no provider is specified, it returns an array of `audio_resource_url` for all providers in the object.
     * If the specified provider does not exist, `null` will be returned.
     *
     * @param string|null $provider The name of the provider (optional).
     *
     * @return string|array|null
     * - If a provider is specified and exists: string (audio resource URL).
     * - If a provider is specified and does not exist: null.
     * - If no provider is specified: array of audio resource URLs for all providers.
     *
     * @example
     * $speech->getAudioResourceUrl('openai'); // Returns the audio resource URL for the OpenAI provider.
     * $speech->getAudioResourceUrl();         // Returns an array of audio resource URLs for all providers.
     */
    public function getAudioResourceUrl(?string $provider = null): string|array|null
    {
        if ($provider) {
            return $this->providers[$provider]['audio_resource_url'] ?? null;
        }

        $result = array_map(function ($data) {
            return $data['audio_resource_url'] ?? null;
        }, $this->providers);

        return !empty($result) ? $result : null;
    }

}