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
    protected $fillable = ['name','complete_type'];

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
        return Train::$completeTypeMap;
    }

    public function getCompleteTypeText()
    {
        return Train::$completeTypeMap[$this->complete_type];
    }

    public function getCompleteTypeTextAttribute()
    {
        return $this->getCompleteTypeText();
    }
}
