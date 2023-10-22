<?php

namespace App\Models;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Solve extends Model
{
    const PUZZLE_TYPE_2X2 = '2x2';
    const PUZZLE_TYPE_3X3 = '3x3';
    const PUZZLE_TYPE_4X4 = '4x4';
    const PUZZLE_TYPE_5X5 = '5x5';
    const PUZZLE_TYPE_6X6 = '6x6';
    const PUZZLE_TYPE_7X7 = '7x7';


    protected $fillable = ['session_id', 'hash', 'time', 'plus2', 'dnf', 'scramble'];



    use HasFactory;
}