<?php namespace Samubra\Training\Controllers;

use BackendMenu;

class Projects extends TrainingController
{
    public $implement = [
        'Backend\Behaviors\ListController',
        'Backend\Behaviors\FormController',
        'Backend.Behaviors.RelationController'
    ];

    public $controllerName = 'projects';
    public $controllerTitle = '培训项目';
    public $requiredPermissions = ['samubra.training.access_project'];
    
    public $listConfig = 'config_list.yaml';
    public $formConfig = 'config_form.yaml';
    public $relationConfig = 'config_relation.yaml';

    public function __construct()
    {

        parent::__construct();
        $this->addCss('/plugins/samubra/training/assets/backend.css');
        BackendMenu::setContext('Samubra.Training', 'training', 'project');
    }
}
