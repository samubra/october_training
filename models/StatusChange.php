<?php namespace Samubra\Training\Models;

use Model;

/**
 * Model
 */
class StatusChange extends Model
{
    use \October\Rain\Database\Traits\Validation;
    

    /**
     * @var string The database table used by the model.
     */
    public $table = 'samubra_training_status_change';

    /**
     * @var array Validation rules
     */
    public $rules = [
    ];
}
