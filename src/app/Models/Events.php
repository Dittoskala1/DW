<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Events extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'start_time',
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
