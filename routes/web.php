<?php

use App\Http\Controllers\ChatbotController;
use Illuminate\Support\Facades\Route;

Route::get('/chatbot', function () {
    return view('chatbot.index');
});


Route::post('/chat', [ChatbotController::class, 'chat']);

Route::get('/test-rag', function() {
    $path = 'health_data/tl/fever.txt';
    if (Storage::exists($path)) {
        return "Nakita! Content: " . Storage::get($path);
    }
    return "Hindi nakita ang file sa: " . storage_path('app/' . $path);
});