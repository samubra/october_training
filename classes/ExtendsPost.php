<?php


namespace Samubra\Training\Classes;

use RainLab\Blog\Models\Post;
use RainLab\Blog\Controllers\Posts as PostsController;
use Samubra\Training\Models\Project;

class ExtendsPost
{

    public function __construct()
    {
        $this->extendModel();
        $this->extendController();
    }
    public function extendModel()
    {
        //pinned
        Post::extend(function($model){
            $model->belongsToMany['projects'] = [
                Project::class,
                'table' => 'samubra_training_project_posts',
                'order' => 'training_begin_date',
                'scope' => 'active'
                ];
            $model->addFillable([
                'pinned'
            ]);
        });
    }
    public function extendController()
    {
        PostsController::extendListColumns(function($list,$model){
            if(!$model instanceof Post)
                return ;
            $list->addColumns([
                'pinned' =>[
                    'label' => '置顶',
                    'type' => 'switch',
                ],
            ]);
        });
        PostsController::extendFormFields(function($form,$model,$context){
            if(!$model instanceof Post)
                return ;
            $form->addSecondaryTabFields([
                'pinned' => [
                    'label' => '置顶',
                    'tab'   => 'rainlab.blog::lang.post.tab_manage',
                    'type' => 'switch',
                ]
                ]);
        });
    }

}
