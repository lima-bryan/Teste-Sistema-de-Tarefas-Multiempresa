<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;
    protected $fillable  = [
        'company_id',
        'user_id',
        'title',
        'description',
        'status',
        'due_date',
        'completed_at',
        'cancelled_at',
        'priority'
    ];
    protected $casts = [
        'due_date' => 'datetime',
        'completed_at' => 'datetime',
        'cancelled_at' => 'datetime',
    ];

    public function company()
    {
        return $this->belongsTo(Company::class); // (1,1)
    }

    public function user()
    {
        return $this->belongsTo(User::class); // (1,1)
    }
    public function taskComments()
    {
        return $this->hasMany(TaskComment::class); // (1,N)
    }
}
