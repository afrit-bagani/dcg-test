<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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
        'course_id',
        'programme_id',
        'is_active',
        'created_by'
    ];

  /***********************************
  Relationship
   ***********************************/

  public function programme(): BelongsTo{
    return $this->belongsTo(Programme::class, 'programme_id', 'programme_id');
  }
  public function course(): BelongsTo{
    return $this->belongsTo(Course::class, 'course_id', 'course_id');
  }
}
