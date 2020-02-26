<?php


namespace Samubra\Training\Classes;

use RainLab\User\Models\User as UserModel;
use RainLab\User\Controllers\Users as UsersController;
use Samubra\Training\Repositories\Train\CertificateRepository;

class ExtendsUser
{

    public function __construct()
    {
        $this->extendModel();
        $this->extendController();
    }
    public function extendModel()
    {
        UserModel::extend(function($model){
            $model->hasMany['certificates'] = [\Samubra\Training\Models\Certificate::class,'key'=>'user_id'];
            $model->hasMany['certificates_count'] = [\Samubra\Training\Models\Certificate::class,'key'=>'user_id','count'=>true];
            $model->hasMany['addresses'] = [\Samubra\Training\Models\UserAddress::class];
            $model->addFillable([
                'identity',
                'phone',
                'company',
                'introduce'
            ]);

            $model->rules['identity'] = ['nullable','identity','unique:users'];
            $model->rules['avatar'] = ['nullable','mimes:jpeg','dimensions:min_width=100,min_height=200'];
            $model->rules['phone'] = ['nullable','phone:CN,mobile'];

            $model->attributeNames['identity'] = '身份证号码';
            $model->attributeNames['phone'] = '联系电话';
            $model->attributeNames['company'] = '工作单位';


            $model->bindEvent('model.afterCreate', function () use ($model) {
                $certificateRepository = new CertificateRepository();
                $certificateRepository->relateCertificates($model);
            });
        });
    }

    public function extendController()
    {
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
