<?php namespace Samubra\Training\Controllers;

use BackendMenu;

class ProjectCourses extends TrainingController
{
    public $implement = [
        'Backend\Behaviors\ListController',
        'Backend\Behaviors\FormController'
    ];

    public $controllerName = 'projectcourses';
    public $controllerTitle = '日程安排';
    public $requiredPermissions = ['samubra.training.access_projectcourse'];

    public $listConfig = 'config_list.yaml';
    public $formConfig = 'config_form.yaml';

    public function __construct()
    {
        parent::__construct();
        $this->addCss('/plugins/samubra/training/assets/backend.css');
        BackendMenu::setContext('Samubra.Training', 'projects', 'projectcourses');
    }
}
