<?php namespace Samubra\Training\Models;

use Model;

/**
 * Model
 */
class Course extends Model
{
    use \October\Rain\Database\Traits\Validation;
    
    /*
     * Disable timestamps by default.
     * Remove this line if timestamps are defined in the database table.
     */
    public $timestamps = false;


    /**
     * @var string The database table used by the model.
     */
    public $table = 'samubra_training_courses';

    /**
     * @var array Validation rules
     */
    public $rules = [
    ];

    public $belongsTo = [
        'teacher' => [Teacher::class,'key' => 'teacher_id']
    ];



    public function getCourseTypeOptions()
    {
        return Train::$courseTypeMap;
    }
    public function getCourseTypeText($type = null)
    {
        if(is_null($type))
            $type = $this->course_type;
        return Train::$courseTypeMap[$type];

    }
}
