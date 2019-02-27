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
        'teacher' => Teacher::class,
    ];

    const COURSE_TYPE_THEORY = 1;
    const COURSE_TYPE_OPERATE = 2;
    const COURSE_TYPE_SELF_STUDY = 3;

    static $courseTypeMap = [
        self::COURSE_TYPE_THEORY=>'理论课',
        self::COURSE_TYPE_OPERATE=>'操作课',
        self::COURSE_TYPE_SELF_STUDY =>'自学'
    ];

    public function getCourseTypeOptions()
    {
        return self::$courseTypeMap;
    }
    public function getCourseTypeText($type = null)
    {
        if(is_null($type))
            $type = $this->course_type;
        return self::$courseTypeMap[$type];

    }
}
