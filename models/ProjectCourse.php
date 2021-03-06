<?php namespace Samubra\Training\Models;

use Carbon\Carbon;
use Model;

/**
 * Model
 */
class ProjectCourse extends Model
{
    use \October\Rain\Database\Traits\Validation;
    //protected $with = ['plan_course','plan_course.course','project','teacher'];

    /*
     * Disable timestamps by default.
     * Remove this line if timestamps are defined in the database table.
     */
    public $timestamps = false;


    /**
     * @var string The database table used by the model.
     */
    public $table = 'samubra_training_project_courses';

    protected $fillable = ['plan_course_id','project_id','teacher_id','course_id','course_time_start','course_time_end','hours'];


    /**
     * @var array Validation rules
     */
    public $rules = [
        'plan_course_id' => 'required',
        'project_id' => 'required',
        'teacher_id' => 'required',
        'course_time_start' => 'required|date_format:Y-m-d H:i:s',
        'course_time_end' => 'required|date_format:Y-m-d H:i:s',
        'hours' => 'required|numeric'
    ];

    public $belongsTo = [
        'plan_course' => [
            PlanCourse::class,
            'key' => 'plan_course_id',
        ],
        'project' => Project::class,
        'teacher' => Teacher::class,
        'course' => Course::class,
    ];

    public function beforeSave()
    {
        $this->course_id = $this->plan_course->course_id;
    }

    public function filterFields($fields)
    {
        //race_log($fields);
        // trace_log($this->certificate);
        if($planCourse = $this->plan_course){
            $fields->hours->value = $planCourse->hours;
            $fields->teacher->value = $planCourse->course->teacher->id;
        }
        if($project = $this->project){
            $fields->course_time_start->value = $project->training_begin_date;
            $days = intval($this->hours / 8); //舍去小数
            //traceLog($days);
            //traceLog($project->training_begin_date);
            $fields->course_time_end->value = Carbon::createFromFormat('Y-m-d',$project->training_begin_date)->addDays($days)->toDateString();
        }
        if($this->hours && $this->course_time_start)
        {
            $days = intval($this->hours / 8); //舍去小数
            //raceLog($this->course_time_start);
            $fields->course_time_end->value = Carbon::createFromFormat('Y-m-d H:i:s',$this->course_time_start)->addDays($days)->toDateString();

        }
    }
}
