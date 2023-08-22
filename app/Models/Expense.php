<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Expense extends Model
{
    use HasFactory;

    protected $fillable = ['vendor', 'region', 'amount', 'status'];

    public function users(): BelongsTo {
        return $this->belongsTo(User::class);
    }
}
