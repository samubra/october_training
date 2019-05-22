<?php namespace Samubra\Training\Controllers;

use BackendMenu;

class Plans extends TrainingController
{
    public $implement = [
        'Backend\Behaviors\ListController',
        'Backend\Behaviors\FormController',
        'Backend.Behaviors.RelationController',
        ];
    public $controllerName = 'plans';
    public $controllerTitle = '培训方案';
    public $requiredPermissions = ['samubra.training.access_plan'];
    
    public $listConfig = 'config_list.yaml';
    public $formConfig = 'config_form.yaml';
    public $relationConfig = 'config_relation.yaml';
    use ShowActionTraits;
    public $showPreviewButton = true;
    public function __construct()
    {
        parent::__construct();
        //BackendMenu::setContext('Samubra.Training', 'training', 'plan');
    }
}
