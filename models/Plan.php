<?php namespace Samubra\Training\Models;

use Model;
use Samubra\Training\Models\Traits\CustomValidateMessage;

/**
 * Model
 */
class Plan extends Model
{
    use \October\Rain\Database\Traits\Validation;

    use CustomValidateMessage;
    

    /**
     * @var string The database table used by the model.
     */
    public $table = 'samubra_training_plans';

    public $with = ['category'];

    protected $fillable = ['title','category_id','organization_id','is_retraining','is_certificate','operate_hours','theroy_hours','training_address','contact_phone','contact_person','target','material','document','other'];

    /**
     * @var array Validation rules
     */
    public $rules = [
        'title' => 'required|min:3',
        'category_id' => 'required',
        'organization_id' => 'required_if:is_certificate,true',
        'operate_hours' => 'numeric',
        'theroy_hours' => 'numeric',
        'contact_phone' => 'required|phone:CN',
    ];

    protected $appends= [
        'is_retraining_text'
    ];

    protected $casts = [
        'is_retraining' => 'boolean',
        'is_certificate' => 'boolean',
    ];

    protected $jsonable = [
        'document','material'
    ];

    public $belongsToMany = [
        'courses' => [
            Course::class,
            'table' => 'samubra_training_plan_courses' ,
            'key' => 'plan_id',
            'otherKey' => 'course_id',
            'pivot' => ['hours','teaching_form'],
            'pivotModel' => PlanCoursePivot::class
        ],
    ];
    public $hasMany = [
        'projects' => Project::class
    ];
    public $belongsTo = [
        'category' => [
            Category::class
        ],
        'organization' => [
            Organization::class
        ]
    ];

    public function getDropdownOptions($fieldName, $value, $formData)
    {
        if($fieldName == 'is_retraining') {
            return [
                Train::NO => '新训',
                Train::YES => '复审'
            ];
        }

        if($fieldName == 'provide_type') {
            return [
                '学员提供' => '学员提供',
                '现场填写' => '现场填写'
            ];
        }

        return [
            Train::NO => '否',
            Train::YES => '是'
        ];

    }

    public function getIsRetrainingTxt()
    {
        $list = $this->getDropdownOptions('is_retraining','','');

        return $list[$this->is_retraining];
    }

}
