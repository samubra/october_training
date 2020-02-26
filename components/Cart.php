<?php


namespace Samubra\Training\Components;


use Cms\Classes\ComponentBase;
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

class Cart extends ComponentBase
{
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
        $this->page['cartList'] = ShoppingCart::all();
        $this->page['total'] = ShoppingCart::total();
    }

    public function onLoadCartList()
    {
        return ;
    }


}
