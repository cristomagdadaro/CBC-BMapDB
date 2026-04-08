<?php

namespace App\Http\Controllers\OpenAi;

use App\Http\Controllers\BaseController;
use App\Repository\API\OpenAiQueryRepo;
use Exception;
use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;

class AiChatController extends BaseController
{
    private const OUT_OF_SCOPE_MESSAGE = 'Sorry, only biotechnology or agriculture-related queries are allowed. Please try again.';

    protected OpenAiQueryRepo $openAiQueryRepo;

    public function __construct(OpenAiQueryRepo $openAiQueryRepo)
    {
        $this->openAiQueryRepo = $openAiQueryRepo;
    }

    /**
     * @throws Exception
     */
    public function chat(Request $request)
    {
        try {
            Validator::make($request->all(), [
                'query' => ['required', 'string'],
            ])->validate();

            $originalQuery = trim((string) $request->input('query'));
            $this->guardQueryLength($originalQuery);

            $model = (string) config('openai.chat_model', 'qwen/qwen3.5-9b');
            $responsePayload = $this->requestChatCompletion([
                'model' => $model,
                'max_tokens' => 256,
                'messages' => [
                    ['role' => 'system', 'content' => $this->systemPrompt()],
                    ['role' => 'user', 'content' => $originalQuery],
                ],
            ]);

            $responseText = $this->sanitizeResponseText($this->extractResponseText(
                $responsePayload
            ));

            if ((bool) config('openai.log_queries', false)) {
                $this->openAiQueryRepo->logQuery(
                    $originalQuery,
                    $model,
                    $responseText
                );
            }

            return $this->sendResponse([
                'aiResponse' => $responseText,
                'model' => $model,
                'provider' => config('openai.provider_name', 'LLMama'),
            ]);
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * @throws Exception
     */
    private function requestChatCompletion(array $payload): array
    {
        $baseUrl = $this->normalizeBaseUrl((string) config('openai.base_url', 'http://192.168.36.10:1234'));
        $isOfficialOpenAi = Str::contains(Str::lower($baseUrl), 'api.openai.com');
        $apiKey = $isOfficialOpenAi
            ? (string) config('openai.api_key', '')
            : (string) config('openai.compat_api_key', 'llmama');

        $headers = [
            'Authorization' => 'Bearer ' . ($apiKey !== '' ? $apiKey : 'llmama'),
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
        ];

        $organization = (string) config('openai.organization', '');
        if ($isOfficialOpenAi && $organization !== '') {
            $headers['OpenAI-Organization'] = $organization;
        }

        $client = new GuzzleClient([
            'base_uri' => $baseUrl . '/',
            'timeout' => (int) config('openai.request_timeout', 120),
        ]);

        try {
            $response = $client->post('chat/completions', [
                'headers' => $headers,
                'json' => $payload,
            ]);
        } catch (GuzzleException $exception) {
            throw new Exception('Chat provider request failed: ' . $exception->getMessage(), 0, $exception);
        }

        $decoded = json_decode((string) $response->getBody(), true);

        if (!is_array($decoded)) {
            throw new Exception('Chat provider returned an invalid JSON response.');
        }

        if (isset($decoded['error'])) {
            $error = is_array($decoded['error']) ? ($decoded['error']['message'] ?? json_encode($decoded['error'])) : (string) $decoded['error'];
            throw new Exception('Chat provider error: ' . $error);
        }

        return $decoded;
    }

    private function normalizeBaseUrl(string $baseUrl): string
    {
        $normalized = rtrim($baseUrl, '/');

        if (!Str::contains(Str::lower($normalized), 'api.openai.com') && !Str::endsWith($normalized, '/v1')) {
            $normalized .= '/v1';
        }

        return $normalized;
    }

    private function extractResponseText(array $result): string
    {
        $message = $result['choices'][0]['message'] ?? null;
        $content = $message['content'] ?? null;

        if (is_string($content) && trim($content) !== '') {
            return $content;
        }

        if (is_array($content)) {
            $text = collect($content)
                ->map(function ($part) {
                    if (is_array($part)) {
                        return $part['text'] ?? $part['content'] ?? null;
                    }

                    if (is_object($part)) {
                        return $part->text ?? $part->content ?? null;
                    }

                    return null;
                })
                ->filter(fn ($value) => is_string($value) && trim($value) !== '')
                ->implode("\n");

            if ($text !== '') {
                return $text;
            }
        }

        $reasoningContent = $message['reasoning_content']
            ?? $message['reasoningContent']
            ?? null;

        $reasoningFallback = $this->compressReasoningToAnswer($reasoningContent);
        if ($reasoningFallback !== null) {
            return $reasoningFallback;
        }

        throw new Exception('The configured chat provider returned an empty response.');
    }

    private function sanitizeResponseText(string $text): string
    {
        $normalized = trim($text);

        if ($normalized === '') {
            return $normalized;
        }

        if (Str::contains($normalized, self::OUT_OF_SCOPE_MESSAGE)) {
            return self::OUT_OF_SCOPE_MESSAGE;
        }

        return $normalized;
    }

    private function compressReasoningToAnswer(mixed $reasoningContent): ?string
    {
        if (!is_string($reasoningContent) || trim($reasoningContent) === '') {
            return null;
        }

        $lines = preg_split('/\r\n|\r|\n/', $reasoningContent) ?: [];
        $fragments = [];

        foreach ($lines as $line) {
            $normalized = trim(str_replace(['*', '`'], '', $line));

            if ($normalized === '' || str_ends_with($normalized, ':')) {
                continue;
            }

            if (preg_match('/^\d+\./', $normalized)) {
                continue;
            }

            if (preg_match('/^(Thinking Process|Analyze the Request|Evaluate the Topic|Determine|Constraint|Goal|User asks|User question|User input|My role|This query is related)/i', $normalized)) {
                continue;
            }

            if (str_contains($normalized, 'unrelated queries are allowed')) {
                continue;
            }

            if (str_contains($normalized, ':')) {
                [, $normalized] = array_pad(explode(':', $normalized, 2), 2, '');
                $normalized = trim($normalized);
            }

            if ($normalized === '') {
                continue;
            }

            $fragments[] = rtrim($normalized, '. ') . '.';
        }

        $text = trim(implode(' ', array_unique($fragments)));

        if ($text === '') {
            return null;
        }

        return Str::limit($text, 500);
    }

    private function systemPrompt(): string
    {
        return 'You are the CBC PIN Biotech Assistant. Focus on biotechnology, agriculture, plant breeding, crop improvement, and related Philippine research or compliance topics. '
            . 'Respond directly with a short final answer in message.content only. Do not spend time on hidden step-by-step reasoning. '
            . 'Answer the user\'s exact question first. If the user asks for a definition or explanation, start with a plain-language definition in the first sentence, then add 1-2 brief examples only if helpful. '
            . 'Keep normal answers concise unless the user asks for depth. '
            . 'If a user asks something completely unrelated to those domains, respond with this exact sentence: '
            . self::OUT_OF_SCOPE_MESSAGE;
    }

    private function guardQueryLength(string $query): void
    {
        $maxLength = 500;

        if (strlen($query) > $maxLength) {
            throw new Exception('Query exceeds the maximum allowed length of 500 characters.');
        }
    }

}
