<?php namespace Samubra\Training\Controllers;

use BackendMenu;
use Samubra\Training\Repositories\Train\CertificateRepository;
use October\Rain\Exception\ApplicationException;
use RainLab\User\Facades\Auth;

class Certificates extends TrainingController
{
    public $requiredPermissions = ['samubra.training.access_certificate'];
    public $controllerName = 'certificates';
    public $controllerTitle = '培训证书';

    use ShowActionTraits;
    public $showPreviewButton = true;

    public $implement = [
        'Backend\Behaviors\ListController',
        'Backend\Behaviors\FormController',
        ];
    
    public $listConfig = 'config_list.yaml';
    public $formConfig = 'config_form.yaml';



    public function __construct()
    {
        parent::__construct();
        $this->addCss('/plugins/samubra/training/assets/backend.css');
        BackendMenu::setContext('Samubra.Training', 'training', 'plan');
    }
        /**
     * Called before the creation or updating form is saved.
     * @param Model
     */
    public function formBeforeSave($model)
    {
        $certificatesModel = new CertificateRepository;
        //trace_sql();
        $certificatesModel->where('id_num',$model->id_num)
                    ->where('id_type',$model->id_type)
                    ->where('category_id',$model->category_id)
                    ->where('active',$model->active)
                    ->whereNull('deleted_at')
                    ->where('id',$model->id,'<>');
        throw_if($certificatesModel->get()->count(),new ApplicationException('该证书已经被添加'));
    }


    /**
     * Called after the creation or updating form is saved.
     * @param Model
     */
    public function formAfterSave($model)
    {
        if(!$model->user_id){
            $userModel = new \Samubra\Training\Repositories\Train\UserRepository;
            if($userModel->where('username',$model->id_num)->count()){
                $model->user_id = $userModel->getByColumn('username',$model->id_num,['id'])->id;
            }else{
                $model->user_id = Auth::register([
                    'name' => $model->id_num,
                    'email' =>$model->id_num. '@tiikoo.cn',
                    'password' => substr($model->id_num, -6),
                    'password_confirmation' => substr($model->id_num, -6),
                    'username' => $model->id_num
                ],true)->id;
            }
            $model->save();
        }

    }
}
