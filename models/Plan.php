<?php namespace Samubra\Training\Models;

use Model;

/**
 * Model
 */
class Plan extends Model
{
    use \October\Rain\Database\Traits\Validation;
    

    /**
     * @var string The database table used by the model.
     */
    public $table = 'samubra_training_plans';

    /**
     * @var array Validation rules
     */
    public $rules = [
    ];

    public $belongsToMany = [
        'courses' => [
            Course::class,
            'table' => 'samubra_training_plan_courses' ,
            'key' => 'plan_id',
            'otherKey' => 'course_id',
            'pivot' => ['hours']
        ],
    ];
    public $hasMany = [
        'projects' => Project::class
    ];
}
