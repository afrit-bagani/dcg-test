<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use HasFactory;
    protected $table = 'course_master';
    protected $primaryKey  = 'course_id';
    protected $fillable = ['code', 'name', 'is_active', 'created_by'];
}
