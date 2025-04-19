<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    use HasFactory;

    protected $fillable = [
      "problem_id",
      "application_id",
      "user_id",
      "client",
      "issue",
      "description",
      "status",
      "assigned_to",
      "priority",
      "via",
    ] ;

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($ticket) {
            $latest = self::latest()->first();
            $number = $latest && preg_match('/#CMP(\d+)/', $latest->ticket_code, $matches)
                ? intval($matches[1]) + 1
                : 1;

            $ticket->ticket_code = '#CMP' . str_pad($number, 5, '0', STR_PAD_LEFT);
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function assigned()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function problem()
    {
        return $this->belongsTo(Problem::class);
    }

    public function application()
    {
        return $this->belongsTo(Application::class);
    }

    public function images()
    {
        return $this->hasMany(TicketImages::class);
    }

    public function links()
    {
        return $this->hasMany(TicketLinks::class);
    }

}
