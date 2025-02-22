<?php

namespace EdenAI\Objects;

use Illuminate\Support\Facades\Config;

class DeepfakeObject extends BaseObject
{
    public function relations(): array
    {
        return [
            'providers' => $this->providers,
        ];
    }

    /**
     * Get the average deepfake score across all providers.
     *
     * This method calculates the average 'deepfake_score' for all the providers
     * that have a status of 'success'. If no providers are available or all providers failed,
     * it returns 0.
     *
     * @return float
     * - The average deepfake score across all successful providers.
     * - Returns 0 if no providers are available or all providers failed.
     *
     * @example
     * $deepfakeObject->getAverageScore(); // Returns the average deepfake score across all providers.
     */
    public function getAverageScore(): float
    {
        $sum = 0;
        $validProviders = 0;

        foreach ($this->providers as $provider) {
            if ($provider['status'] !== 'success') continue;
            $sum += $provider['deepfake_score'];
            $validProviders++;
        }

        // Если нет валидных провайдеров, возвращаем 0
        if ($validProviders === 0) {
            return 0;
        }

        return $sum / $validProviders;
    }


    /**
     * Determine if the content is a deepfake based on the average deepfake score.
     *
     * This method compares the average deepfake score to a threshold value
     * (configured in `edenai.deepfake_detection.threshold`). If the score is greater
     * than the threshold, it returns true, indicating the content is a deepfake. Otherwise, false.
     *
     * @return bool
     * - true if the content is a deepfake based on the average score.
     * - false if the content is not a deepfake.
     *
     * @example
     * $deepfakeObject->isDeepfake(); // Returns true if the content is a deepfake, based on the average score.
     */
    public function isDeepfake(): bool
    {
        $score = $this->getAverageScore();
        return $score > Config::get('edenai.deepfake_detection.threshold', 0.8);
    }
}