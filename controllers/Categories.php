<?php namespace Samubra\Training\Controllers;

use BackendMenu;

class Categories extends TrainingController
{
    public $requiredPermissions = ['samubra.training.access_category'];
    public $controllerName = 'categories';
    public $controllerTitle = '培训类别';

    public $implement = [
        'Backend\Behaviors\ListController',
        'Backend\Behaviors\FormController',
        'Backend\Behaviors\ReorderController'
    ];
    
    public $listConfig = 'config_list.yaml';
    public $formConfig = 'config_form.yaml';
    public $reorderConfig = 'config_reorder.yaml';

    public function __construct()
    {
        parent::__construct();
        //BackendMenu::setContext('Samubra.Training', 'training', 'setting');
    }
}
