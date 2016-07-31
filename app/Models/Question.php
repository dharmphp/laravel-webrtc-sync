<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    protected $fillable = ['presentation_id', 'slide_index', 'question_text', 'is_active'];

    public function options()
    {
        return $this->hasMany(Option::class);
    }
}
