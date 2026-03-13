<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    use HasFactory;
    protected $table = 'subject_master';
    protected $primaryKey = 'subject_id';
    protected $fillable = [
        'code',
        'name',
        'internal_full_marks',
        'internal_pass_marks',
        'theory_full_marks',
        'theory_pass_marks',
        'practical_full_marks',
        'practical_pass_marks',
        'is_active',
        'created_by'
    ];
}
