<?php namespace Samubra\Training\Models;

use Model;

/**
 * Model
 */
class Project extends Model
{
    use \October\Rain\Database\Traits\Validation;
    

    /**
     * @var string The database table used by the model.
     */
    public $table = 'samubra_training_projects';

    /**
     * @var array Validation rules
     */
    public $rules = [
    ];

    public $hasMany = [
        'courses' => ProjectCourse::class,
        'records' => Record::class
    ];

    public $morphToMany = [
        'status_change' => [
            Status::class,
            'name' => 'entity',
            'table' => 'samubra_training_status_change',
        ],
    ];
}
