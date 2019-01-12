<?php namespace Samubra\Training;

use System\Classes\PluginBase;

use Illuminate\Support\Facades\Validator;
use App;
use Illuminate\Foundation\AliasLoader;
use RainLab\User\Models\User as UserModel;
use RainLab\User\Controllers\Users as UsersController;

class Plugin extends PluginBase
{
    public $require = ['RainLab.User'];

    public function boot()
    {
        Validator::extend('identity', function($attribute, $value, $parameters, $validator) {
            return preg_match('/(^[1-9]\d{5}(18|19|([23]\d))\d{2}((0[1-9])|(10|11|12))(([0-2][1-9])|10|20|30|31)\d{3}[0-9Xx]$)|(^[1-9]\d{5}\d{2}((0[1-9])|(10|11|12))(([0-2][1-9])|10|20|30|31)\d{3}$)/', $value);
        });
        Validator::extend('phone', function($attribute, $value, $parameters, $validator) {
            //return preg_match('/^13[\d]{9}$|^14[5,7]{1}\d{8}$|^15[^4]{1}\d{8}$|^17[0,6,7,8]{1}\d{8}$|^18[\d]{9}$/',$value);
            return preg_match('/^((13[0-9])|(14[5,7])|(15[0-3,5-9])|(17[0,3,5-8])|(18[0-9])|166|198|199|(147))\\d{8}$/',$value);
        });
    $this->extendUser();

    }

    public function registerComponents()
    {
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

    protected function extendUser()
    {
        UserModel::extend(function($model){
            $model->hasMany['certificates'] = [\Samubra\Training\Models\Certificate::class,'key'=>'user_id'];
            $model->hasMany['certificates_count'] = [\Samubra\Training\Models\Certificate::class,'key'=>'user_id','count'=>true];
            $model->addFillable([
                'identity'
            ]);

            $model->rules['identity'] = 'identity|unique:users';

            $model->attributeNames['identity'] = '身份证号码';
        });

        UsersController::extendListColumns(function($list,$model){
            if(!$model instanceof UserModel)
                return ;

            $list->addColumns([
                'identity' =>[
                    'label' => '身份证号码',
                    'type' => 'text',
                    'searchable' => true,
                ]
            ]);
        });
        UsersController::extendFormFields(function($form,$model,$context){

            if(!$model instanceof UserModel)
                return ;

            $form->addTabFields([
                'identity' => [
                    'label' => '身份证号码',
                    'tab'   => '培训信息',
                    'span' => 'auto',
                    'required' => '1',
                    'type' => 'text',
                ]
            ]);
        });
    }

}
