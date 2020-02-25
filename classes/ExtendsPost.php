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
                ]
            ]);
        });
        PostsController::extend(function($controller) {

            $controller->addCss('/plugins/samubra/training/assets/backend.css');
            // Implement behavior if not already implemented
            if (!$controller->isClassExtendedWith('Backend.Behaviors.RelationController')) {
                $controller->implement[] = 'Backend.Behaviors.RelationController';
            }

            // Define property if not already defined
            if (!isset($controller->relationConfig)) {
                $controller->addDynamicProperty('relationConfig');
            }

            // Splice in configuration safely
            $myConfigPath = '$/samubra/training/controllers/projects/post_config_relation.yaml';

            $controller->relationConfig = $controller->mergeConfig(
                $controller->relationConfig,
                $myConfigPath
            );
        });

            PostsController::extendFormFields(function($form,$model,$context){
            if(!$model instanceof Post)
                return ;
            $form->addSecondaryTabFields([
                'pinned' => [
                    'label' => '置顶',
                    'tab'   => 'rainlab.blog::lang.post.tab_manage',
                    'type' => 'switch',
                ],
                'projects' =>[
                    'tab'   => '培训项目',
                    'type' => 'partial',
                    'path' => '$/samubra/training/controllers/projects/list/_projects.htm'
                ]
            ]);

        });
    }

}
