<?php namespace Samubra\Training\Controllers;

use Backend\Classes\Controller;
use BackendMenu;
use Samubra\Training\Models\Training;

class Teachers extends TrainingController
{
    public $implement = [
        'Backend\Behaviors\ListController',
        'Backend\Behaviors\FormController'
    ];

    public $controllerName = 'teachers';
    
    public $listConfig = 'config_list.yaml';
    public $formConfig = 'config_form.yaml';

    public function __construct()
    {
        parent::__construct();
        BackendMenu::setContext('Samubra.Training', 'training', 'setting');
    }

    /**
     * Assign for form field '$fields' when create
     */
    public function formExtendFields($formWidget, $fields)
    {
        if ($this->action == 'create') {
            $fields['edu_type']->value = Training::EDU_TYPE_JUNIOR_HIGH_SCHOOL;
            $fields['job_title']->value = Training::JOB_TITLE_ELEMENTARY;
            return $fields;
        }
    }
}
