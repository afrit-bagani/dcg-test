<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentBasicInformation extends Model
{
    use HasFactory;

    protected $table = 'student_basic_information';
    protected $primaryKey = 'student_id';
    protected $guarded = [];
}
