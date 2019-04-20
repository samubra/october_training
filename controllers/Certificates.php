<?php namespace Samubra\Training\Controllers;

use BackendMenu;

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
}
