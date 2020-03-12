<?php namespace Samubra\Training\Models;

use Model;

/**
 * Model
 */
class OrderItem extends Model
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
    public $table = 'samubra_training_order_items';

    /**
     * @var array Validation rules
     */
    public $rules = [
    ];

    protected $fillable = ['amount', 'price', 'rating', 'review', 'reviewed_at'];
    protected $dates = ['reviewed_at'];

    public $belongsTo = [
        'project' => Project::class,
        'record' => Record::class,
        'order' => OrderBack::class
    ];
}
