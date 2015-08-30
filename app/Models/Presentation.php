<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Presentation extends Model
{
    protected $table = 'presentations';
    protected $fillable = [
        'title',
        'speaker_name',
        'access_code',
        'current_slide_index',
        'status'
    ];

    public function slides()
    {
        return $this->hasMany(Slide::class)->orderBy('order', 'asc');
    }
}
