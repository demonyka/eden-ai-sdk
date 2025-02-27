<?php

namespace EdenAI\Objects;

class FaceCompared extends BaseObject
{
    public function relations(): array
    {
        return [
            'providers' => $this->providers,
        ];
    }

    public function getAverageConfidence(): float|int
    {
        $totalConfidence = 0;
        $count = 0;

        foreach ($this->providers as $provider) {
            if ($provider['status'] !== 'success') continue;
            foreach ($provider['items'] as $item) {
                $totalConfidence += $item['confidence'];
                $count++;
            }
        }

        if ($count === 0) {
            return 0;
        }

        return $totalConfidence / $count;
    }
}