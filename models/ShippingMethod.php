<?php namespace Samubra\Training\Models;

use Event;
use Model;

/**
 * ShippingMethod Model
 */
class ShippingMethod extends Model
{
    use \October\Rain\Database\Traits\Validation;
    use \October\Rain\Database\Traits\SoftDelete;

    /**
     * The attributes that should be mutated to dates.
     * @var array
     */
    protected $dates = ['deleted_at'];

    /**
     * @var string The database table used by the model.
     */
    public $table = 'samubra_training_shipping_methods';

    /*
     * Validation
     */
    public $rules = [
        'name' => 'required',
        'code' => 'required',
    ];

    public $belongsTo = [
        'order' => ['Samubra\Training\Models\Order']
    ];

    public function getCodeOptions($value, $formData)
    {
        $options = [
            'free_shipping' => '免费送货',
            'local_pickup' => '自取',
            'flat_rate' => '手续费',
        ];

        $methods = Event::fire('samubra.training.shippingMethods');
        if (is_array($methods) && !empty($methods)) {
            foreach ($methods as $method) {
                if (!is_array($method)) {
                    continue;
                }

                foreach ($method as $code => $name) {
                    $options[$code] = $name;
                }
            }
        }

        return $options;
    }
}
