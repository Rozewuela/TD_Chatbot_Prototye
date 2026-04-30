<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;

class ChatbotController extends Controller
{
    public function chat(Request $request)
    {
        $userMessage = $request->input('message');
        $activeLang = $request->input('lang', 'en'); 
        Session::put('chat_lang', $activeLang);

        $languages = ['en' => 'English', 'tl' => 'Tagalog', 'bi' => 'Bikol-Iriga'];
        $targetLangName = $languages[$activeLang] ?? 'English';

        // 1. Topic Mapping
        $topicMap = [
            'fever'        => ['fever', 'lagnat', 'kalintura', 'mafuyat'],
            'cough'        => ['cough', 'ubo'],
            'headache'     => ['headache', 'sakit ng ulo', 'matiteng'],
            'diarrhea'     => ['diarrhea', 'pagtatae', 'pagudo-udo', 'nag-aawas'], 
            'hypertension' => ['hypertension', 'high blood'],
            'vaccines'     => ['vaccines', 'vaccine', 'bakuna', 'turok'],
            'dengue'       => ['dengue'],
        ];

        $foundTopic = 'default';
        $lowerMsg = mb_strtolower($userMessage);
        foreach ($topicMap as $topic => $keywords) {
            foreach ($keywords as $word) {
                if (str_contains($lowerMsg, $word)) {
                    $foundTopic = $topic;
                    break 2;
                }
            }
        }

        // 2. File Retrieval
        $fileName = strtolower($foundTopic);
        $path = storage_path("app/health_data/{$activeLang}/{$fileName}.txt");

        // Simple check: if it doesn't exist, try the mapping name directly
        if (!file_exists($path)) {
            $path = storage_path("app/health_data/{$activeLang}/{$foundTopic}.txt");
        }

        $verifiedData = (file_exists($path)) ? file_get_contents($path) : "NOT_FOUND";

        // 3. DEFINE FOOTERS
        $footers = [
            'en' => "\n\n---\nNote: Info only. Visit the Health Center.",
            'tl' => "\n\n---\nPaunawa: Impormasyon lamang ito. Pumunta sa Health Center.",
            'bi' => "\n\n---\nPa-isi: Impormasyon sana ini. Mag duman sa Health Center."
        ];

        // 4. THE BYPASS LOGIC (Your Requested Fix)
        // If it's Bikol or Tagalog, don't even call the AI. Just return the file content.
        // 4. THE BYPASS LOGIC (For tl and bi)
if ($activeLang === 'bi' || $activeLang === 'tl') {
    $replyContent = ($verifiedData === "NOT_FOUND") 
        ? $notFoundMessages[$activeLang] // Use the friendly missing message
        : $verifiedData;

    return response()->json([
        'reply' => $replyContent . ($footers[$activeLang] ?? ''),
        'current_lang' => $activeLang
    ]);
}

        // 5. AI LOGIC (Only for English/Conversational)
     // 5. AI LOGIC (Only for English)
try {
    if ($verifiedData === "NOT_FOUND") {
        $prompt = "SYSTEM: You are LMLinga. The user asked about a health topic we don't have data for. 
                   Politely tell them in a friendly way that the info is coming soon and they should visit the clinic. The focus onlly is health related topic. Other topic not related is will diregard.";
    } else {
        $prompt = "SYSTEM: You are LMLinga, a friendly Barangay health assistant.
                   Use the following DATA to answer the user.
                   DATA: $verifiedData
                   
                   INSTRUCTION: 
                   Do NOT just copy-paste. Instead, speak directly to the user.
                   Use phrases like 'If you have a...',  'You should try to...'.
                   Make the medical advice from the DATA sound like a warm conversation.
                   
                   OUTPUT:";
    }

    $response = Http::timeout(120)->post('http://localhost:11434/api/generate', [
        'model'  => 'mistral:latest',
        'prompt' => $prompt,
        'stream' => false,
        'options' => [
            'temperature' => 0.7, // Higher temp makes English flow naturally
            'stop' => ["User:", "Assistant:", "SYSTEM:"]
        ]
    ]);
    
    // ... rest of your successful response logic

            if ($response->successful()) {
                $aiReply = $response->json()['response'];
                return response()->json([
                    'reply' => trim($aiReply) . $footers['en'],
                    'current_lang' => $activeLang
                ]);
            }
            return response()->json(['reply' => 'AI Connection Error.'], 500);
        } catch (\Exception $e) {
            return response()->json(['reply' => 'Error: ' . $e->getMessage()], 500);
        }
    }
}