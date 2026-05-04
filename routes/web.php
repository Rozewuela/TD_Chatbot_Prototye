<?php

use App\Http\Controllers\ChatbotController;
use Illuminate\Support\Facades\Route;

/* ===== Landing Page ===== */
Route::get('/', function () {
    return view('layouts.landing');
})->name('landing');

/* ===== Registration Page ===== */
Route::get('/register', function () {
    return view('auth.register');
})->name('register');

/* ===== Registration Form Submit (Placeholder) ===== */
Route::post('/register', function () {
    // TODO: Replace with AuthController@store when backend is ready
    return redirect()->back()->with('message', 'Registration backend not yet implemented.');
})->name('register.submit');

/* ===== Login Page ===== */
Route::get('/login', function () {
    return view('auth.login');
})->name('login');

/* ===== Login / Logout Form Submit (Placeholders) ===== */
Route::post('/login', function () {
    // TODO: Replace with AuthController@login when backend is ready
    return redirect()->back()->with('message', 'Login backend not yet implemented.');
})->name('login.submit');

Route::post('/logout', function () {
    // TODO: Replace with AuthController@logout when backend is ready
    return redirect()->route('landing')->with('message', 'Logged out (placeholder).');
})->name('logout');

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