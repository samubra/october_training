<?php namespace Samubra\Training\Models;

use Model;
use October\Rain\Database\Traits\SoftDelete;
use October\Rain\Exception\AjaxException;
use October\Rain\Exception\ApplicationException;
use October\Rain\Support\Facades\Flash;
use RainLab\User\Models\User;
use Samubra\Training\Models\Traits\CreateNumTrait;
use Samubra\Training\Models\Traits\CustomValidateMessage;

/**
 * Model
 */
class Certificate extends Model
{
    use \October\Rain\Database\Traits\Validation;
    use CreateNumTrait;
    use SoftDelete;
    use CustomValidateMessage;
    

    /**
     * @var string The database table used by the model.
     */
    public $table = 'samubra_training_certificates';

    protected $filldable = [
        'num','id_num','id_type','name','phone','address','company','edu_type','category_id','organization_id','first_get_date','print_date','review_date','invalid_date','active','user_id'
    ];

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

    public $hasMany = [
        'records' => Record::class
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


    public function listIdTypes()
    {
        return Train::$idTypeMap;
    }
}
