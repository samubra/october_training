<?php namespace Samubra\Training\Models;

use Auth;
use Lang;
use Event;
use Model;

/**
 * OrderBack Model
 */
class Order extends Model
{
    use \October\Rain\Database\Traits\Encryptable;

    /**
     * @var string The database table used by the model.
     */
    public $table = 'samubra_training_orders';

    /**
     * The attributes that should be mutated to dates.
     * @var array
     */
    protected $dates = ['date_completed', 'date_paid'];

    /**
     * @var array Guarded fields
     */
    protected $guarded = ['*'];

    /**
     * @var array Fillable fields
     */
    protected $fillable = [];

    protected $jsonable = ['billing_info', 'shipping_info','address','cart_items'];

    /**
     * The attributes that should be encrypted for arrays.
     *
     * @var array
     */
    public $encryptable = ['payment_data', 'payment_response'];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = ['payment_data'];

    /**
     * @var array Relations
     */
    public $hasOne = [];
    public $hasMany = [
        'items' => OrderItem::class
    ];
    public $belongsTo = [
        'user' => ['RainLab\User\Models\User'],
        'payment_method' => ['Samubra\Training\Models\PaymentMethod', 'order' => 'weight asc'],
        'shipping_method'  => ['Samubra\Training\Models\ShippingMethod', 'order' => 'weight asc'],
    ];
    public $belongsToMany = [];
    public $morphTo = [];
    public $morphOne = [];
    public $morphMany = [];
    public $attachOne = [];
    public $attachMany = [];

    public function beforeSave()
    {
        $user = Auth::getUser();
        if (!isset($user)) {
            $this->user_id = 0;
        }
        else {
            $this->user_id = $user['attributes']['id'];
        }
    }

    public function afterUpdate()
    {
        Event::fire('xeor.octocart.afterOrderUpdate', [$this]);
    }

    protected static function boot()
    {
        parent::boot();
        // 监听模型创建事件，在写入数据库之前触发
        static::creating(function ($model) {
            // 如果模型的 no 字段为空
            if (!$model->no) {
                // 调用 findAvailableNo 生成订单流水号
                $model->no = static::findAvailableNo();
                // 如果生成失败，则终止创建订单
                if (!$model->no) {
                    return false;
                }
            }
        });
    }
    public static function findAvailableNo()
    {
        // 订单流水号前缀
        $prefix = date('YmdHis');
        for ($i = 0; $i < 10; $i++) {
            // 随机生成 6 位的数字
            $no = $prefix.str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
            // 判断是否已经存在
            if (!static::query()->where('no', $no)->exists()) {
                return $no;
            }
        }
        \Log::warning('find order no failed');

        return false;
    }
    public function getStatusOptions($keyValue = null)
    {
        return [
            'pending'=>'待付款',
            'processing'=>'处理中',
            'on-hold'=>'待命',
            'paid'=>'已付费',
            'completed'=>'已完成',
            'cancelled'=>'已取消',
            'refunded'=>'已退款',
            'failed'=>'失败',
        ];
    }


    /**
     * Sets the "url" attribute with a URL to this object
     * @param string $pageName
     * @param Cms\Classes\Controller $controller
     */
    public function setUrl($pageName, $controller)
    {
        $params = [
            'id' => $this->id,
            'no' => $this->no,
        ];
        return $this->url = $controller->pageUrl($pageName, $params);
    }

    public function getTotal()
    {
        return (float)$this->total + (float)$this->shipping_total;
    }

    public function getSubTotal()
    {
        return $this->total;
    }

    public function getShippingTotal()
    {
        return is_null($this->shipping_total) ? 0 : $this->shipping_total;
    }

    public function getShippingTax()
    {
        return is_null($this->shipping_tax) ? 0 : $this->shipping_tax;
    }

    public function getBillingPhone()
    {
        return $this->phone;
    }

    public function getBillingEmail()
    {
        return $this->email;
    }

    public function getShippingMethodName()
    {
        $shippingMethod = $this->shipping_metod;
        return $shippingMethod ? $shippingMethod->name : '';
    }


    public function getCartItems()
    {
        return json_decode($this->cart_items, true);
    }
}
