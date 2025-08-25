<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'company_name',
        'phone',
    ];
    public function users()
    {
        return $this->hasMany(User::class); // (1,N)

    }
    public function tasks()
    {
        return $this->hasMany(Task::class); // (1,N)
    }
}
