<?php namespace Samubra\Training;

use Samubra\Training\Classes\CheckAddRecord;
use Samubra\Training\Classes\ExtendsPost;
use Samubra\Training\Classes\ExtendsUser;
use Samubra\Training\Repositories\Train\CertificateRepository;
use System\Classes\PluginBase;

use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\AliasLoader;
use RainLab\User\Models\User as UserModel;
use RainLab\User\Controllers\Users as UsersController;
use Event;
use Request;
use Lang;



class Plugin extends PluginBase
{
    public $require = ['RainLab.User','RainLab.Pages','RainLab.Blog'];

    public function boot()
    {

        Validator::extend('identity', function($attribute, $value, $parameters, $validator) {
            //return preg_match('/(^[1-9]\d{5}(18|19|([23]\d))\d{2}((0[1-9])|(10|11|12))(([0-2][1-9])|10|20|30|31)\d{3}[0-9Xx]$)|(^[1-9]\d{5}\d{2}((0[1-9])|(10|11|12))(([0-2][1-9])|10|20|30|31)\d{3}$)/', $value);
           $idValidator = new \Jxlwqq\IdValidator\IdValidator();
           return $idValidator->isValid($value);
        });
        Validator::extend('record', function($attribute, $value, $parameters, $validator) {
            $check = new CheckAddRecord();
            return $check->setCertificateModel($value)->setProjectModel($parameters['0'])->check();
        });

        //扩展user的model和controller
        new ExtendsUser();

        new ExtendsPost();

    }


    public function registerFormWidgets()
    {
        return [
            'Samubra\Training\FormWidgets\Variation' => [
                'label' => 'Variation',
                'code'  => 'variation'
            ]
        ];
    }
    public function register()
    {

        \App::register(\Propaganistas\LaravelPhone\PhoneServiceProvider::class);
        \App::register(\Overtrue\LaravelShoppingCart\ServiceProvider::class);
        \App::register('\Maatwebsite\Excel\ExcelServiceProvider');

        // Register aliases
        $alias = AliasLoader::getInstance();
        $alias->alias('Excel', 'Maatwebsite\Excel\Facades\Excel');
        $alias->alias('ShoppingCart', \Overtrue\LaravelShoppingCart\Facade::class);

        $alias->alias('OctoCart', 'OctoCart\OctoCart\Facades\Cart');

        \App::singleton('cart', function () {
            return new \OctoCart\OctoCart\Cart;
        });
    }

    public function registerComponents()
    {
        return [
            'Samubra\Training\Components\UserCertificates' => 'userCertificates',
            'Samubra\Training\Components\ProjectDetails' => 'projectDetails',
            'Samubra\Training\Components\Record' => 'addRecord',
            'Samubra\Training\Components\UserAddresses' => 'userAddresses',
            //'Samubra\Training\Components\Cartback' => 'cart',
            'Samubra\Training\Components\AddAttach' => 'addAttach',

            'Samubra\Training\Components\Cart' => 'cart',
            'Samubra\Training\Components\Orders' => 'orders',
            'Samubra\Training\Components\Checkout' => 'checkout',
           //'Samubra\Training\Components\Products' => 'products',
            'Samubra\Training\Components\Categories' => 'categories',
            'Samubra\Training\Components\Order' => 'order',
        ];
    }

    /**
     * Registers mail templates for this plugin.
     *
     * @return array
     */
    public function registerMailTemplates()
    {
        return [
            'samubra.training::mail.order_confirm_admin' => 'OctoCart Admin email.',
            'samubra.training::mail.order_confirm' => 'OctoCart User email.'
        ];
    }
    /**
     * Registers back-end settings for this plugin.
     *
     * @return array
     */
    public function registerSettings()
    {
        return [
            'settings' => [
                'label' => 'samubra.training::lang.settings.menu_label',
                'description' => 'samubra.training::lang.settings.menu_description',
                'category' => 'samubra.training::lang.plugin.name',
                'icon' => 'icon-cog',
                'class' => 'Samubra\Training\Models\Settings',
                'order' => 200,
                'keywords'    => 'training settings'
            ],
            'shipping' => [
                'label' => 'samubra.training::lang.shipping.menu_label',
                'description' => 'samubra.training::lang.shipping.menu_description',
                'category' => 'samubra.training::lang.plugin.name',
                'icon' => 'icon-truck',
                'url' => \Backend::url('samubra/training/shipping'),
                'order' => 300,
                'keywords' => 'training shipping methods'
            ],
            'payments' => [
                'label' => 'samubra.training::lang.payments.menu_label',
                'description' => 'samubra.training::lang.payments.menu_description',
                'category' => 'samubra.training::lang.plugin.name',
                'icon' => 'icon-credit-card',
                'url' => \Backend::url('samubra/training/payments'),
                'order' => 400,
                'keywords' => 'training payments methods'
            ],
        ];
    }

    public function registerPermissions()
    {
        $controller = [
            'category'  => '分类',
            'teacher'   => '教师',
            'course'    => '课程',
            'plancourse'    => '授课方案',
            'projectcourse'    => '日程安排',
            'plan'      => '培训方案',
            'project'   => '培训项目',
            'certificate'   => '证书',
            'record'    => '培训记录',
            'status'    => '状态',
        ];
        $arrayAccess = [];
        foreach ($controller as $key => $row) {
            $arrayAccess['samubra.training.access_'.$key] = [
                'tab' => '培训报名管理',
                'label' => $row . '管理'
            ];
        }
        foreach ($controller as $row) {
            $arrayAccess['samubra.training.delete_'.$row] = [
                'tab' => '培训报名管理',
                'label' => '删除'.$row
            ];
        }
        $arrayAccess['samubra.training.access_orders'] = [
            'tab' => 'samubra.training::lang.access.tab',
            'label' => 'samubra.training::lang.access.orders'
        ];
        return $arrayAccess;
    }


    public function registerMarkupTags()
    {
        return [
            'filters' => [
                'price' => [$this, 'priceFilter'],
                'query' => function($text) {
                    $queryString = Request::server('QUERY_STRING');
                    if ($queryString) {
                        $queryString = '?' . $queryString;
                    }
                    return $text . $queryString;
                }
            ],
            'functions' => [
            ]
        ];
    }
    public function registerListColumnTypes()
    {
        return [
            'status' => function ($value) {
                switch ($value) {
                    case 'instock':
                        $value = Lang::get('samubra.training::lang.product.instock');
                        break;
                    case 'outofstock':
                        $value = Lang::get('samubra.training::lang.product.outofstock');
                        break;
                }
                return $value;
            },
        ];
    }

    public function priceFilter($number)
    {
        return currency_format($number);
    }
}
