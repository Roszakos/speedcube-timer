<?php

namespace App\Models;

use App\Models\Solve;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Session extends Model
{
    use HasFactory;

    const PUZZLE_TYPE_2X2 = '2x2';
    const PUZZLE_TYPE_3X3 = '3x3';
    const PUZZLE_TYPE_4X4 = '4x4';
    const PUZZLE_TYPE_5X5 = '5x5';
    const PUZZLE_TYPE_6X6 = '6x6';
    const PUZZLE_TYPE_7X7 = '7x7';

    const UPDATED_AT = null;
    const CREATED_AT = null;

    protected $fillable = ['user_id', 'hash', 'puzzle', 'start_date'];


    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function solves()
    {
        return $this->hasMany(Solve::class);
    }
}
