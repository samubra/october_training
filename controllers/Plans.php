<?php namespace Samubra\Training\Controllers;

use BackendMenu;
use Samubra\Training\Models\Plan;
use Flash;

class Plans extends TrainingController
{
    public $implement = [
        'Backend\Behaviors\ListController',
        'Backend\Behaviors\FormController',
        'Backend.Behaviors.RelationController',
        ];
    public $controllerName = 'plans';
    public $controllerTitle = '培训方案';
    public $requiredPermissions = ['samubra.training.access_plan'];

    public $listConfig = 'config_list.yaml';
    public $formConfig = 'config_form.yaml';
    public $relationConfig = 'config_relation.yaml';
    use ShowActionTraits;
    public $showPreviewButton = true;
    public function __construct()
    {
        parent::__construct();
        BackendMenu::setContext('Samubra.Training', 'plans', 'plans');
    }

    public function onCopy()
    {
        if(($checked = post('checked')) && is_array($checked))
        {
            $count = 0;
            foreach ($checked as $planId){
                $count++;
               $oldPlan = Plan::with('courses')->find($planId);
               $newPlan = $oldPlan->replicate();
               $newPlan->title = $oldPlan->title . ' 副本'.$count;
               $newPlan->push();
               //$newPlan->save();
               if($oldPlan->courses->count()){
                   foreach ($oldPlan->courses as $course){
                       $newPlan->courses()->attach($course);
                   }
               }

                unset($oldPlan);
                unset($newPlan);
            }
            Flash::success('已经为所选择的'.$count.'个培训方案创建了副本！');
        }else{
            Flash::error('请选择需要复制的培训方案！');
        }
        return $this->listRefresh();
    }
}
