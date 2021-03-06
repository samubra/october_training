<?php namespace Samubra\Training\Controllers;

use BackendMenu;
use Samubra\Training\Models\Train;

class Teachers extends TrainingController
{
    public $implement = [
        'Backend\Behaviors\ListController',
        'Backend\Behaviors\FormController',
        'Backend.Behaviors.ImportExportController',
    ];

    public $controllerName = 'teachers';
    public $requiredPermissions = ['samubra.training.access_teacher'];

    public $importExportConfig = 'config_import_export.yaml';

    public $controllerTitle = '教师';
    
    public $listConfig = 'config_list.yaml';
    public $formConfig = 'config_form.yaml';
    use ShowActionTraits;
    public $showPreviewButton = true;
    public function __construct()
    {
        parent::__construct();
        BackendMenu::setContext('Samubra.Training', 'setting','teachers');
    }

    /**
     * Assign for form field '$fields' when create
     */
    public function formExtendFields($formWidget, $fields)
    {
        if ($this->action == 'create') {
            $fields['edu_type']->value = Train::EDU_TYPE_JUNIOR_HIGH_SCHOOL;
            $fields['job_title']->value = Train::JOB_TITLE_ELEMENTARY;
            return $fields;
        }
    }
}
