<?php

namespace EdenAI\Objects;

class FaceCompared extends ExplicitContent
{
    public function relations(): array
    {
        return [
            'providers' => $this->providers,
        ];
    }
}