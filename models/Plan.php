<?php namespace Samubra\Training\Models;

use Model;
use Samubra\Train\Models\Train;

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
            'pivot' => ['hours']
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
