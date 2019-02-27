<?php namespace Samubra\Training\Models;

use Model;

/**
 * Model
 */
class PlanCourse extends Model
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
    public $table = 'samubra_training_plan_courses';

    /**
     * @var array Validation rules
     */
    public $rules = [
    ];

    public $belongsTo = [
        'plan' => Plan::class,
        'course' => Course::class
    ];
}
