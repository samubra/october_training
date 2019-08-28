<?php
/**
 * Created by PhpStorm.
 * User: samubra
 * Date: 19-5-25
 * Time: 下午8:35
 */

namespace Samubra\Training\Models\ImportAndExport;


use Samubra\Training\Repositories\Train\CourseRepository;

class ImportCourses extends \Backend\Models\ImportModel
{
    /**
     * @var array The rules to be applied to the data.
     */
    public $rules = [
        'title' => 'required|min:2',
        'course_type' => 'required',
        'teacher_id' => 'required',
        'default_hours' => 'required|numeric'
    ];

    public function importData($results, $sessionKey = null)
    {

        foreach ($results as $row => $course){
            try {
                $courseRepository = new CourseRepository();
                $courseRepository->create($course);
                $this->logCreated();
            } catch (\Exception $ex) {
                $this->logError($row, $ex->getMessage());
            }
        }
    }

}