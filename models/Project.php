<?php namespace Samubra\Training\Models;

use Model;
use Samubra\Training\Models\Traits\SaveStatusId;

/**
 * Model
 */
class Project extends Model
{
    use \October\Rain\Database\Traits\Validation;

    use SaveStatusId;

    public $status_filed = 'training_status_id';
    

    /**
     * @var string The database table used by the model.
     */
    public $table = 'samubra_training_projects';

    /**
     * @var array Validation rules
     */
    public $rules = [
        'slug' => 'required',
        'title' => 'required|min3',
        'training_begin_date' => 'date',
        'training_end_date' => 'date|after:training_begin_date',
        'plan_exam_date' => 'date|after:training_end_date',
        'cost' => 'numeric',
        'plan_id' => 'required'
    ];

    protected $jsonable = ['condition'];
    protected $casts = [
        'active' => 'boolean'
    ];

    public $hasMany = [
        'courses' => ProjectCourse::class,
        'records' => Record::class
    ];

    public $belongsTo = [
        'plan' => Plan::class,
        'status' => [Status::class,'key' => 'training_status_id']
    ];

    public $belongsToMany = [
        'certificates' => [
            Certificate::class,
            'table' => 'samubra_training_records',
            'key' => 'certificate_id',
            'other_key' => 'project_id',
            'pivot' => [
                'num','record_status_id','record_edu_type','health_type','theory_score','operate_score','is_eligible'
            ]
        ]
    ];

    public $morphToMany = [
        'status_change' => [
            Status::class,
            'name' => 'entity',
            'table' => 'samubra_training_status_change',
            'timestamps' => true,
        ],
    ];


    public function getDropdownOptions($fieldName, $value, $formData)
    {
        if($fieldName == 'is_valid')
            return [
                Train::YES => '有效',
                Train::NO => '无效'
            ];
        return [
            Train::ENABLE => '启用',
            Train::DISABLE => '停用',
        ];
    }
}
