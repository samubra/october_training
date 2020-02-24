<?php namespace Samubra\Training;

use Samubra\Training\Classes\ExtendsPost;
use Samubra\Training\Classes\ExtendsUser;
use Samubra\Training\Repositories\Train\CertificateRepository;
use System\Classes\PluginBase;

use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\AliasLoader;
use RainLab\User\Models\User as UserModel;
use RainLab\User\Controllers\Users as UsersController;
use Event;


class Plugin extends PluginBase
{
    public $require = ['RainLab.User','RainLab.Pages','RainLab.Blog'];

    public function boot()
    {
        \App::register(\Propaganistas\LaravelPhone\PhoneServiceProvider::class);
        \App::register(\Gloudemans\Shoppingcart\ShoppingcartServiceProvider::class);
        \App::register('\Maatwebsite\Excel\ExcelServiceProvider');

        // Register aliases
        $alias = AliasLoader::getInstance();
        $alias->alias('Excel', 'Maatwebsite\Excel\Facades\Excel');
        $alias->alias('Cart',  \Gloudemans\Shoppingcart\Facades\Cart::class);

        Validator::extend('identity', function($attribute, $value, $parameters, $validator) {
            //return preg_match('/(^[1-9]\d{5}(18|19|([23]\d))\d{2}((0[1-9])|(10|11|12))(([0-2][1-9])|10|20|30|31)\d{3}[0-9Xx]$)|(^[1-9]\d{5}\d{2}((0[1-9])|(10|11|12))(([0-2][1-9])|10|20|30|31)\d{3}$)/', $value);
           $idValidator = new \Jxlwqq\IdValidator\IdValidator();
           return $idValidator->isValid($value);
        });

        //扩展user的model和controller
        new ExtendsUser();

        new ExtendsPost();

    }

    public function registerComponents()
    {
        return [
            'Samubra\Training\Components\UserCertificates' => 'userCertificates',
            'Samubra\Training\Components\ProjectDetails' => 'projectDetails',
            'Samubra\Training\Components\AddRecord' => 'addRecord'
        ];
    }

    public function registerSettings()
    {
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
            'status'    => '状态'
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
        return $arrayAccess;
    }


}
