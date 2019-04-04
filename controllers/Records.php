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

    public function filterFields($fields, $context = null)
    {
        trace_log($fields);
        //trace_log($this->certifiacte->name);
        if ($this->certifiacte) {
            $fields->record_name->hidden = true;
        }
        /**
        $certificate = Certificate::find($this->certifiacte);
        if (str_contains($displayedVendors, 'provider1')) {
            $fields->{'specificfields[for][provider1]'}->hidden = false;
        }
        if (str_contains($displayedVendors, 'provider2')) {
            $fields->{'specificfields[for][provider2]'}->hidden = false;
        }
         * **/
    }
}
