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
}