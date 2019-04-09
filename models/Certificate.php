<?php namespace Samubra\Training\Models;

use Model;
use RainLab\User\Models\User;
use Samubra\Training\Models\Traits\CreateNumTrait;

/**
 * Model
 */
class Certificate extends Model
{
    use \October\Rain\Database\Traits\Validation;
    use CreateNumTrait;
    

    /**
     * @var string The database table used by the model.
     */
    public $table = 'samubra_training_certificates';

    /**
     * @var array Validation rules
     */
    public $rules = [
        'id_num' => 'required|identity',
        'id_type' => 'required',
        'name' => 'required',
        'phone' => 'required|phone:CN',
        'edu_type' => 'required',
        'category_id' => 'required',
        'organization_id' => 'required',
        'first_get_date' => 'date',
        'print_date' => 'date|after_or_equal:first_get_date',
        'review_date' => 'date|after:print_date',
        'invalid_date' => 'date|after:review_date',
        'active' => 'boolean'
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
        if($fieldName == 'id_type')
            return Train::$idTypeMap;

        return [
            Train::NO => '否',
            Train::YES => '是'
        ];
    }
}
