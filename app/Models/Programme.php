<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Programme extends Model
{
    use HasFactory;
    protected $table = 'programme_master';
    protected $primaryKey = 'programme_id';
    protected $fillable = ['code', 'name', 'is_active', 'created_by'];
}
