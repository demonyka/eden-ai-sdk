<?php

namespace EdenAI\Objects;

use Illuminate\Support\Facades\Config;

class DeepfakeObject extends BaseObject
{

    protected array $providers = [];
    public function __construct(array $data = [])
    {
        parent::__construct($data);

        foreach ($data as $key => $provider) {
            $this->providers[$key] = new Provider($provider);
        }
    }
    public function relations(): array
    {
        return [
            'providers' => $this->providers,
        ];
    }

    public function getProviders(): array
    {
        return $this->providers;
    }

    public function getCost(): float
    {
        return array_reduce($this->providers, function ($sum, $provider) {
            return $sum + $provider->getCost();
        }, 0);
    }

    public function getAverageScore(): float
    {
        $totalProviders = count($this->providers);

        if ($totalProviders === 0) {
            return 0;
        }

        $sum = array_reduce($this->providers, function ($sum, $provider) {
            return $sum + $provider['deepfake_score'];
        }, 0);

        return $sum / $totalProviders;
    }

    public function isDeepfake(): bool
    {
        $score = $this->getAverageScore();
        return $score > Config::get('edenai.deepfake_detection.threshold', 0.8);
    }
}