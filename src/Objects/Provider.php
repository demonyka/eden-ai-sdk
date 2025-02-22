<?php

namespace EdenAI\Objects;

class Provider extends BaseObject
{
    public function __construct(array $data = [])
    {
        parent::__construct($data);
    }
    public function relations(): array
    {
        return [];
    }
}