<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    use HasFactory;

    protected $fillable = [
        'type',
        'number_ticket',
        'status',
        'attending_in'
    ];

    protected $casts = [
        'attending_in' => 'datetime',
    ];

    public function scopeType($q, string $type)
    {
        return $q->where('type', $type);
    }
    public function scopeInWait($q)
    {
        return $q->where('status', 'wait');
    }
    public function scopeAttending($q)
    {
        return $q->where('status', 'attending');
    }
}
