<?php namespace Samubra\Training\Models;

use Model;
use phpDocumentor\Reflection\Types\Self_;

/**
 * Model
 */
class Organization extends Model
{
    use \October\Rain\Database\Traits\Validation;
    
    /*
     * Disable timestamps by default.
     * Remove this line if timestamps are defined in the database table.
     */
    public $timestamps = false;

    const COMPLET_TYPE_GRADUATION = 1;
    const COMPLET_TYPE_TRAINING_CERTIFICATE = 2;
    const COMPLET_TYPE_OPERATIONS_CERTIFICATE = 3;


    /**
     * @var string The database table used by the model.
     */
    public $table = 'samubra_training_organizations';

    /**
     * @var array Validation rules
     */
    public $rules = [
        'name' => 'required',
        'complete_type' => 'required'
    ];

    public function getCompleteTypeOptions()
    {
        return [
            self::COMPLET_TYPE_GRADUATION => '培训结业证',
            self::COMPLET_TYPE_TRAINING_CERTIFICATE => '培训合格证',
            Self::COMPLET_TYPE_OPERATIONS_CERTIFICATE => '特种作业操作证'
        ];
    }

    public function getCompleteTypeText()
    {
        $list = $this->getCompleteTypeOptions();
        return $list[$this->complete_type];
    }

    public function getCompleteTypeTextAttribute()
    {
        return $this->getCompleteTypeText();
    }
}
