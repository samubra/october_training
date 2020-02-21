<?php


namespace Samubra\Training\Classes;

use RainLab\Blog\Models\Post;
use Samubra\Training\Models\Project;

class ExtendsPost
{

    public function __construct()
    {
        $this->extendModel();
    }
    public function extendModel()
    {
        Post::extend(function($model){
            $model->belongsToMany['projects'] = [
                Project::class,
                'table' => 'samubra_training_project_posts',
                'order' => 'training_begin_date',
                'scope' => 'active'
                ];
        });
    }

}
