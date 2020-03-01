<?php
/**
 * 添加购物车中的
 */

namespace Samubra\Training\Classes;


use Samubra\Training\Repositories\Train\OrderRepository;
use Samubra\Training\Repositories\Train\RecordRepository;
use Samubra\Training\Repositories\Train\UserAddressesRepository;
use ShoppingCart;
use Carbon\Carbon;
use Auth;
use Flash;
use DB;

class Order
{
    protected $userAddressesRepository;
    protected $orderRepository;
    protected $recordRepository;
    protected $address_id;
    public function __construct()
    {
        $this->userAddressesRepository = new UserAddressesRepository();
        $this->orderRepository = new OrderRepository();
        $this->recordRepository = new RecordRepository();
    }

    public function setAddressId($address_id)
    {
        $this->address_id = $address_id;
        return $this;
    }

    public function getCarts()
    {
        return ShoppingCart::all();
    }

    public function onAddRecordToOrder()
    {
        $user = Auth::getUser();
        $order = DB::transaction(function () use ($user) {
            $address = $this->userAddressesRepository->updateById($this->address_id,['last_used_at' => Carbon::now()->toDateString()]);
            $order   = $this->saveOrder($address);

            $totalAmount = 0;

            $carts       = $this->getCarts();

            // 遍历用户提交的 SKU
            foreach ($carts as $cart) {
                //trace_log($cart->rawId());
                $record  = $this->recordRepository->getById($cart->id);
                // 创建一个 OrderItem 并直接与当前订单关联
                $item = $order->items()->make([
                    'amount' => $cart->qty,
                    'price'  => $cart->price,
                ]);
                $item->project()->associate($record->project_id);
                $item->record()->associate($record);
                $item->save();
                $totalAmount += $item->price * $item->amount;
                //移除购物车
                ShoppingCart::remove($cart->rawId());

            }

            // 更新订单总金额
            $order->update(['total_amount' => $totalAmount]);

            // 将下单的商品从购物车中移除
            //$skuIds = collect($items)->pluck('sku_id');
            //$user->cartItems()->whereIn('product_sku_id', $skuIds)->delete();
            //ShoppingCart::clean();
            Flash::success('培训申请订单提交成功！');
            return $order;
        });
        return $order;
    }

    public function saveOrder($address)
    {
        $order   = $this->orderRepository->makeModel();
        $order   = $order->fill([
            'address'      => [ // 将地址信息放入订单中
                'address'       => $address->full_address,
                'zip'           => $address->zip,
                'contact_name'  => $address->contact_name,
                'contact_phone' => $address->contact_phone,
            ],
            //'user_id' => $user->id,
            'total_amount' => 0,
        ]);
        $order->user()->associate(Auth::getUser());
        $order->save();

        return $order;
    }
}
