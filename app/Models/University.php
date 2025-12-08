<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class University extends Model
{
    /** @use HasFactory<\Database\Factories\UniversityFactory> */
    use HasFactory;

    protected $fillable = [
        'name',
        'collagename',
        'is_deleted',
    ];
    public function allyears()
    {
        return $this->hasMany(Year::class);
    }
    public function years()
    {
        return $this->hasMany(Year::class, 'university_id')->where('is_deleted', '0');
    }
    public function salepoints()
    {
        return $this->hasMany(SalesPoint::class);
    }
    public function students()
    {
        return $this->hasMany(Student::class);
    }
    public function allteachers()
    {
        return $this->hasMany(Teacher::class);
    }
    public function teachers()
    {
        return $this->hasMany(Teacher::class);
    }
    public function seasons()
    {
        return $this->hasMany(Season::class)->where('is_deleted', '0');
    }
    public function noteification()
    {
        return $this->hasMany( TeacherNotification::class);
    }
}
