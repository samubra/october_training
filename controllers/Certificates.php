<?php namespace Samubra\Training\Controllers;

use BackendMenu;
use Illuminate\Http\Request;
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
        'Backend.Behaviors.ImportExportController',
        ];
    
    public $listConfig = 'config_list.yaml';
    public $formConfig = 'config_form.yaml';
    public $importExportConfig = 'config_import_export.yaml';



    public function __construct()
    {
        parent::__construct();
        $this->addCss('/plugins/samubra/training/assets/backend.css');
        BackendMenu::setContext('Samubra.Training', 'plans', 'certificates');
    }
        /**
             * Called before the creation or updating form is saved.
             * @param Model
             */
    public function formBeforeSave($model)
    {
            $certificatesModel = new CertificateRepository;
            trace_sql();
            trace_log($model->id);
            $postData = post('Certificate');
        if($model->id)
            $countModel = $certificatesModel->where('id_num', $postData['id_num'])->where('id_type', $postData['id_type'])
                ->where('category_id', $postData['category'])
                ->where('active', $postData['active'])
                ->where('id', $model->id,'<>')->get();
        else
            $countModel = $certificatesModel->where('id_num', $postData['id_num'])->where('id_type', $postData['id_type'])
                ->where('category_id', $postData['category'])
                ->where('active', $postData['active'])->get();
        //trace_log($countModel->count());
        //exit();
        throw_if($countModel->count(),new ApplicationException('该证书已经被添加')
        );
    }


    /**
     * Called after the creation or updating form is saved.
     * @param Model
     */
    public function formAfterSave($model)
    {
        if(!$model->user_id){
            $userModel = new \Samubra\Training\Repositories\Train\UserRepository;
            $users = $userModel->where('name',$model->id_num)->get();
            if($users->count()){
                $model->user_id = $users->first()->id;
                //trace_log($model->user_id);
            }else{
                $model->user_id = Auth::register([
                    'name' => $model->id_num,
                    'surname' => $model->name,
                    'email' =>$model->id_num. '@tiikoo.cn',
                    'password' => substr($model->id_num, -8),
                    'password_confirmation' => substr($model->id_num, -8),
                    'username' => $model->id_num
                ],true)->id;
            }
            $model->save();
        }

    }
}
