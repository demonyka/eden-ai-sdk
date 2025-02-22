<?php

namespace EdenAI\Objects;

class GeneratedCode extends BaseObject
{
    public function relations(): array
    {
        return [
            'providers' => $this->providers,
        ];
    }

    /**
     * Get the generated code text.
     *
     * If a provider is specified, it returns the generated code (`generated_text`) for that specific provider.
     * If no provider is specified, it returns the generated code texts for all providers in the object.
     * If the provider does not exist, `null` will be returned.
     *
     * @param string|null $provider The name of the provider (optional).
     *
     * @return string|array|null
     * - If a provider is specified and exists: string (generated code).
     * - If a provider is specified and does not exist: null.
     * - If no provider is specified: array of generated texts for all providers.
     *
     * @example
     * $generatedCode->getResult('google'); // Returns the generated code text for Google provider.
     * $generatedCode->getResult();         // Returns an array of generated code texts for all providers.
     */
    public function getResult(?string $provider = null): string|array|null
    {
        if ($provider) {
            return isset($this->providers[$provider]) ? $this->providers[$provider]['generated_text'] : null;
        }

        return array_map(function ($data) {
            return $data['generated_text'];
        }, $this->providers);
    }

}