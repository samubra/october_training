<?php

/**
 * Base controller for cart
 */

namespace Samubra\Training\Controllers;

use Auth;
use Lang;
use Flash;
use Response;
use BackendMenu;
use BackendAuth;
use Backend;
use Backend\Classes\Controller;

class TrainingController extends Controller
{
    public $implement = [
        \Backend\Behaviors\FormController::class,
        \Backend\Behaviors\ListController::class
    ];

    public $formConfig = 'config_form.yaml';
    public $listConfig = 'config_list.yaml';

    public $controllerName = '';

    public function __construct()
    {
        parent::__construct();
        //$this->addCss("/plugins/ideas/cart/assets/vendor/alertable/jquery.alertable.css", "1.0.0");
        //$this->addJs("/plugins/ideas/cart/assets/vendor/string_to_slug/speakingurl.js", "1.0.0");
        BackendMenu::setContext('Samubra.Training', 'training', 'category');
    }

    public function index()
    {
        $this->pageTitle = '列表';
        $this->vars['controller'] = $this->controllerName;
        $this->asExtension('ListController')->index();
    }

    public function create()
    {
        $this->pageTitle = '添加';
        $this->vars['controller'] = $this->controllerName;
        return $this->asExtension('FormController')->create();
    }

    public function update($recordId = null)
    {
        $this->pageTitle = '编辑';
        $this->vars['controller'] = $this->controllerName;
        return $this->asExtension('FormController')->update($recordId);
    }


}
