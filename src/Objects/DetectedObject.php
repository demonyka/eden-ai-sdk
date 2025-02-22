<?php

namespace EdenAI\Objects;

use Illuminate\Support\Facades\Config;

class DetectedObject extends BaseObject
{
    public function relations(): array
    {
        return [
            'providers' => $this->providers,
        ];
    }

    public function getItems(): array
    {
        $items = [];

        foreach ($this->providers as $provider) {
            if ($provider['status'] !== 'success') continue;
            foreach ($provider->getItems() as $item) {
                $label = strtolower($item['label']);
                $confidence = $item['confidence'];

                if (isset($items[$label])) {
                    $items[$label]['sum'] += $confidence;
                    $items[$label]['count']++;
                } else {
                    $items[$label] = [
                        'sum' => $confidence,
                        'count' => 1,
                    ];
                }
            }
        }

        return array_map(function ($data) {
            return $data['sum'] / $data['count'];
        }, $items);
    }

    /**
     * Get the confidence for a specific label.
     *
     * @param string $label
     * @return float|null
     */
    public function item(string $label): ?float
    {
        $label = strtolower($label);
        $items = $this->getItems();

        return $items[$label] ?? null;
    }

    public function hasItem(string $label): bool
    {
        $label = strtolower($label);
        $items = $this->getItems();

        return isset($items[$label]) && ($items[$label] > Config::get('edenai.object_detection.threshold'));
    }
}