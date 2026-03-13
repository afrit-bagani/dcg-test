<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Student extends Model
{
    use HasFactory;

    protected $table = 'student_registrations';

    protected $primaryKey = 'student_id';

    protected $fillable = [
        'reg_no',
        'name',
        'email',
        'batch_id',
        'programme_id',
        'course_id',
        'is_active',
        'created_by',
    ];

    // --- RELATIONSHIPS ---

    public function batch(): BelongsTo
    {
        return $this->belongsTo(Batch::class, 'batch_id', 'batch_id');
    }

    public function programme(): BelongsTo
    {
        return $this->belongsTo(Programme::class, 'programme_id', 'programme_id');
    }

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class, 'course_id', 'course_id');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }
}
