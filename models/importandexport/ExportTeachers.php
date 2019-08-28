<?php
/**
 * Created by PhpStorm.
 * User: samubra
 * Date: 19-5-25
 * Time: 下午8:37
 */

namespace Samubra\Training\Models\ImportAndExport;


use Samubra\Training\Models\Teacher;

class ExportTeachers extends \Backend\Models\ExportModel
{
    public function exportData($columns, $sessionKey = null)
    {
        $teachers = Teacher::all();
        $teachers->each(function($teacher) use ($columns) {
            $teacher->addVisible($columns);
        });
        return $teachers->toArray();
    }
}