<?php


namespace Samubra\Training\Components;


use Cms\Classes\ComponentBase;
use Samubra\Training\Models\Order;
use Samubra\Training\Models\UserAddress;
use Samubra\Training\Repositories\Train\OrderRepository;
use Samubra\Training\Repositories\Train\UserAddressesRepository;
use ShoppingCart;
use Samubra\Training\Classes\CheckRecord;
use Samubra\Training\Models\Record;
use Samubra\Training\Models\Train;
use Samubra\Training\Repositories\Train\CertificateRepository;
use Samubra\Training\Repositories\Train\ProjectRepository;
use Flash;
use Samubra\Training\Repositories\Train\RecordRepository;
use Validator;
use ValidationException;
use SystemException;
use ApplicationException;
use Auth;
use Log;
use DB;
use Carbon\Carbon;

class Cart extends ComponentBase
{

    protected $carts;
    /**
     * @return array
     */
    public function componentDetails()
    {
        return [
            'name'        => '购物车',
            'description' => '添加购物车功能',
        ];
    }

    public function onRun()
    {
       $this->carts = $this->page['cartList'] = ShoppingCart::all();
        $this->page['total'] = ShoppingCart::total();
        $this->page['countRows'] = ShoppingCart::countRows();
        $this->page['count'] = ShoppingCart::count($totalItems = true);
        //trace_log($this->page['cartList']);

    }

    public function onLoadCartList()
    {
        return ;
    }

    public function onAddToOrder()
    {
        $order = new \Samubra\Training\Classes\Order();
        return [
            '#cart_list' => $this->renderPartial('pages-cart/order_result',['order' => $order->setAddressId(post('address_id'))->onAddRecordToOrder()])
        ];
    }




}
