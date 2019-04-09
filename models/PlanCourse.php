<?php namespace Samubra\Training\Models;

use Model;

/**
 * Model
 */
class PlanCourse extends Model
{
    use \October\Rain\Database\Traits\Validation;

    public $with = ['course','course'];
    
    /*
     * Disable timestamps by default.
     * Remove this line if timestamps are defined in the database table.
     */
    public $timestamps = false;


    /**
     * @var string The database table used by the model.
     */
    public $table = 'samubra_training_plan_courses';

    protected $appends = ['plan_title','course_title'];

    /**
     * @var array Validation rules
     */
    public $rules = [
    ];

    public $belongsTo = [
        'plan' => Plan::class,
        'course' => Course::class
    ];

    public $hasMany = [
        'projectCourses' => [
            ProjectCourse::class,
            'key' => 'plan_course_id'
        ]
    ];

    public function getPlanTitleAttribute()
    {
        return $this->plan->title;
    }
    public function getCourseTitleAttribute()
    {
        return $this->course->title;
    }

    public function filterFields($fields, $context = null)
    {
        if ($course = $this->course) {
            $fields->hours->value = $course->default_hours;
        }
    }
}
