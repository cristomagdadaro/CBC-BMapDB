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
                'query' => ['required'],
            ])->validate();

            $model = 'gpt-4o-mini';
            $query = $request->input('query');
            $result = OpenAI::chat()->create([
                'model' => $model,
                'messages' => [
                    ['role' => 'user', 'content' => $query],
                ],
            ]);

            DB::table('openai_queries')->insert([
                'query' => $query,
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
}
