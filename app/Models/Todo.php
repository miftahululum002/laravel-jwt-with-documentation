<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Todo extends Model
{
    protected $fillable = ['title', 'description', 'created_by', 'updated_by'];

    public $timestamps = false;

    public function user()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
