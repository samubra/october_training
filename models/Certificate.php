<?php namespace Samubra\Training\Models;

use Model;
use RainLab\User\Models\User;

/**
 * Model
 */
class Certificate extends Model
{
    use \October\Rain\Database\Traits\Validation;
    

    /**
     * @var string The database table used by the model.
     */
    public $table = 'samubra_training_certificates';

    /**
     * @var array Validation rules
     */
    public $rules = [

    ];

    public $belongsTo = [
        'user' => User::class
    ];
}
