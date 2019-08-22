<?php namespace Samubra\Training\Controllers;

use BackendMenu;

class Courses extends TrainingController
{
    public $implement = [
        'Backend\Behaviors\ListController',
        'Backend\Behaviors\FormController'
    ];

    public $requiredPermissions = ['samubra.training.access_course'];
    public $controllerName = 'courses';
    public $controllerTitle = '课程';

    public $listConfig = 'config_list.yaml';
    public $formConfig = 'config_form.yaml';

    public function __construct()
    {
        parent::__construct();
        BackendMenu::setContext('Samubra.Training', 'training', 'setting');
    }
}
