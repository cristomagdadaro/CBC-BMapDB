<?php

use App\Enums\Permission;
use App\Http\Controllers\OpenAi\AiChatController;
use Illuminate\Support\Facades\Route;

/*OpenAi Related APIs*/
Route::prefix('/openai')->group(function () {
    Route::post('/chat', [AiChatController::class, 'chat'])->name('api.openai.chat');
});

