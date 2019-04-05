<?php namespace Samubra\Training\Controllers;

use Backend\Classes\Controller;
use BackendMenu;

class ProjectCourses extends Controller
{
    public $implement = [        'Backend\Behaviors\ListController',        'Backend\Behaviors\FormController'    ];
    
    public $listConfig = 'config_list.yaml';
    public $formConfig = 'config_form.yaml';

    public function __construct()
    {
        parent::__construct();
        $this->addCss('/plugins/samubra/training/assets/backend.css');
    }
}
