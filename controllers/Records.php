<?php namespace Samubra\Training\Controllers;

use BackendMenu;
use Carbon\Carbon;
use Samubra\Training\Models\Train;
use Samubra\Training\Repositories\Train\RecordRepository;
use View;
use Backend;

class Records extends TrainingController
{
    public $implement = [
        'Backend\Behaviors\ListController',
        'Backend\Behaviors\FormController',
        'Backend.Behaviors.RelationController'
    ];

    public $controllerName = 'records';
    public $requiredPermissions = ['samubra.training.access_record'];
    public $controllerTitle = '申请记录';
    
    public $listConfig = 'config_list.yaml';
    public $formConfig = 'config_form.yaml';
    public $relationConfig = 'config_relation.yaml';
    use ShowActionTraits;
    public $showPreviewButton = true;
    public $showPrintButton = true;
    public function __construct()
    {

        parent::__construct();
        $this->addCss('/plugins/samubra/training/assets/backend.css');
        BackendMenu::setContext('Samubra.Training', 'projects', 'records');
    }

    public function print($recordId = null)
    {
        //$this->addJs('/plugins/samubra/training/assets/js/jQuery.print.min.js');
        $this->addJs('/plugins/samubra/training/assets/js/jquery.jqprint-0.3.js');
        $this->addCss('/plugins/samubra/training/assets/css/jquery.jqprint.css',['media','print']);
        //$this->addCss('/plugins/samubra/training/assets/css/print.css',['media','print']);
        //$this->addCss('/plugins/samubra/training/assets/css/print.css');


        $this->vars['controller'] = $this->controllerName;
        $this->pageTitle = '打印申请表格';
        $data['url'] = Backend::url('samubra/training/'.$this->controllerName);

        $repository = new RecordRepository();
        $record = $repository->with('certificate','certificate.user','certificate.category','certificate.category.parent','project','project.plan')->getById($recordId);
        $data['user_sex'] = (int)substr($record->record_id_num,-2,1)% 2 === 0 ? '女' : '男';

        $data['date_now'] = Carbon::now(new \DateTimeZone('Asia/Chongqing'))->format('Y年n月j日');

        if($record->project->plan->is_retraining){
            $printDate = Carbon::createFromFormat('Y-m-d',$record->certificate->print_date);
            $data['print_date'] = $printDate->format('Y年m月d日');
            $data['print_end'] = $printDate->addYears(6)->format('Y年n月j日');
        }
        $data['start_date'] = Carbon::createFromFormat('Y-m-d',$record->project->training_begin_date)->format('Y年n月j日');
        $data['end_date'] = Carbon::createFromFormat('Y-m-d',$record->project->training_end_date)->format('Y年n月j日');
        $data['hours'] = $record->project->plan->theroy_hours + $record->project->plan->operate_hours;
        $data['edu_type'] = Train::$eduTypeMap[$record->record_edu_type];

        $data['record'] = $record;
        return View::make('samubra.training::project.print',$data);
    }


}
