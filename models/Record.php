<?php namespace Samubra\Training\Models;

use Illuminate\Validation\Rule;
use Model;
use Samubra\Training\Models\Traits\CreateNumTrait;
use Samubra\Training\Models\Traits\SaveStatusId;

/**
 * Model
 */
class Record extends Model
{
    use \October\Rain\Database\Traits\Validation;
    use CreateNumTrait;
    use SaveStatusId;

    public $status_filed = 'record_status_id';
    protected $fillable = ['num','record_status_id','record_edu_type','health_type','certificate_id','project_id','theory_score','operate_score','is_eligible','record_name','record_phone','record_address','record_company','record_id_num','record_id_type'];


    /**
     * @var string The database table used by the model.
     */
    public $table = 'samubra_training_records';

    /**
     * @var array Validation rules
     */
    public $rules = [
        'record_edu_type' => 'required' ,
        'is_eligible' => 'boolean' ,
        'record_name' => 'required' ,
        'record_phone' => 'required|phone:CN' ,
        'record_address' => 'required',
        'record_company' => 'required'
    ];
    public $attributeNames = [
        'certificate_id' => '培训证书',
        'project_id' => '培训项目'
    ];

    public $customMessages = [
        'record_id_num.unique' => '当前培训项目已存在该培训记录',
        //'record_id_num.identity' => '身份证号码格式错误'
    ];
    protected $casts = [
        'is_eligible' => 'boolean',
    ];

    protected $appends = [
        'edu_type_text'
    ];

    public $belongsTo = [
        'project' => Project::class,
        'certificate' => Certificate::class,
        'status' => [Status::class,'key' => 'record_status_id'],
    ];

    //$related, $name, $table = null, $foreignPivotKey = null,
    //                                $relatedPivotKey = null, $parentKey = null,
    //                                $relatedKey = null, $inverse = false
    public $morphToMany = [
        'status_change' => [
            Status::class,
            'name' => 'entity',
            'table' => 'samubra_training_status_change',
            'timestamps' => true,
        ],
    ];

    public function getEduTypeTextAttribute()
    {
        return Train::$eduTypeMap[$this->record_edu_type];
    }

    public function beforeValidate()
    {
        if($this->project->plan->is_certificate)
        {
            $this->rules['certificate_id'] = ['required'];
        }
        $recordIdNum = ['required','identity'];

        $recordIdNum[] = Rule::unique($this->table,'record_id_num')->where('project_id', $this->project_id);

        $this->rules['record_id_num'] = $recordIdNum;
    }
    public function getDropdownOptions($fieldName, $value, $formData)
    {
        if($fieldName == 'record_edu_type')
            return Train::$eduTypeMap;
        if($fieldName == 'health_type')
            return Train::$healthTypeMap;
        if($fieldName == 'record_id_type')
            return Train::$idTypeMap;

        if($fieldName == 'is_eligible')
            return [
                Train::NO => '不合格',
                Train::YES => '合格'
            ];
        return [
            Train::NO => '否',
            Train::YES => '是'
        ];
    }

   public function filterFields($fields)
   {
       //race_log($fields);
       //trace_log($this->id);
       $certificate = $this->certificate;
       if($certificate){
           $fields->record_id_num->value = $certificate->id_num;
           $fields->record_id_type->value = $certificate->id_type;
           $fields->record_name->value = ($certificate->id_num == $this->record_id_num && $this->record_name) ? $this->record_name :  $certificate->name;
           $fields->record_phone->value = ($certificate->id_num == $this->record_id_num && $this->record_phone) ? $this->record_phone : $certificate->phone;
           $fields->record_address->value = ($certificate->id_num == $this->record_id_num && $this->record_address) ? $this->record_address : $certificate->address;
           $fields->record_company->value = ($certificate->id_num == $this->record_id_num && $this->record_company) ? $this->record_company : $certificate->company;
           $fields->record_edu_type->value = ($certificate->id_num == $this->record_id_num && $this->record_edu_type) ? $this->record_edu_type : $certificate->edu_type;
           $fields->record_id_num->readOnly = true;
           $fields->record_id_type->readOnly = true;

       }
    }

    public function scopeIsAccept($query , $status_id = null)
    {
        $status_id = $status_id ? $status_id:6;

        return $query->where('record_status_id',$status_id);
    }
}
