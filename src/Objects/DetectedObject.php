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

    /**
     * Get the average confidence for each detected label across all providers.
     *
     * This method calculates the average confidence for each label by summing up the confidence values
     * from all providers that have a status of 'success'. It returns an array where each key is the label,
     * and the value is the average confidence for that label.
     *
     * @return array
     * - An associative array where the key is the label and the value is the average confidence.
     * - If no items are detected, an empty array is returned.
     *
     * @example
     * $detectedObject->getItems(); // Returns an array of average confidences for each detected label.
     */
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
     * Get the average confidence for a specific label.
     *
     * This method returns the average confidence for a given label, based on the data collected from all providers.
     * If the label is not found, it returns null.
     *
     * @param string $label The label to get the confidence for.
     *
     * @return float|null
     * - The average confidence for the label.
     * - null if the label is not found.
     *
     * @example
     * $detectedObject->item('cat'); // Returns the average confidence for the label 'cat'.
     */
    public function item(string $label): ?float
    {
        $label = strtolower($label);
        $items = $this->getItems();

        return $items[$label] ?? null;
    }

    /**
     * Check if a specific label is detected with sufficient confidence.
     *
     * This method checks if the given label is present in the detected items and if its average confidence
     * is above a configured threshold. It returns true if the label's confidence exceeds the threshold, otherwise false.
     *
     * @param string $label The label to check for.
     *
     * @return bool
     * - true if the label exists and its confidence is above the threshold.
     * - false otherwise.
     *
     * @example
     * $detectedObject->hasItem('cat'); // Returns true if the confidence for 'cat' is above the threshold.
     */
    public function hasItem(string $label): bool
    {
        $label = strtolower($label);
        $items = $this->getItems();

        return isset($items[$label]) && ($items[$label] > Config::get('edenai.object_detection.threshold'));
    }
}