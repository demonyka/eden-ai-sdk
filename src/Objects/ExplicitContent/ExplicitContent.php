<?php

namespace EdenAI\Objects\ExplicitContent;

use EdenAI\Objects\BaseObject;
use EdenAI\Objects\Provider;
use Illuminate\Support\Facades\Config;

class ExplicitContent extends BaseObject
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

    public function getCost(): float
    {
        return array_reduce($this->providers, function ($sum, $provider) {
            return $sum + $provider->getCost();
        }, 0);
    }

    public function getRate(): float
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

    public function getScore(): float
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

    public function isSafe(): bool
    {
        $score = $this->getAverageScore();
        return $score < Config::get('edenai.exclipt_content.threshold', 0.8);
    }
}