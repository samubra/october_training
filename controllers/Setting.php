<?php namespace Samubra\Training\Controllers;

use BackendMenu;
use Flash;
use View;
use Backend\Classes\Controller;


class Setting extends Controller
{
    public $controllerName = 'setting';


    public $implement = [
        \Backend\Behaviors\ListController::class
    ];

    public $listConfig = 'config_list.yaml';

    public function __construct()
    {
        parent::__construct();
        //add style
        $this->addCss("/plugins/samubra/training/assets/css/styles.css", "1.0.0");

        BackendMenu::setContext('Samubra.Training', 'training', 'setting');
    }

    public function index()
    {

        $this->pageTitle = '报名管理';
        $this->asExtension('ListController')->index();
    }

    public function dfg()
    {
        echo 'dfg';
    }


}
