<?php namespace Samubra\Training\Models;

use Model;
use Samubra\Training\Models\Traits\PlanCourseTraits;

/**
 * Model
 */
class PlanCourse extends Model
{
    use \October\Rain\Database\Traits\Validation;

    use PlanCourseTraits;

    public $with = ['course','course'];

    protected $fillable = ['plan_id','course_id','hours','teaching_form'];
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
