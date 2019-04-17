<?php namespace Samubra\Training\Models;

use October\Rain\Database\Pivot;
use Samubra\Training\Models\Traits\PlanCourseTraits;
/**
 * Model
 */
class PlanCoursePivot extends Pivot
{
    use \October\Rain\Database\Traits\Validation;
    use PlanCourseTraits;

    public $with = ['course','course'];
    public $timestamps = false;

    public $table = 'samubra_training_plan_courses';


    protected $appends = ['plan_title','course_title'];

    protected $jsonable = ['teaching_form'];

    protected $casts = [
        'teaching_form' => 'array',
    ];
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
}
