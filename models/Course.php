<?php namespace Samubra\Training\Models;

use Model;

/**
 * Model
 */
class Course extends Model
{
    use \October\Rain\Database\Traits\Validation;
    protected $fillable = ['title','course_type','teacher_id','default_hours'];
    /*
     * Disable timestamps by default.
     * Remove this line if timestamps are defined in the database table.
     */
    public $timestamps = false;

    public $with = ['teacher'];

    protected $appends = [
        'course_type_text'
    ];


    /**
     * @var string The database table used by the model.
     */
    public $table = 'samubra_training_courses';

    /**
     * @var array Validation rules
     */
    public $rules = [
        'title' => 'required|min:2',
        'course_type' => 'required',
        'teacher_id' => 'required',
        'default_hours' => 'required|numeric'
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

    public function getCourseTypeTextAttribute()
    {
        return Train::$courseTypeMap[$this->course_type];
    }
}
