<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Card extends Model
{
    use HasFactory;
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'title',
        'description',
        'row_id',
        'position',
    ];

    /**
     * Get the row that owns the card.
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function row()
    {
        return $this->belongsTo(Row::class);
    }
}
