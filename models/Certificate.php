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
    public function beforeCreate()
    {
            $this->num = $this->getRateRandom();
    }

    protected function getRateRandom($length = 10)
    {
        $num = Train::generateRandomString();
        if(\DB::table($this->table)->where('num',$num)->count())
            $this->getRateRandom($length);
        else
            return $num;
    }
}
