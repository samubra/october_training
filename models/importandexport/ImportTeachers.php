<?php
/**
 * Created by PhpStorm.
 * User: samubra
 * Date: 19-5-25
 * Time: 下午8:35
 */

namespace Samubra\Training\Models\ImportAndExport;


use Samubra\Training\Repositories\Train\TeacherRepository;
use October\Rain\Support\Collection;

class ImportTeachers extends \Backend\Models\ImportModel
{
    /**
     * @var array The rules to be applied to the data.
     */
    public $rules = [
        //'name' => 'required',
        'identity'  => 'required,identity',
        'phone' => 'required,phone:CN',
        'company' => 'required',
    ];

    public function importData($results, $sessionKey = null)
    {

        $collection = new Collection($results);
        foreach ($results as $row => $teacher){
            try {
                trace_sql();
                trace_log($teacher);
                $teacherRepository = new TeacherRepository;
                $teacherRepository->create($teacher);
                $this->logCreated();
            } catch (\Exception $ex) {
                $this->logError($row, $ex->getMessage());
            }
        }

        /**
        trace_sql();
        $collection->chunk(100, function ($teachers) {

            foreach ($teachers as $row => $teacher) {
                trace_log($teacher['name']);
                try {
                    $teacherRepository = new TeacherRepository;
                    $teacherRepository->create($teacher);
                    $this->logCreated();
                } catch (\Exception $ex) {
                    $this->logError($row, $ex->getMessage());
                }
            }
        });
         **/
    }

}