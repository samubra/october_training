<?php namespace Samubra\Training\Components;

use Auth;
use Cms\Classes\ComponentBase;
use Samubra\Training\Models\Settings;
use Samubra\Training\Models\Order as OrderModel;

class Orders extends ComponentBase
{

    /**
     * A collection of orders to display
     * @var Collection
     */
    public $orders;

    /**
     * Message to display when there are no products.
     * @var string
     */
    public $noOrdersMessage;

    /**
     * Reference to the page name for linking to order.
     * @var string
     */
    public $orderDisplayPage;

    public function componentDetails()
    {
        return [
            'name'        => 'samubra.training::lang.orders.name',
            'description' => 'samubra.training::lang.orders.description'
        ];
    }

    public function defineProperties()
    {
        return [
            'noOrdersMessage' => [
                'title'        => 'samubra.training::lang.orders.no_orders',
                'description'  => 'samubra.training::lang.orders.no_orders_description',
                'type'         => 'string',
                'default'      => 'No orders found',
                'showExternalParam' => false
            ]
        ];
    }

    public function onRun()
    {
        $this->prepareVars();
        $this->orders = $this->page['orders'] = $this->loadOrders();
    }

    protected function prepareVars()
    {
        $this->noOrdersMessage = $this->page['noOrdersMessage'] = $this->property('noOrdersMessage');

        /*
         * Page links
         */
        $this->orderDisplayPage = $this->page['orderDisplayPage'] = Settings::get('order_display_page', 'order');
    }

    protected function loadOrders()
    {
        $user = Auth::getUser();
        if (!isset($user)) {
            return array();
        }
        else {
            $orders = OrderModel::with('items','payment_method','shipping_method')->where('user_id', $user->id)->get();
            /*
             * Add a "url" helper attribute for linking to each category
             */
            return $orders->each(function($order) {
                $order->setUrl($this->orderDisplayPage, $this->controller);
            });
        }
    }

}
