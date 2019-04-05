<?php namespace Samubra\Training\Models;

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
    

    /**
     * @var string The database table used by the model.
     */
    public $table = 'samubra_training_records';

    /**
     * @var array Validation rules
     */
    public $rules = [
    ];

    public $belongsTo = [
        'project' => Project::class,
        'certificate' => Certificate::class,
        'status' => Status::class,
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


    public function getDropdownOptions($fieldName, $value, $formData)
    {
        if($fieldName == 'record_edu_type')
            return Train::$eduTypeMap;
        if($fieldName == 'health_type')
            return Train::$healthTypeMap;

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
      // trace_log($this->certificate);
       $certificate = $this->certificate;
       if($certificate){
           $fields->record_name->value = $certificate->name;
           $fields->record_phone->value = $certificate->phone;
           $fields->record_address->value = $certificate->address;
           $fields->record_company->value = $certificate->company;
           $fields->record_edu_type->value = $certificate->edu_type;
       }
    }
}
