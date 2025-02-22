<?php

namespace EdenAI\Methods\Text;

use EdenAI\Exceptions\EdenAIException;
use EdenAI\Objects\GeneratedCode;
use EdenAI\Traits\Http;
use Illuminate\Support\Facades\Config;
use JsonException;

/**
 * Class CodeGeneration.
 *
 * @mixin Http
 */
trait CodeGeneration
{
    /**
     * Code generation
     *
     * <code>
     * $params = [
     *       'instruction'                   => '',  // string     - Required. Instruction for code generation
     *       'prompt'                        => '',  // string     - Optional. Code prompt if you want to append or edit a piece of code
     *       'temperature'                   => '',  // float      - Optional. Default in config
     *       'max_tokens'                    => '',  // int        - Optional. Default in config
     *       'providers'                     => '',  // string     - (Optional). providers separated by commas (Default in Config)
     * ]
     * </code>
     *
     * @link https://app.edenai.run/bricks/text/code-generation
     *
     * @throws EdenAIException|JsonException
     */
    public function generateCode(array $params): GeneratedCode
    {
        if (!isset($params["instruction"])) {
            throw new EdenAIException("Missing required parameter 'instruction'");
        }
        if (!isset($params['language'])) {
            $params['temperature'] = Config::get('edenai.code_generation.temperature', 0.1);
        }
        if (!isset($params['max_tokens'])) {
            $params['max_tokens'] = Config::get('edenai.code_generation.max_tokens', 500);
        }
        if (!isset($params["providers"])) {
            $params["providers"] = Config::get('edenai.code_generation.providers', 'google');
        }

        return new GeneratedCode($this->post('text/code_generation', $params)->getDecodedBody());
    }
}