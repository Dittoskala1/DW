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
        'status',
        'category_id',
        'organizer_id',
        'location_id',
        'audience_id',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function organizer()
    {
        return $this->belongsTo(Organizer::class);
    }

    public function location()
    {
        return $this->belongsTo(Location::class);
    }

    public function audience()
    {
        return $this->belongsTo(Audience::class);
    }

}
