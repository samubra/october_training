<?php namespace Samubra\Training\Controllers;

use Backend\Classes\Controller;
use BackendMenu;
use Samubra\Training\Models\Certificate;

class Records extends Controller
{
    public $implement = [
        'Backend\Behaviors\ListController',
        'Backend\Behaviors\FormController',
        'Backend.Behaviors.RelationController'
    ];
    
    public $listConfig = 'config_list.yaml';
    public $formConfig = 'config_form.yaml';
    public $relationConfig = 'config_relation.yaml';

    public function __construct()
    {
        parent::__construct();
    }


}
