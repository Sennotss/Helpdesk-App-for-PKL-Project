<?php

namespace App\Helpers;

use App\Models\HelpdeskSchedule;
use App\Models\User;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class TelegramHelper
{
  public static function sendMessage($chat_id, $message)
  {
    $token = config('services.telegram.bot_token');
    $url = "https://api.telegram.org/bot$token/sendMessage";

    $response = Http::get($url, [
        'chat_id' => $chat_id,
        'text' => $message,
    ]);

    \Log::info('Response from Telegram API: ' . $response->body());

    if (!$response->successful()) {
        Log::error('Gagal mengirim pesan Telegram', [
            'chat_id' => $chat_id,
            'message' => $message,
            'response' => $response->body(),
        ]);
    }
  }

  public static function sendTodaySchedule()
  {
    try {
      $today = date('Y-m-d');
      $jadwal = HelpdeskSchedule::where('date', $today )->first();

      if (!$jadwal) {
          self::sendMessage(config('services.telegram.chat_id'), "Tidak ada jadwal helpdesk hari ini.");
          return;
      }

      $shiftPagi = $jadwal->shift_pagi_user_id
          ? User::find($jadwal->shift_pagi_user_id)->name
          : 'Belum ditentukan';

      $shiftSore = $jadwal->shift_sore_user_id
          ? User::find($jadwal->shift_sore_user_id)->name
          : 'Belum ditentukan';

      $message = "Jadwal Helpdesk Hari Ini ({$today})\n\n";
      $message .= "Shift Pagi: {$shiftPagi}\n";
      $message .= "Shift Sore: {$shiftSore}";

      self::sendMessage(config('services.telegram.chat_id'), $message);

    } catch (\Throwable $e) {
        \Log::error("Error schedule helpdesk: " . $e->getMessage());
    }
  }

}

?>
