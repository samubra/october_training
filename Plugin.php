<?php namespace Samubra\Training;

use Samubra\Training\Repositories\Train\CertificateRepository;
use System\Classes\PluginBase;

use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\AliasLoader;
use RainLab\User\Models\User as UserModel;
use RainLab\User\Controllers\Users as UsersController;
use Event;


class Plugin extends PluginBase
{
    public $require = ['RainLab.User','RainLab.Pages'];

    public function boot()
    {
        \App::register(\Propaganistas\LaravelPhone\PhoneServiceProvider::class);
        \App::register('\Maatwebsite\Excel\ExcelServiceProvider');

        // Register aliases
        $alias = AliasLoader::getInstance();
        $alias->alias('Excel', 'Maatwebsite\Excel\Facades\Excel');

        Validator::extend('identity', function($attribute, $value, $parameters, $validator) {
            //return preg_match('/(^[1-9]\d{5}(18|19|([23]\d))\d{2}((0[1-9])|(10|11|12))(([0-2][1-9])|10|20|30|31)\d{3}[0-9Xx]$)|(^[1-9]\d{5}\d{2}((0[1-9])|(10|11|12))(([0-2][1-9])|10|20|30|31)\d{3}$)/', $value);
           $idValidator = new \Jxlwqq\IdValidator\IdValidator();
           return $idValidator->isValid($value);
        });
        $this->extendUser();



    }

    public function registerComponents()
    {
        return [
            'Samubra\Training\Components\UserCertificates' => 'userCertificates'
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

    protected function extendUser()
    {
        UserModel::extend(function($model){
            $model->hasMany['certificates'] = [\Samubra\Training\Models\Certificate::class,'key'=>'user_id'];
            $model->hasMany['certificates_count'] = [\Samubra\Training\Models\Certificate::class,'key'=>'user_id','count'=>true];
            $model->addFillable([
                'identity',
                'phone',
                'company',
                'introduce'
            ]);

            $model->rules['identity'] = ['identity','unique:users'];
            $model->rules['phone'] = ['phone:CN'];

            $model->attributeNames['identity'] = '身份证号码';
            $model->attributeNames['phone'] = '联系电话';
            $model->attributeNames['company'] = '工作单位';

            $model->addDynamicMethod('scopeExtendLoginQuery', function ($query, $credential, $value) use ($model) {
                if ($credential == 'email') {
                    $query = $query->orWhere('identity', $value);
                }
                return $query;
            });


            $model->bindEvent('model.afterCreate', function () use ($model) {
                $certificateRepository = new CertificateRepository();
                $certificateRepository->relateCertificates($model);
            });

        });

        UsersController::extendListColumns(function($list,$model){
            if(!$model instanceof UserModel)
                return ;

            $list->addColumns([
                'identity' =>[
                    'label' => '身份证号码',
                    'type' => 'text',
                    'searchable' => true,
                ],
                'phone' =>[
                    'label' => '联系电话',
                    'type' => 'text',
                    'searchable' => true,
                ],
                'company' =>[
                    'label' => '单位名称',
                    'type' => 'text',
                    'searchable' => true,
                ],
                'introduce' =>[
                    'label' => '个人介绍',
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
                ],
                'phone' => [
                    'label' => '联系电话',
                    'tab'   => '培训信息',
                    'span' => 'auto',
                    'required' => '1',
                    'type' => 'text',
                ],
                'company' => [
                    'label' => '工作单位',
                    'tab'   => '培训信息',
                    'span' => 'auto',
                    'required' => '1',
                    'type' => 'text',
                ]
            ]);
        });
    }

}
