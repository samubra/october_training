<?php namespace Samubra\Training\Controllers;

use BackendMenu;

class Organizations extends TrainingController
{
    public $implement = [
        'Backend\Behaviors\ListController',
        'Backend\Behaviors\FormController'
    ];
    public $controllerName = 'organizations';
    public $controllerTitle = '发证机构';
    public $requiredPermissions = ['samubra.training.access_organization'];
    
    public $listConfig = 'config_list.yaml';
    public $formConfig = 'config_form.yaml';

    public function __construct()
    {
        parent::__construct();
        //BackendMenu::setContext('Samubra.Training', 'training', 'setting');
    }
}
