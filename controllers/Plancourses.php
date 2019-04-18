<?php namespace Samubra\Training\Controllers;

use BackendMenu;

class Plancourses extends TrainingController
{
    public $controllerName = 'plancourses';
    public $controllerTitle = '授课计划';
    public $requiredPermissions = ['samubra.training.access_plancourse'];

    public $implement = [
        'Backend\Behaviors\ListController',
        'Backend\Behaviors\FormController',
    ];

    public $listConfig = 'config_list.yaml';
    public $formConfig = 'config_form.yaml';

    public $showPreviewButton = true;

    use ShowActionTraits;
    
    public function __construct()
    {
        parent::__construct();
        BackendMenu::setContext('Samubra.Training', 'training', 'plan');
    }
}
