<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Application extends Model
{
    use HasFactory;

    protected $fillable = [
      "name",
      "description",
      "status",
    ] ;
    
    public function tickets()
    {
        return $this->hasMany(Ticket::class);
    }
}
