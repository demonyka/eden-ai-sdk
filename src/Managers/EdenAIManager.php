<?php

namespace Demonyga\EdenAiSdk\Managers;

use Demonyga\EdenAiSdk\Technologies\Image\ExplicitContent;

class EdenAIManager
{
    protected ExplicitContent $explicitContent;

    public function __construct()
    {
        $this->explicitContent = new ExplicitContent();
    }

    public function explicitContent(): ExplicitContent
    {
        return $this->explicitContent;
    }
}
