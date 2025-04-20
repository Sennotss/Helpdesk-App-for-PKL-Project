<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Models\Ticket;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class TelegramWebhookController extends Controller
{
    public function handle(Request $request)
    {
        // Ambil data dari pesan Telegram
        $message = $request->input('message.text') ?? '';
        $chat_id = $request->input('message.chat.id');
        $user_id = $request->input('message.from.id');

        // Log data yang masuk untuk pengecekan
        Log::info('Pesan Telegram Masuk:', [
            'raw_message' => $message,
            'chat_id' => $chat_id,
            'user_id' => $user_id,
        ]);

        // Regex untuk menangkap data dari pesan
        preg_match('/Client\/Project:\s*(.*?)(?=\n|$)/i', $message, $clientMatch);
        preg_match('/Isu\/Kendala:\s*(.*?)(?=\n|$)/i', $message, $issueMatch);
        preg_match('/Keterangan:\s*(.*?)(?=\n|$)/i', $message, $descriptionMatch);

        // Jika format pesan valid, coba simpan tiket
        if ($clientMatch && $issueMatch && $descriptionMatch) {
            try {
                // Simpan tiket ke database
                $ticket = Ticket::create([
                    'client' => trim($clientMatch[1]),
                    'issue' => trim($issueMatch[1]),
                    'description' => trim($descriptionMatch[1]),
                    'pelapor_id' => $user_id,
                ]);

                $this->sendTelegramMessage($chat_id, "Tiket berhasil dibuat dengan ID: " . $ticket->ticket_code . " Tunggu sebentar yaa, akan segera tim kami follow up");

                Log::info('Tiket berhasil dibuat:', ['ticket' => $ticket]);

                return response()->json(['status' => 'success', 'ticket_id' => $ticket->id]);
            } catch (\Exception $e) {
                Log::error('Gagal menyimpan tiket:', ['error' => $e->getMessage()]);

                $this->sendTelegramMessage($chat_id, "Terjadi kesalahan saat membuat tiket: " . $e->getMessage());

                return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
            }
        }
        $this->sendTelegramMessage($chat_id, "Format pesan tidak valid. Pastikan formatnya benar.");
        return response()->json(['status' => 'invalid format'], 400);
    }

    private function sendTelegramMessage($chat_id, $message)
    {
        $token = config('services.telegram.bot_token');
        $url = "https://api.telegram.org/bot$token/sendMessage";

        $response = Http::get($url, [
            'chat_id' => $chat_id,
            'text' => $message,
        ]);

        if (!$response->successful()) {
            Log::error('Gagal mengirim pesan Telegram', [
                'chat_id' => $chat_id,
                'message' => $message,
                'response' => $response->body(),
            ]);
        }
    }
}
