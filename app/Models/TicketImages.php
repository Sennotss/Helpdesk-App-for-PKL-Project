<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TicketImages extends Model
{
    use HasFactory;

    protected $fillable = [
      'ticket_id',
      'image_path'
    ];

    public function ticket()
    {
        return $this->belongsTo(Ticket::class);
    }
}
