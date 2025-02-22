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

    public function getAverageScore(): float
    {
        $totalProviders = count($this->providers);

        if ($totalProviders === 0) {
            return 0;
        }

        $sum = 0;

        foreach ($this->providers as $provider) {
            if ($provider['status'] !== 'success') {
                continue;
            }
            $sum += $provider['deepfake_score'];
        }

        return $sum / $totalProviders;
    }

    public function isDeepfake(): bool
    {
        $score = $this->getAverageScore();
        return $score > Config::get('edenai.deepfake_detection.threshold', 0.8);
    }
}