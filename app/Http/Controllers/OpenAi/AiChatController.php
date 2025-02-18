<?php

namespace App\Http\Controllers\OpenAi;

use App\Http\Controllers\BaseController;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use OpenAI\Laravel\Facades\OpenAI;
use Illuminate\Support\Facades\Validator;

class AiChatController extends BaseController
{
    /**
     * @throws Exception
     */
    public function chat(Request $request)
    {
        try {
            Validator::make($request->all(), [
                'query' => ['required', 'string'],
            ])->validate();

            $query = $this->addContextToQuery($request->input('query'));

            $model = 'gpt-4o-mini';
            $result = OpenAI::chat()->create([
                'model' => $model,
                'messages' => [
                    ['role' => 'user', 'content' => $query],
                ],
            ]);

            DB::table('openai_queries')->insert([
                'query' => $request->input('query'),
                'model' => $model,
                'response' => $result->choices[0]->message->content,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            return $this->sendResponse(['aiResponse' => $result->choices[0]->message->content]);
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Prepend context to ensure the query stays within biotechnology and agriculture.
     */
    private function addContextToQuery(string $query): string
    {
        // Validate the query
        if (!$this->isAllowedTopic($query)) {
            // Log misuse
            logger()->info("Invalid query: {$query}");

            // Return a clear error message
            return "Sorry, only biotechnology or agriculture-related queries are allowed. Please try again.";
        }

        // Ensure the query stays within our scope
        if (preg_match('/^\/(biotech|agriculture)/', $query)) {
            return $query;
        }

        // If it doesn't match, prepend a filter
        return "This question must be related to biotechnology or agriculture if not, respond with this exact string: Sorry, only biotechnology or agriculture-related queries are allowed. Guest query: {$query}";
    }


    private function isAllowedTopic(string $query): bool
    {
        // Define the maximum character length for the query to prevent abuse
        $maxLength = 500;

        // If query exceeds our allowed scope, automatically reject it
        if (strlen($query) > $maxLength) {
            return false;
        }

        // Simple keyword check - you might want to use a more sophisticated method here
        $allowedTopics = [
            'biotechnology',
            'agriculture',
            'plant breeding',
            'genetic engineering',
            'crop improvement',
        ];

        // Split the query into words and check against allowed topics
        $words = explode(' ', $query);
        foreach ($words as $word) {
            if (in_array($word, $allowedTopics)) {
                return true;
            }
        }

        return false;
    }

}
