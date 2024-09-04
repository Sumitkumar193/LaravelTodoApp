<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Http\Requests\TodoRequest;

class Todo extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'completed'];
}
