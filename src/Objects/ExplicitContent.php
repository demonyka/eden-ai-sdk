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
    public function getAverageRate(): float
    {
        $totalProviders = count($this->providers);

        if ($totalProviders === 0) {
            return 0;
        }

        $sum = array_reduce($this->providers, function ($sum, $provider) {
            return $sum + $provider['nsfw_likelihood'];
        }, 0);

        return $sum / $totalProviders;
    }

    public function getAverageScore(): float
    {
        $totalProviders = count($this->providers);

        if ($totalProviders === 0) {
            return 0;
        }

        $sum = array_reduce($this->providers, function ($sum, $provider) {
            return $sum + $provider['nsfw_likelihood_score'];
        }, 0);

        return $sum / $totalProviders;
    }

    public function isNSFW(): bool
    {
        $score = $this->getAverageScore();
        return $score > Config::get('edenai.exclipt_content.threshold', 0.8);
    }
}