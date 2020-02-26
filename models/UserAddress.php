<?php namespace Samubra\Training\Models;

use Model;
use RainLab\User\Models\User;

/**
 * Model
 */
class UserAddress extends Model
{
    use \October\Rain\Database\Traits\Validation;


    /**
     * @var string The database table used by the model.
     */
    public $table = 'samubra_training_user_addresses';

    /**
     * @var array Validation rules
     */
    public $rules = [
    ];
    protected $fillable = [
        'province',
        'city',
        'district',
        'address',
        'zip',
        'contact_name',
        'contact_phone',
        'last_used_at',
    ];
    protected $dates = ['last_used_at'];

    public $belongsTo = [
        'user' => User::class
    ];

    public function getFullAddressAttribute()
    {
        return "{$this->province}{$this->city}{$this->district}{$this->address}";
    }
}
