<?php namespace Samubra\Training\Models;

use Model;

/**
 * Model
 */
class Record extends Model
{
    use \October\Rain\Database\Traits\Validation;
    

    /**
     * @var string The database table used by the model.
     */
    public $table = 'samubra_training_categories';

    /**
     * @var array Validation rules
     */
    public $rules = [
    ];

    public $belongsTo = [
        'project' => Project::class,
        'certificate' => Certificate::class,
        'status' => Status::class,
    ];

    //$related, $name, $table = null, $foreignPivotKey = null,
    //                                $relatedPivotKey = null, $parentKey = null,
    //                                $relatedKey = null, $inverse = false
    public $morphToMany = [
        'status_change' => [
            Status::class,
            'name' => 'entity',
            'table' => 'samubra_training_status_change',
        ],
    ];
}
