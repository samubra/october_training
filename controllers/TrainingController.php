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
    public $controllerTitle = '';

    public $showUpdateButton = false;
    public $showPreviewButton = false;
    public $showPrintButton = false;

    public function __construct()
    {
        parent::__construct();
        //$this->addCss("/plugins/ideas/cart/assets/vendor/alertable/jquery.alertable.css", "1.0.0");
        //$this->addJs("/plugins/ideas/cart/assets/vendor/string_to_slug/speakingurl.js", "1.0.0");
        //BackendMenu::setContext('Samubra.Training', 'training', 'setting');

        $this->vars['controller'] = $this->controllerName;
        $this->vars['controller_title'] = $this->controllerTitle;

        $this->vars['showUpdateButton'] = $this->showUpdateButton;
        $this->vars['showPreviewButton'] = $this->showPreviewButton;
        $this->vars['showPrintButton'] = $this->showPrintButton;
    }

    public function index()
    {
        $this->pageTitle = $this->controllerTitle . '列表';

        $this->asExtension('ListController')->index();
    }

    public function create()
    {
        $this->pageTitle = '添加' . $this->controllerTitle;
        return $this->asExtension('FormController')->create();
    }

    public function update($recordId = null)
    {
        $this->pageTitle = '编辑' . $this->controllerTitle;
        return $this->asExtension('FormController')->update($recordId);
    }
    public function preview($recordId = null)
    {
        $this->pageTitle = '查看' . $this->controllerTitle;
        return $this->asExtension('FormController')->preview($recordId);
    }
}
