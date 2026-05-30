<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class NewsDetectorController extends Controller
{
    // Menampilkan halaman utama website
    public function index()
    {
        return view('detector');
    }

    // Melakukan analisis teks berita via API Gemini
    public function analyze(Request $request)
    {
        $request->validate([
            'news_text' => 'required|string|min:30',
        ], [
            'news_text.required' => 'Teks berita tidak boleh kosong.',
            'news_text.min' => 'Berikan teks berita minimal 30 karakter agar analisis lebih akurat.',
        ]);

        $newsText = $request->input('news_text');
        $apiKey = env('GEMINI_API_KEY');

        if (!$apiKey) {
            return response()->json([
                'success' => false,
                'message' => 'Konfigurasi Error: API Key Gemini belum dipasang di file .env'
            ], 500);
        }

        $prompt = "
Kamu adalah AI pendeteksi hoax untuk teks berita di Indonesia.

Analisis teks berita berikut secara kritis.

Balas hanya dalam format JSON valid, tanpa markdown, tanpa ```json, tanpa teks tambahan.

Format JSON wajib:
{
  \"status\": \"VALID\" atau \"HOAX\" atau \"POTENSI_HOAX\",
  \"skor\": angka 0 sampai 100,
  \"alasan\": \"Penjelasan singkat dan logis 1-2 kalimat\",
  \"rekomendasi\": \"Saran singkat untuk pembaca\"
}

Teks berita:
{$newsText}
";

        try {
            $response = Http::timeout(60)
                ->withHeaders([
                    'Content-Type' => 'application/json',
                    'x-goog-api-key' => $apiKey,
                ])
                ->post('https://generativelanguage.googleapis.com/v1beta/models/gemini-2.5-flash:generateContent', [
                    'contents' => [
                        [
                            'parts' => [
                                [
                                    'text' => $prompt
                                ]
                            ]
                        ]
                    ],
                    'generationConfig' => [
                        'responseMimeType' => 'application/json',
                    ],
                ]);

            if (!$response->successful()) {
                Log::error('Gemini API Error', [
                    'status' => $response->status(),
                    'body' => $response->body(),
                ]);

                return response()->json([
                    'success' => false,
                    'message' => 'Gagal mendapatkan respons dari AI Server. Silakan coba beberapa saat lagi.',
                ], 502);
            }

            $result = $response->json();

            $rawJsonText = data_get($result, 'candidates.0.content.parts.0.text');

            if (!$rawJsonText) {
                Log::error('Gemini response kosong atau format tidak sesuai', [
                    'response' => $result,
                ]);

                return response()->json([
                    'success' => false,
                    'message' => 'Respons AI kosong atau format tidak sesuai. Silakan coba lagi.',
                ], 502);
            }

            // Bersihkan kalau Gemini tetap membungkus pakai markdown
            $cleanJsonText = trim($rawJsonText);
            $cleanJsonText = preg_replace('/^```json\s*/i', '', $cleanJsonText);
            $cleanJsonText = preg_replace('/^```\s*/', '', $cleanJsonText);
            $cleanJsonText = preg_replace('/\s*```$/', '', $cleanJsonText);

            $analysisData = json_decode($cleanJsonText, true);

            if (json_last_error() !== JSON_ERROR_NONE || !is_array($analysisData)) {
                Log::error('JSON AI tidak valid', [
                    'raw' => $rawJsonText,
                    'clean' => $cleanJsonText,
                    'error' => json_last_error_msg(),
                ]);

                return response()->json([
                    'success' => false,
                    'message' => 'Format respons AI tidak valid. Silakan coba lagi.',
                ], 502);
            }

            // Default supaya frontend tidak error kalau ada field yang kosong
            $analysisData = [
                'status' => $analysisData['status'] ?? 'POTENSI_HOAX',
                'skor' => $analysisData['skor'] ?? 50,
                'alasan' => $analysisData['alasan'] ?? 'AI tidak memberikan alasan yang lengkap.',
                'rekomendasi' => $analysisData['rekomendasi'] ?? 'Verifikasi ulang berita melalui sumber resmi.',
            ];

            return response()->json([
                'success' => true,
                'data' => $analysisData
            ]);

        } catch (\Exception $e) {
            Log::error('Hoax Detector Error: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan sistem internal. Silakan coba lagi nanti.'
            ], 500);
        }
    }
}