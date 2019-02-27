<?php namespace Samubra\Training\Models;

use Model;
use RainLab\User\Models\User;
use Samubra\Train\Models\Train;

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
        'user' => User::class,
        'category' => Category::class,
        'organization' => Organization::class
    ];
    public function getDropdownOptions($fieldName, $value, $formData)
    {
        if($fieldName == 'edu_type')
            return Train::$eduTypeMap;

        if($fieldName == 'active')
            return [
                Train::NO => '无效',
                Train::YES => '有效'
            ];

        return [
            Train::NO => '否',
            Train::YES => '是'
        ];
    }
}
