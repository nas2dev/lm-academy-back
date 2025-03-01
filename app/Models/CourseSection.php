<?php

namespace App\Models;

use App\Models\CourseModule;
use App\Models\CourseMaterial;
use Illuminate\Database\Eloquent\Model;

class CourseSection extends Model
{
    protected $fillable = [
        'module_id',
        'title', 
        'description',
        'nr_of_files',
        'duration'
    ];

    public function module() {
        return $this->belongsTo(CourseModule::class, 'module_id');
    }

    public function materials() {
        return $this->hasMany(CourseMaterial::class, 'course_section_id');
    }
}
