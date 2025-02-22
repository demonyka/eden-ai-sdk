<?php

namespace EdenAI\Objects;

use Illuminate\Support\Facades\Config;

class ModeratedText extends ExplicitContent
{
    public function relations(): array
    {
        return [
            'providers' => $this->providers,
        ];
    }
}