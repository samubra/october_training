<?php namespace Samubra\Training\Models;

use Model;
use Samubra\Training\Models\Traits\CustomValidateMessage;

/**
 * Model
 */
class Teacher extends Model
{
    use \October\Rain\Database\Traits\Validation;
    use CustomValidateMessage;
    
    /*
     * Disable timestamps by default.
     * Remove this line if timestamps are defined in the database table.
     */
    public $timestamps = false;

    //protected $with = ['courses','project_courses','courses_count','project_courses_count'];


    /**
     * @var string The database table used by the model.
     */
    public $table = 'samubra_training_teachers';

    /**
     * @var array Validation rules
     */
    public $rules = [
        'name' => 'required|min:2',
        'identity'  => 'identity|required',
        'phone' => 'phone:CN',
        'company' => 'required',
    ];

    public $attachOne = [
        'image' => 'System\Models\File'
    ];

    public $hasMany = [
        'courses' => [
            Course::class,
            'key' => 'teacher_id'
        ],
        'project_courses' => [
            ProjectCourse::class,
            'key' => 'teacher_id'
        ],
        'courses_count' => [
            Course::class,
            'key' => 'teacher_id',
            'count' => true
        ],
        'project_courses_count' => [
            ProjectCourse::class,
            'key' => 'teacher_id',
            'count' => true
        ],
    ];

    public function getJobTitleOptions()
    {
        return Train::$jobTitleMap;
    }

    public function getJobTitleText()
    {
        return Train::$jobTitleMap[$this->job_title];
    }

    public function getEduTypeOptions()
    {
        return Train::$eduTypeMap;
    }

    public function getEduTypeText()
    {
        return Train::$eduTypeMap[$this->edu_type];
    }
}
