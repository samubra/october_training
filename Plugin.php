<?php namespace Samubra\Training;

use Samubra\Training\Repositories\Train\CertificateRepository;
use System\Classes\PluginBase;

use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\AliasLoader;
use Lovata\Buddies\Models\User as UserModel;
use Lovata\Buddies\Controllers\Users as UsersController;
use Event;


class Plugin extends PluginBase
{
    public $require = ['RainLab.User'];

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
            'Samubra\Training\Components\RelateCertificate' => 'relateCertificate',
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
                'identity'
            ]);

            $model->rules['identity'] = ['identity','unique:lovata_buddies_users'];
           // $model->rules['phone'] = ['phone:CN'];

            $model->attributeNames['identity'] = '身份证号码';
           // $model->attributeNames['phone'] = '联系电话';

            $model->addDynamicMethod('scopeExtendLoginQuery', function ($query, $credential, $value) use ($model) {
                if ($credential == 'email') {
                    $query = $query->orWhere('identity', $value);
                }
                return $query;
            });

            $model->bindEvent('model.afterCreate', function () use ($model) {
                $certificateRepository = new CertificateRepository();
                $certificateModels = $certificateRepository->with('category')->where('id_num',$model->identity)
                    ->whereNull('user_id')->get();
                $certificateModels->each(function($item) use($model){
                    $item->user_id = $model->id;
                    $item->save();
                    trace_log($model->identity . '已关联证书'.$item->category->name);
                });

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
