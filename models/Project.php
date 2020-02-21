<?php namespace Samubra\Training\Models;

use Model;
use RainLab\Blog\Models\Post;
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

    protected $fillable = ['slug','title','active','training_status_id','training_begin_date','training_end_date','plan_exam_date','cost','condition','plan_id','remark'];

    /**
     * @var array Validation rules
     */
    public $rules = [
        'slug' => 'required',
        'title' => 'required|min:3',
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
        'records' => Record::class,
        'records_count' => [Record::class,'count' => true],
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
        ],
        'posts' => [
            Post::class,
            'table' => 'samubra_training_project_posts',
            'order' => 'created_at'
        ],
        'certificates_count' => [
            Certificate::class,
            'table' => 'samubra_training_records',
            'key' => 'certificate_id',
            'other_key' => 'project_id',
            'pivot' => [
                'num','record_status_id','record_edu_type','health_type','theory_score','operate_score','is_eligible'
            ],
            'count' => true
        ],
    ];

    public $morphToMany = [
        'status_change' => [
            Status::class,
            'name' => 'entity',
            'table' => 'samubra_training_status_change',
            'timestamps' => true,
        ],
    ];


    public function scopeActive($query)
    {
        return $query->where('active',true);
    }

    public function getDropdownOptions($fieldName, $value, $formData)
    {
        if($fieldName == 'is_valid')
            return [
                Train::YES => '已复审',
                Train::NO => '未复审'
            ];
        return [
            Train::ENABLE => '启用',
            Train::DISABLE => '停用',
        ];
    }
}
