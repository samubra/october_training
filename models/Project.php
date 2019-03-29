<?php namespace Samubra\Training\Models;

use Model;

/**
 * Model
 */
class Project extends Model
{
    use \October\Rain\Database\Traits\Validation;
    

    /**
     * @var string The database table used by the model.
     */
    public $table = 'samubra_training_projects';

    /**
     * @var array Validation rules
     */
    public $rules = [
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
        'plan' => Plan::class
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

    public function beforeSave()
    {
        $lastStatus = $this->status_change->sortByDesc('updated_at');
        $this->training_status_id = $lastStatus->first()->id;
        trace_log($this->training_status_id);
    }
}
