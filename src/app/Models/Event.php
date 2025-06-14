<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'start_date',
        'start_time',
        'end_date',
        'end_time',
        'location',
        'status',
        'category_id',
        'poster_url',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
