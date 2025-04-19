<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TicketLinks extends Model
{
    use HasFactory;

    protected $fillable = [
      'ticket_id',
      'url'
    ];

    public function ticket()
    {
        return $this->belongsTo(Ticket::class);
    }
}
