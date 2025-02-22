<?php

namespace EdenAI\Objects;

use Illuminate\Support\Facades\Log;

/**
 * Class Collection.
 *
 * @mixin Collection
 */
abstract class BaseObject extends Collection
{
    protected array $providers = [];

    public function __construct($data)
    {
        parent::__construct($this->getRawResult($data));

        $this->initializeProviders($data);
    }

    protected function initializeProviders(array $data): void
    {
        foreach ($data as $key => $provider) {
            if (isset($provider['error'])) {
                Log::error($provider['error']['message']);
            }

            if (isset($provider['status'])) {
                $this->providers[$key] = new Provider($provider);

                if ($provider['status'] === 'fail' && isset($provider['error']['message'])) {
                    Log::error($provider['error']['message']);
                }
            }
        }
    }

    public function getCost(): float
    {
        return array_reduce($this->providers, function ($sum, $provider) {
            return $sum + $provider->getCost();
        }, 0);
    }

    public function getProviders(): array
    {
        return $this->providers;
    }
}
