<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HelpdeskSchedule extends Model
{
    use HasFactory;

    protected $fillable = [
      'date',
      'shift_pagi_user_id',
      'shift_sore_user_id',
      'description'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
