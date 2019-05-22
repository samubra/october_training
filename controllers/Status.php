<?php namespace Samubra\Training\Controllers;

use BackendMenu;

class Status extends TrainingController
{
    public $implement = [
        'Backend\Behaviors\ListController',
        'Backend\Behaviors\FormController'
    ];
    public $controllerName = 'status';
    public $requiredPermissions = ['samubra.training.access_status'];
    public $controllerTitle = '状态';
    
    public $listConfig = 'config_list.yaml';
    public $formConfig = 'config_form.yaml';

    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        parent::index();
        $this->addCss('/plugins/samubra/training/assets/backend.css');
        //BackendMenu::setContext('Samubra.Training', 'training', 'setting');
    }

}
