<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Course extends Model
{
    use HasFactory;

    protected $table = 'course_master';

    protected $primaryKey = 'course_id';

    protected $fillable = ['code', 'name', 'is_active', 'programme_id', 'created_by'];

   /***********************************
    Relationship
   ***********************************/

    public function programme(): BelongsTo
    {
        return $this->belongsTo(Programme::class, 'programme_id', 'programme_id');
    }

    public function subjects(): HasMany
    {
      return $this->hasMany(Subject::class, 'course_id', 'course_id');
    }
}
