<?php namespace Samubra\Training\Controllers;

use BackendMenu;
use Samubra\Training\Models\Project;
use Flash;


class Projects extends TrainingController
{
    public $implement = [
        'Backend\Behaviors\ListController',
        'Backend\Behaviors\FormController',
        'Backend.Behaviors.RelationController'
    ];

    public $controllerName = 'projects';
    public $controllerTitle = '培训项目';
    public $requiredPermissions = ['samubra.training.access_project'];

    public $listConfig = 'config_list.yaml';
    public $formConfig = 'config_form.yaml';
    public $relationConfig = 'config_relation.yaml';
    use ShowActionTraits;
    public $showPreviewButton = true;
    public function __construct()
    {

        parent::__construct();
        $this->addCss('/plugins/samubra/training/assets/backend.css');
        BackendMenu::setContext('Samubra.Training', 'projects', 'projects');
    }


    public function onCopy()
    {
        if(($checked = post('checked')) && is_array($checked))
        {
            $count = 0;
            foreach ($checked as $projectId){
                $count++;
                $oldProject = project::with('courses')->find($projectId);
                $newProject = $oldProject->replicate();
                $newProject->title = $oldProject->title . ' 副本'.$count;
                $newProject->slug = $oldProject->slug . 'copy'.$count.'rand'.rand(1,100);
                $newProject->push();
                //$newProject->save();
                if($oldProject->courses->count()){
                    foreach ($oldProject->courses as $course){
                        $newCourse = $course->replicate();
                        $newCourse->push();
                        $newCourse->project()->associate($newProject);
                        $newCourse->save();
                    }
                }
                unset($oldProject);
                unset($newProject);
            }
            Flash::success('已经为所选择的'.$count.'个培训项目创建了副本！');
        }else{
            Flash::error('请选择需要创建副本的培训项目！');
        }
        return $this->listRefresh();
    }

    public function onActive()
    {
        if (
            ($projectIds = post('checked')) &&
            is_array($projectIds)
        ) {
            Project::whereIn('id',$projectIds)->update(['active'=>true]);
            Flash::success('所选择的培训项目已设置为启用');
        }else{
            Flash::error('请选择需要启用的培训项目进行操作！');
        }
        return $this->listRefresh();
    }
    public function onNotActive()
    {
        if (
            ($projectIds = post('checked')) &&
            is_array($projectIds)
        ) {
            Project::whereIn('id',$projectIds)->update(['active'=>false]);
            Flash::success('所选择的培训项目已设置为禁用');
        }else{
            Flash::error('请选择需要禁用的培训项目进行操作！');
        }
        return $this->listRefresh();
    }
}
