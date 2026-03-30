<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Programme extends Model
{
    use HasFactory;

    protected $table = 'programme_master';
    protected $primaryKey = 'programme_id';
    protected $fillable = ['code', 'name', 'is_active', 'created_by'];

    /***********************************
     Relationship
     ***********************************/

    public function courses(): HasMany{
      return $this->hasMany(Course::class, 'programme_id', 'programme_id');
    }
}
