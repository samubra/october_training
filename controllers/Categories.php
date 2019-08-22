<?php namespace Samubra\Training\Controllers;

use BackendMenu;
use Samubra\Training\Models\Route;
use Event;
use Samubra\Training\Models\Train;

class Categories extends TrainingController
{
    public $requiredPermissions = ['samubra.training.access_category'];
    public $controllerName = 'categories';
    public $controllerTitle = '培训类别';

    public $implement = [
        'Backend\Behaviors\ListController',
        'Backend\Behaviors\FormController',
        'Backend\Behaviors\ReorderController'
    ];
    
    public $listConfig = 'config_list.yaml';
    public $formConfig = 'config_form.yaml';
    public $reorderConfig = 'config_reorder.yaml';

    public function __construct()
    {
        parent::__construct();
        BackendMenu::setContext('Samubra.Training', 'training', 'setting');
    }

    /**
     * Called after the creation or updating form is saved.
     * @param Model
     */
    public function formAfterSave($model)
    {
        $categorySaved = $model->attributes;
        $post = post();
        //trace_log($_POST);
        $idEdit = isset($post['id']) ? $post['id']:'0';
        $slug = $categorySaved['slug'];
        $entityId = $categorySaved['id'];
        $type = Route::ROUTE_CATEGORY;
        Route::saveRoutes($idEdit, $slug, $entityId, $type);
        Event::fire('samubra.training.after_save_category', [$categorySaved, $post]);
    }

    /*
     * Called before the creation or updating form is saved.
     * @param Model
     */
    public function formBeforeSave($model)
    {
        $post = post();

        if(!$post['Category']['slug'])
            $this->slug = Train::generateRandomString(10);
    }
}
