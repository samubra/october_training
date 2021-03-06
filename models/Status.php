<?php namespace Samubra\Training\Models;

use Model;

/**
 * Model
 */
class Status extends Model
{
    use \October\Rain\Database\Traits\Validation;
    
    /*
     * Disable timestamps by default.
     * Remove this line if timestamps are defined in the database table.
     */
    public $timestamps = false;


    /**
     * @var string The database table used by the model.
     */
    public $table = 'samubra_training_status';

    /**
     * @var array Validation rules
     */
    public $rules = [
        'name' => 'required|min:3',
        'color' => 'required',
    ];

    protected $fillable = ['name','color','type','sort'];

    const STATUS_RECORD = 1;
    CONST STATUS_PROJECT = 2;


    public static $statusTypeMap = [
        self::STATUS_RECORD => '申请受理状态',
        self::STATUS_PROJECT => '培训过程状态',
    ];

    public function getDropdownOptions($fieldName, $value, $formData)
    {
        return self::$statusTypeMap;
    }

    public function getTypeText()
    {
        $list = self::$statusTypeMap;

        return $list[$this->type];
    }


    public function scopeProject($query)
    {
        return $query->whereType(self::STATUS_PROJECT);
    }
    public function scopeRecord($query)
    {
        return $query->whereType(self::STATUS_RECORD);
    }
}
