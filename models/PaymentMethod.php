<?php namespace Samubra\Training\Models;

use Lang;
use Event;
use Model;

/**
 * PaymentMethod Model
 */
class PaymentMethod extends Model
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
    public $table = 'samubra_training_payment_methods';

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
            'cod' => '货到付款',
            'cop' => '付款发货',
        ];

        $methods = Event::fire('samubra.training.paymentMethods');
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
