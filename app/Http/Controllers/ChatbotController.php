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
        
        // 1. Topic Mapping
        $topicMap = [
            'fever'        => ['fever', 'lagnat', 'kalintura'],
            'cough'        => ['cough', 'ubo'],
            'headache'     => ['headache', 'sakit ng ulo', 'kulog payo'],
            'diarrhea'     => ['diarrhea', 'pagtatae', 'pagudo-udo'], 
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
        if (!file_exists($path)) {
            $path = storage_path("app/health_data/{$activeLang}/{$foundTopic}.txt");
        }

        $verifiedData = (file_exists($path)) ? file_get_contents($path) : "NOT_FOUND";

        // 3. DEFINE FOOTERS
        $footers = [
            'en' => "\n\n---\nNote: Info only. Visit the Health Center.",
            'tl' => "\n\n---\nPaunawa: Impormasyon lamang ito. Pumunta sa Health Center.",
            'bi' => "\n\n---\nPa-isi: Impormasyon sana adi. Mag iyan sa Health Center."
        ];

        // 4. DYNAMIC MODEL SELECTION
        // English uses Mistral, Tagalog/Bikol use Gemma
        $modelName = 'mistral:latest'; 
        if ($activeLang === 'tl' || $activeLang === 'bi') {
            $modelName = 'gemma4:31b-cloud'; // Your specific Gemma hash/tag
        }

        // 5. AI LOGIC (Unified for all languages)
        try {
            if ($verifiedData === "NOT_FOUND") {
                $prompt = "SYSTEM: You are LMLinga. The user asked about a health topic we don't have data for. 
                           Politely tell them in a friendly way in {$languages[$activeLang]} that the info is coming soon and they should visit the clinic. 
                           Focus only on health related topics.";
            } else {
               $prompt = json_encode([
    "role" => "LMLinga - Barangay Health Assistant",

    "language" => $languages[$activeLang],

    "rules" => [
        "Use ONLY the provided DATA",
        "Do NOT use outside knowledge",
        "Do NOT guess or assume",
        "If answer is not in DATA, reply exactly: I don’t have enough information to answer that.",
        "No diagnosis",
        "Use simple words only",
        "Be friendly and conversational"
    ],

    "format" => [
        "Start with a friendly sentence",
        "Provide answer using bullet points",
        "End with a short supportive message"
    ],

    "data" => $verifiedData,

    "user_question" => $userMessage,

    "output_instruction" => "Generate the final answer following all rules strictly."
], JSON_PRETTY_PRINT);
            }

            $response = Http::timeout(120)->post('http://localhost:11434/api/generate', [
                'model'  => $modelName,
                'prompt' => $prompt,
                'stream' => false,
                'options' => [
                    'temperature' => 0.7,
                    'stop' => ["User:", "Assistant:", "SYSTEM:"]
                ]
            ]);
            
            if ($response->successful()) {
                $aiReply = $response->json()['response'];
                return response()->json([
                    'reply' => trim($aiReply) . ($footers[$activeLang] ?? ''),
                    'current_lang' => $activeLang
                ]);
            }

            return response()->json(['reply' => 'AI Connection Error.'], 500);

        } catch (\Exception $e) {
            return response()->json(['reply' => 'Error: ' . $e->getMessage()], 500);
        }
    }
}