<?php

return [
    'token' => env('EDEN_AI_TOKEN', ''),
    'exclipt_content' => [
        'providers' => 'clarifai,google',
        'threshold' => 0.8
    ]
];
