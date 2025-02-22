<?php

return [
    'token' => env('EDEN_AI_TOKEN', ''),
    'exclipt_content' => [
        'providers' => 'clarifai,google',
        'threshold' => 0.8
    ],
    'deepfake_detection' => [
        'providers' => 'sightengine',
        'threshold' => 0.8
    ],
    'object_detection' => [
        'providers' => 'google,amazon',
        'threshold' => 0.6
    ],
    'moderate_text' => [
        'providers' => 'google',
        'threshold' => 0.8,
        'fallbackLanguage' => 'en'
    ],
    'code_generation' => [
        'providers' => 'openai',
        'temperature' => 0.1,
        'max_tokens' => 500
    ]
];
