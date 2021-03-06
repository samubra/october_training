<?php
/**
 * Created by PhpStorm.
 * User: samubra
 * Date: 19-5-25
 * Time: 下午8:37
 */

namespace Samubra\Training\Models\ImportAndExport;


use Samubra\Training\Models\Course;
use Samubra\Training\Models\Train;
use Samubra\Training\Repositories\Train\CourseRepository;
use Samubra\Training\Repositories\Train\TeacherRepository;

class ExportCourses extends \Backend\Models\ExportModel
{
    protected $appends = ['export_type','export_by_course_type','export_by_teacher','course_type','teacher_name'];
    protected $fillable = [
        'export_type','export_by_course_type','export_by_teacher'
    ];
    public function exportData($columns, $sessionKey = null)
    {
        $courseModel = $this->getCourseRepository()->makeModel();



        $courses = $courseModel->export($this->setConditions())->get();
        $courses->each(function($course) use ($columns) {
            if($course->course_type)
                $course->course_type_text = Train::$courseTypeMap[$course->course_type];
            if($course->teacher_id)
                $course->teacher_name = $course->teacher->name;
            $course->addVisible($columns);
            $course->addVisible(['course_type_text','teacher_name']);
        });
        return $courses->toArray();
    }

    protected function setConditions()
    {
        $condition = [];
        $export_type = $this->export_type;
        if(in_array($export_type,['by_auto','by_course_type']) && $this->export_by_course_type)
            $condition['courseType'] = $this->export_by_course_type;

        if(in_array($export_type,['by_auto','by_teacher']) && $this->export_by_teacher)
            $condition['teacher'] = $this->export_by_teacher;
        return $condition;
    }

    protected function exportExtendColumns($columns)
    {
        $columns['course_type_text'] = '课程类型';
        $columns['teacher_name'] = '授课教师';
        return parent::exportExtendColumns($columns); // TODO: Change the autogenerated stub
    }

    public function getExportByCourseTypeOptions()
    {
        return Train::$courseTypeMap;
    }

    protected function getCourseRepository()
    {
        return new CourseRepository();
    }
    public function getExportByTeacherOptions()
    {
        $teacherModel = new TeacherRepository();
        return $teacherModel->all()->pluck('name','id')->toArray();
    }
}