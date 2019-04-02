<?php namespace Samubra\Training\Models;

use Model;

/**
 * Model
 */
class Teacher extends Model
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
    public $table = 'samubra_training_teachers';

    /**
     * @var array Validation rules
     */
    public $rules = [
        'name' => 'required',
        'identity'  => 'identity|required'
    ];

    public $attachOne = [
        'image' => 'System\Models\File'
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
