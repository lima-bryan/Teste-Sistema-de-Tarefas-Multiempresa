<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TaskComment extends Model
{
    use HasFactory;
    protected $table = 'task_comments'; //criei só por precaução

    protected $fillable = [
        'task_id',
        'user_id',
        'comment',
    ];

    public function task()
    {
        return $this->belongsTo(Task::class); // (1,1)
    }
    public function user()
    {
        return $this->belongsTo(User::class); // (1,1)
    }
}
