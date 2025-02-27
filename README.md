<h1 align="center" id="title">EdenAI SDK</h1>

<p align="center"><img src="https://socialify.git.ci/demonyka/eden-ai-sdk/image?custom_description=Unofficial+API+SDK+for+Eden+AI%0Ahttps%3A%2F%2Fwww.edenai.co%2F&amp;description=1&amp;forks=1&amp;issues=1&amp;language=1&amp;name=1&amp;owner=1&amp;pattern=Plus&amp;pulls=1&amp;stargazers=1&amp;theme=Auto" alt="project-image"></p>

# EdenAI SDK Documentation

## Introduction

The EdenAI SDK is an unofficial PHP library that provides a convenient interface to interact with the Eden AI platform. Eden AI is a platform that aggregates multiple AI service providers, allowing developers to access various AI capabilities through a unified API.

This SDK makes it easy to integrate Eden AI services into your PHP applications, especially in Laravel projects.

## Table of Contents

- [Installation](#installation)
- [Configuration](#configuration)
- [Basic Usage](#basic-usage)
- [Available Features](#available-features)
    - [Image Processing](#image-processing)
        - [Explicit Content Detection](#explicit-content-detection)
        - [Deepfake Detection](#deepfake-detection)
        - [Object Detection](#object-detection)
        - [Face Comparison](#face-comparison)
    - [Text Processing](#text-processing)
        - [Text Moderation](#text-moderation)
        - [Code Generation](#code-generation)
    - [Audio Processing](#audio-processing)
        - [Text to Speech](#text-to-speech)
- [Laravel Integration](#laravel-integration)
- [Advanced Usage](#advanced-usage)
    - [Working with Providers](#working-with-providers)
    - [Error Handling](#error-handling)
    - [Custom HTTP Clients](#custom-http-clients)
- [API Reference](#api-reference)

## Installation

You can install the SDK using Composer:

```bash
composer require demonyka/eden-ai-sdk
```

## Configuration

### Direct Usage

When using the SDK directly, you can provide the API token when creating an instance:

```php
use EdenAI\Api;

// Method 1: Provide token directly
$api = new Api('your-api-token-here');

// Method 2: Use environment variable
// Set the EDEN_AI_TOKEN environment variable
// $api = new Api();
```

### Laravel Integration

If you're using Laravel, publish the configuration file:

```bash
php artisan vendor:publish --tag="edenai-config"
```

This will create a `config/edenai.php` file where you can configure the SDK. Add your API token to your `.env` file:

```
EDEN_AI_TOKEN=your-api-token-here
```

## Basic Usage

### Creating an API Instance

```php
use EdenAI\Api;

// Initialize with API token
$api = new Api('your-api-token-here');
```

### Making API Requests

Each API method corresponds to a specific Eden AI endpoint. Here's a basic example:

```php
// Check for explicit content in an image
$result = $api->checkExplicitContent([
    'file_url' => 'https://example.com/image.jpg',
    'providers' => 'google,clarifai'
]);

// Check if the image is NSFW
if ($result->isNSFW()) {
    echo "This image contains explicit content";
} else {
    echo "This image is safe";
}
```

## Available Features

The SDK provides access to various AI capabilities offered by Eden AI:

### Image Processing

#### Explicit Content Detection

Detect explicit or NSFW content in images:

```php
$result = $api->checkExplicitContent([
    'file_url' => 'https://example.com/image.jpg',
    'providers' => 'clarifai,google' // Optional, defaults to config
]);

// Get the average NSFW score across providers
$score = $result->getAverageScore();

// Check if the image is considered NSFW
$isNSFW = $result->isNSFW();
```

#### Deepfake Detection

Detect if an image has been manipulated or artificially generated:

```php
$result = $api->detectDeepfake([
    'file_url' => 'https://example.com/image.jpg',
    'providers' => 'sightengine' // Optional, defaults to config
]);

// Get the average deepfake score
$score = $result->getAverageScore();

// Check if the image is likely a deepfake
$isDeepfake = $result->isDeepfake();
```

#### Object Detection

Detect and identify objects in images:

```php
$result = $api->detectObject([
    'file_url' => 'https://example.com/image.jpg',
    'providers' => 'google,amazon' // Optional, defaults to config
]);

// Get all detected items with confidence scores
$items = $result->getItems();

// Check for a specific object
$hasCar = $result->hasItem('car');

// Get confidence score for a specific object
$carConfidence = $result->item('car');
```

#### Face Comparison

Compare faces in two different images:

```php
$result = $api->compareFace([
    'file1_url' => 'https://example.com/face1.jpg',
    'file2_url' => 'https://example.com/face2.jpg',
    'providers' => 'amazon' // Optional, defaults to config
]);

// Get average confidence of face matching
$confidence = $result->getAverageConfidence();

// Check if faces are considered the same person
$isMatched = $result->isCompared();
```

### Text Processing

#### Text Moderation

Detect inappropriate or harmful content in text:

```php
$result = $api->moderateText([
    'text' => 'Text to be moderated',
    'language' => 'en', // Optional, defaults to config
    'providers' => 'google' // Optional, defaults to config
]);

// Similar to explicit content detection for images
$score = $result->getAverageScore();
$isInappropriate = $result->isNSFW();
```

#### Code Generation

Generate code based on natural language instructions:

```php
$result = $api->generateCode([
    'instruction' => 'Create a function that calculates the factorial of a number',
    'temperature' => 0.1, // Optional, defaults to config
    'max_tokens' => 500, // Optional, defaults to config
    'providers' => 'openai' // Optional, defaults to config
]);

// Get generated code from a specific provider
$code = $result->getResult('openai');

// Or get code from all providers
$allCode = $result->getResult();
```

### Audio Processing

#### Text to Speech

Convert text to spoken audio:

```php
$result = $api->textToSpeech([
    'text' => 'Hello, this is a test of text to speech',
    'language' => 'en', // Optional, defaults to config
    'option' => 'MALE', // Optional, defaults to config (MALE/FEMALE)
    'providers' => 'openai' // Optional, defaults to config
]);

// Get audio URL from a specific provider
$audioUrl = $result->getAudioResourceUrl('openai');

// Or get audio URLs from all providers
$allAudioUrls = $result->getAudioResourceUrl();
```

## Laravel Integration

The SDK includes Laravel integration for easier usage:

### Facade

After publishing the config file, you can use the EdenAI facade:

```php
use EdenAI\Laravel\Facades\EdenAI;

// Use the facade
$result = EdenAI::checkExplicitContent([
    'file_url' => 'https://example.com/image.jpg'
]);
```

### Configuration

The published `config/edenai.php` file allows you to set default configurations for each feature:

```php
// config/edenai.php example
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
    // Other configurations...
];
```

## Advanced Usage

### Working with Providers

Each method can work with multiple providers. The SDK will combine results from all providers to give you more accurate results:

```php
// Using multiple providers
$result = $api->detectObject([
    'file_url' => 'https://example.com/image.jpg',
    'providers' => 'google,amazon,microsoft'
]);

// Access individual provider results
$providers = $result->getProviders();
foreach ($providers as $providerName => $provider) {
    echo "Provider: $providerName\n";
    echo "Status: " . $provider['status'] . "\n";
    
    if ($provider['status'] === 'success') {
        // Access provider-specific data
        print_r($provider->getRawResponse());
    } else {
        // Handle error
        echo "Error: " . $provider['error']['message'] . "\n";
    }
}

// Get total cost of the operation
$cost = $result->getCost();
```

### Error Handling

The SDK uses exceptions to handle errors:

```php
use EdenAI\Exceptions\EdenAIException;

try {
    $result = $api->checkExplicitContent([
        'file_url' => 'https://example.com/image.jpg'
    ]);
} catch (EdenAIException $e) {
    echo "Error: " . $e->getMessage() . "\n";
    echo "Code: " . $e->getCode() . "\n";
}
```

### Custom HTTP Clients

You can customize the HTTP client used by the SDK:

```php
use EdenAI\Api;
use EdenAI\HttpClients\GuzzleHttpClient;
use GuzzleHttp\Client;

// Create custom Guzzle client with specific options
$guzzleClient = new Client([
    'proxy' => 'http://proxy.example.com:8125',
    'verify' => false,
]);

$httpClient = new GuzzleHttpClient($guzzleClient);

// Pass to API
$api = new Api('your-token', $httpClient);

// Or set after instantiation
$api->setHttpClientHandler($httpClient);

// Configure timeouts
$api->setTimeOut(120); // in seconds
$api->setConnectTimeOut(30); // in seconds
```

## API Reference

### Main API Class

`EdenAI\Api` is the main entry point for interacting with the SDK.

#### Constructor

```php
public function __construct(
    ?string $token = null, 
    ?HttpClientInterface $httpClientHandler = null, 
    ?string $baseUrl = null
)
```

- `$token`: Your Eden AI API token
- `$httpClientHandler`: Custom HTTP client implementation
- `$baseUrl`: Custom API base URL (defaults to Eden AI's API)

#### HTTP Methods

```php
public function setTimeOut(int $timeOut): self
public function setConnectTimeOut(int $connectTimeOut): self
public function getLastResponse(): ?EdenAIResponse
```

### Response Objects

All API methods return specific response objects that extend `EdenAI\Objects\BaseObject`.

Common methods available on all response objects:

```php
public function getCost(): float
public function getProviders(): array
public function getRawResponse(): mixed
```

### Feature-Specific Methods

Each feature has its own methods and responses as documented in the [Available Features](#available-features) section.

## License

The EdenAI SDK is open-source software licensed under the MIT license.