<?php

namespace EdenAI\Objects;

use Illuminate\Support\Facades\Config;

class ExplicitContent extends BaseObject
{
    public function relations(): array
    {
        return [
            'providers' => $this->providers,
        ];
    }

    /**
     * Get the average rate of NSFW likelihood across all providers.
     *
     * This method calculates the average 'nsfw_likelihood' value for all the providers
     * in the `providers` array. If no providers exist, it returns 0.
     *
     * @return float
     * - The average NSFW likelihood rate across all providers.
     * - Returns 0 if no providers are available.
     *
     * @example
     * $explicitContent->getAverageRate(); // Returns the average NSFW likelihood rate across all providers.
     */
    public function getAverageRate(): float
    {
        $sum = 0;
        $validProviders = 0;

        foreach ($this->providers as $provider) {
            if ($provider['status'] !== 'success') continue;
            $sum += $provider['nsfw_likelihood'];
            $validProviders++;
        }

        if ($validProviders === 0) {
            return 0;
        }

        return $sum / $validProviders;
    }



    /**
     * Get the average score of NSFW likelihood across all providers.
     *
     * This method calculates the average 'nsfw_likelihood_score' value for all the providers
     * in the `providers` array. If no providers exist, it returns 0.
     *
     * @return float
     * - The average NSFW likelihood score across all providers.
     * - Returns 0 if no providers are available.
     *
     * @example
     * $explicitContent->getAverageScore(); // Returns the average NSFW likelihood score across all providers.
     */
    public function getAverageScore(): float
    {
        $sum = 0;
        $validProviders = 0;

        foreach ($this->providers as $provider) {
            if ($provider['status'] !== 'success') continue;
            $sum += $provider['nsfw_likelihood_score'];
            $validProviders++;
        }

        if ($validProviders === 0) {
            return 0;
        }

        return $sum / $validProviders;
    }

    public function isNSFW(): bool
    {
        $score = $this->getAverageScore();
        return $score > Config::get('edenai.exclipt_content.threshold', 0.8);
    }
}