<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ScheduleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
      $users = \App\Models\User::all();

      // Menghasilkan data jadwal untuk beberapa tanggal
      $dates = [
          Carbon::today()->format('Y-m-d'),
          Carbon::tomorrow()->format('Y-m-d'),
          Carbon::now()->addDays(2)->format('Y-m-d')
      ];

      foreach ($dates as $date) {
          // Memilih pengguna secara acak untuk shift pagi dan sore
          $shiftPagiUser = $users->random();
          $shiftSoreUser = $users->random();

          // Memasukkan data jadwal
          DB::table('helpdesk_schedules')->insert([
              'date' => $date,
              'shift_pagi_user_id' => $shiftPagiUser->id,
              'shift_sore_user_id' => $shiftSoreUser->id,
              'description' => 'Catatan untuk jadwal ' . $date,
              'created_at' => now(),
              'updated_at' => now(),
          ]);
        }
    }
}
